<?php

namespace Database\Seeders;

use A17\Twill\Http\Controllers\Admin\MediaLibraryController;
use A17\Twill\Models\Media;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $userModel = twillModel('user');

        (new $userModel())->forceFill([
            'name' => 'Admin',
            'email' => 'admin@artic.edu',
            'role' => 'SUPERADMIN',
            'published' => true,
            'registered_at' => now(),
            'password' => Hash::make('password'),
        ])->save();

        $this->getThemesByLocale()->each(function ($locales) {
            $english = $locales->pull('en');

            $translatable = ['title', 'intro'];

            $theme = Theme::factory()->create([
                ...Arr::only($english, $translatable),
                'published' => true,
            ]);

            $locales->each(
                fn ($translation, $locale) => $this->addTranslation($theme, Arr::only($translation, $translatable), $locale)
            );

            $theme->translations()->update(['active' => true]);

            $this->addThemeImage($theme, $english['icon']['url'], 'icon');
            $this->addThemeImage($theme, $english['guideCoverArt']['url'], 'cover');
            $this->addThemeImage($theme, $english['guideCoverArtHome']['url'], 'cover_home');
            collect($english['bgs'])->each(fn ($bg) => $this->addThemeImage($theme, $bg['url'], 'backgrounds'));
        });
    }

    /**
     * Get theme data for each locale
     * Each theme has translations for each locale
     */
    private function getThemesByLocale(): Collection
    {
        $themeLocales = collect(config('journeymaker.locale_sources'))
            ->filter()
            ->map(fn ($source) => $this->getJsonFromSource($source))
            ->map(fn ($source) => collect($source['themes']));

        if ($themeLocales->isEmpty()) {
            return collect();
        }

        $themeLocales = array_map(
            fn (...$themeLocales) => $themeLocales,
            ...$themeLocales->values()->toArray()
        );

        $locales = collect(config('journeymaker.locale_sources'))->keys();

        return collect($themeLocales)
            ->map(fn ($translations) => $locales->combine($translations));
    }

    /**
     * Get JSON from source and cache it for a day
     */
    private function getJsonFromSource(string $source): array
    {
        return Cache::remember($source, 60 * 60 * 24, fn () => Http::get($source)->json());
    }

    /**
     * Add provided translation to model
     */
    private function addTranslation(Model $model, array $translation, string $locale): void
    {
        $model->translations()->getRelated()->updateOrCreate(
            [$model->translations()->getForeignKeyName() => $model->id, 'locale' => $locale],
            $translation
        );
    }

    private function addThemeImage(Theme $theme, string $url, string $role): void
    {
        $media = $this->addMediaToLibrary($url);

        $theme->medias()->attach($media->id, [
            'metadatas' => '{}',
            'role' => $role,
            'crop' => 'default',
            'ratio' => 'default',
            'crop_x' => 0,
            'crop_y' => 0,
            'crop_w' => $media->width,
            'crop_h' => $media->height,
        ]);
    }

    public function addMediaToLibrary(string $url): Media
    {
        $imageName = basename($url);
        $imagePath = sys_get_temp_dir().'/'.$imageName;
        $imageFile = file_get_contents($url);

        file_put_contents($imagePath, $imageFile);

        $request = Request::create('', 'POST', [
            'unique_folder_name' => Str::uuid()->toString(),
            'qqfilename' => $imageName,
            'qqtotalfilesize' => strlen($imageFile),
        ], [], [
            'qqfile' => new UploadedFile($imagePath, $imageName, 'image/jpg', null, true),
        ]);

        return app()->make(MediaLibraryController::class)->storeFile($request);
    }
}
