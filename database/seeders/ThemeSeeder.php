<?php

namespace Database\Seeders;

use A17\Twill\Models\User;
use App\Repositories\ThemeRepository;
use Database\Seeders\Behaviors\HasTwillSeeding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SapientPro\ImageComparator\ImageComparator;

class ThemeSeeder extends Seeder
{
    use HasTwillSeeding;

    public function __construct(
        private Collection $primaryLanguage,
        private Collection $translations,
        private Collection $themes,
        private ImageComparator $imageComparator = new ImageComparator()
    ) {
        $data = collect(config('journeymaker.locale_sources'))
            ->filter()
            ->map(fn ($source) => $this->getJsonFromSource($source))
            ->map(fn ($source) => collect($source['themes']));

        $this->primaryLanguage = $data->pull('en');

        $this->translations = $data;

        $this->themes = $this->primaryLanguage
            ->map(fn ($primaryLanguage, $themeKey) => $this->addTranslationsToTheme($primaryLanguage, $themeKey));
    }

    public function run(): void
    {
        // Log in as admin to attribute activity to them
        Auth::guard('twill_users')->login(User::find(1));

        $this->themes
            ->each(function ($rawTheme) {

                $themeData = collect([
                    'en' => [
                        'title' => $rawTheme['title'],
                        'intro' => $rawTheme['intro'],
                        'journey_guide' => $rawTheme['journey_guide'],
                    ],
                ])->merge($rawTheme['translations'])->map(
                    fn ($translation, $locale) => [
                        'title' => [$locale => $translation['title']],
                        'intro' => [$locale => $translation['intro']],
                        'journey_guide' => [$locale => $translation['journey_guide']],
                    ]
                )->reduce(function (array $carry, array $translation) {
                    return array_merge_recursive($carry, $translation);
                }, []);

                $theme = app()->make(ThemeRepository::class)->create([
                    ...$themeData,
                    'published' => true,
                ]);

                activity()->performedOn($theme)->causedBy(User::find(1))->log('created');

                $theme->translations()->update(['active' => true]);

                $this->addImage($theme, $rawTheme['icon']['url'], 'icon');
                $this->addImage($theme, $rawTheme['guideCoverArt']['url'], 'cover');
                $this->addImage($theme, $rawTheme['guideCoverArtHome']['url'], 'cover_home');
                collect($rawTheme['bgs'])->each(fn ($bg) => $this->addImage($theme, $bg['url'], 'backgrounds'));

                $medias = app()->make(ThemeRepository::class)->getFormFieldsHandleMedias($theme, [])['medias'] ?? [];

                $revision = $theme->revisions()->first();
                $payload = json_decode($revision->payload);
                $payload->medias = $medias;
                $revision->update(['payload' => json_encode($payload)]);

                $this->call(ThemePromptSeeder::class, false, [
                    'theme' => $theme,
                    'themePrompts' => $rawTheme['prompts'],
                ]);
            });
    }

    public function addTranslationsToTheme(array $theme, int $themeKey): array
    {
        return [
            ...$theme,
            'translations' => $this->getTranslationsForKey($themeKey),
            'prompts' => $this->addTranslationsToPrompts($theme['prompts'], $themeKey),
        ];
    }

    public function addTranslationsToPrompts(array $prompts, int $themeKey): array
    {
        return collect($prompts)
            ->map(fn ($prompt, $promptKey) => [
                ...$prompt,
                'translations' => $this->getTranslationsForKey($themeKey.'.prompts.'.$promptKey),
                'artworks' => $this->addTranslationsToArtworks($prompt['artworks'], $themeKey, $promptKey),
            ])->toArray();
    }

    public function addTranslationsToArtworks(array $artworks, int $themeKey, int $promptKey): array
    {
        return collect($artworks)->map(fn ($artwork, $artworkKey) => [
            ...$artwork,
            'translations' => $this->getTranslationsForKey($themeKey.'.prompts.'.$promptKey.'.artworks.'.$artworkKey),
        ])->toArray();
    }

    public function getTranslationsForKey(string $key): array
    {
        return $this->translations
            ->map(fn ($translations) => Arr::get($translations, $key))
            ->filter()
            ->toArray();
    }

    /**
     * Get JSON from source and cache it for a day
     */
    private function getJsonFromSource(string $source): array
    {
        return Cache::remember($source, 60 * 60 * 24, fn () => Http::get($source)->json());
    }
}
