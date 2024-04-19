<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Models\ThemePrompt;
use Database\Seeders\Behaviors\HasTwillSeeding;
use Illuminate\Database\Seeder;

class ThemePromptSeeder extends Seeder
{
    use HasTwillSeeding;

    public function run(Theme $theme, array $themePrompts): void
    {
        collect($themePrompts)->each(function ($rawThemePrompt) use ($theme) {
            $themePrompt = ThemePrompt::factory()->create([
                'title' => $rawThemePrompt['title'],
                'subtitle' => $rawThemePrompt['subtitle'],
                'theme_id' => $theme->id,
                'published' => true,
            ]);

            collect($rawThemePrompt['translations'])->each(
                fn ($translation, $locale) => $this->addTranslation(
                    $themePrompt,
                    [
                        'title' => $translation['title'],
                        'subtitle' => $translation['subtitle'],
                    ],
                    $locale
                )
            );

            $themePrompt->translations()->update(['active' => true]);

            $this->call(ArtworkSeeder::class, false, [
                'themePrompt' => $themePrompt,
                'artworks' => $rawThemePrompt['artworks'],
            ]);
        });
    }
}
