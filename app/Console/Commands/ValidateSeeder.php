<?php

namespace App\Console\Commands;

use Database\Seeders\ThemeSeeder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use SapientPro\ImageComparator\ImageComparator;

class ValidateSeeder extends Command
{
    protected $signature = 'app:validate-seeder';

    protected $description = 'This command will validate the seeder sources.';

    public function __construct(
        private Collection $themes,
        private ImageComparator $imageComparator = new ImageComparator()
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $themes = (fn () => $this->themes)->call(App::make(ThemeSeeder::class));

        $themes->each(function ($theme) {
            collect($theme['translations'])
                ->each(fn ($translation, $locale) => $this->checkImg(
                    'icon.url',
                    'Theme',
                    $theme,
                    $translation,
                    $locale
                ));

            collect($theme['prompts'])
                ->each(fn ($prompt) => collect($prompt['artworks'])
                    ->each(fn ($artwork) => collect($artwork['translations'])
                        ->each(fn ($translation, $locale) => $this->checkImg(
                            'img.url',
                            'Artwork',
                            $artwork,
                            $translation,
                            $locale,
                            ['theme' => $theme, 'prompt' => $prompt, 'artwork' => $artwork]
                        ))
                    )
                );
        });
    }

    private function checkImg(
        string $field,
        string $type,
        array $object,
        array $translation,
        string $locale,
        array $meta = []
    ): void {
        $url1 = Arr::get($object, $field);
        $url2 = Arr::get($translation, $field);

        if ($url1 !== $url2) {
            $similarity = $this->getSimilarity($url1, $url2);

            if ($similarity > 50) {
                return;
            }

            if ($type === 'Theme') {
                $this->comment('Theme: '.$object['title']);
            }

            if ($type === 'Artwork') {
                $this->comment('Theme: '.$meta['theme']['title']);
                $this->comment('Prompt: '.$meta['prompt']['title']);
                $this->comment('Artwork: '.$meta['artwork']['title']);
            }

            $this->comment('EN ID: '.$object['id'].' Img: '.$url1);
            $this->comment(Str::upper($locale).' ID: '.$translation['id'].' Img: '.$url2);
            $this->comment('Image Similarity: '.$similarity);
            $this->newLine(2);
        }
    }

    private function getSimilarity(?string $url1, ?string $url2): int
    {
        try {
            return $url1 && $url2
                ? $this->imageComparator->compare($url1, $url2)
                : 0;
        } catch (Exception) {
            return 0;
        }
    }
}
