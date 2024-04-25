<?php

namespace Database\Seeders;

use App\Models\Theme;
use Database\Seeders\Behaviors\HasTwillSeeding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ThemeSeeder extends Seeder
{
    use HasTwillSeeding;

    public function __construct(
        private Collection $primaryLanguage,
        private Collection $translations,
        private Collection $themes,
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
        $this->themes
            ->each(function ($rawTheme) {
                $theme = Theme::factory()->create([
                    'title' => $rawTheme['title'],
                    'intro' => $rawTheme['intro'],
                    'journey_guide' => $rawTheme['journey_guide'],
                    'published' => true,
                ]);

                collect($rawTheme['translations'])->each(
                    fn ($translation, $locale) => $this->addTranslation(
                        $theme,
                        [
                            'title' => $translation['title'],
                            'intro' => $translation['intro'],
                            'journey_guide' => $translation['journey_guide'],
                        ],
                        $locale
                    )
                );

                $theme->translations()->update(['active' => true]);

                $this->call(ThemePromptSeeder::class, false, [
                    'theme' => $theme,
                    'themePrompts' => $rawTheme['prompts'],
                ]);

                $this->addImage($theme, $rawTheme['icon']['url'], 'icon');
                $this->addImage($theme, $rawTheme['guideCoverArt']['url'], 'cover');
                $this->addImage($theme, $rawTheme['guideCoverArtHome']['url'], 'cover_home');
                collect($rawTheme['bgs'])->each(fn ($bg) => $this->addImage($theme, $bg['url'], 'backgrounds'));
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
