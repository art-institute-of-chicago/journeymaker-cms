<?php

namespace Database\Seeders;

use A17\Twill\Models\User;
use App\Models\Theme;
use App\Repositories\ThemePromptRepository;
use Database\Seeders\Behaviors\HasTwillSeeding;
use Illuminate\Database\Seeder;

class ThemePromptSeeder extends Seeder
{
    use HasTwillSeeding;

    public function run(Theme $theme, array $themePrompts): void
    {
        collect($themePrompts)->each(function ($rawThemePrompt) use ($theme) {
            $themePromptData = collect([
                'en' => [
                    'title' => $rawThemePrompt['title'],
                    'subtitle' => $rawThemePrompt['subtitle'],
                ],
            ])->merge($rawThemePrompt['translations'])->map(
                fn ($translation, $locale) => [
                    'title' => [$locale => $translation['title']],
                    'subtitle' => [$locale => $translation['subtitle']],
                ]
            )->reduce(function (array $carry, array $translation) {
                return array_merge_recursive($carry, $translation);
            }, []);

            $themePrompt = app()->make(ThemePromptRepository::class)->create([
                ...$themePromptData,
                'theme_id' => $theme->id,
                'published' => true,
            ]);

            activity()->performedOn($themePrompt)->causedBy(User::find(1))->log('created');

            $themePrompt->translations()->update(['active' => true]);

            $this->call(ArtworkSeeder::class, false, [
                'themePrompt' => $themePrompt,
                'artworks' => $rawThemePrompt['artworks'],
            ]);
        });
    }
}
