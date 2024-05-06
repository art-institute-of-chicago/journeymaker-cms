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
        private ThemeSeeder $themeSeeder,
        private ImageComparator $imageComparator = new ImageComparator()
    ) {
        parent::__construct();

        $this->themeSeeder = App::make(ThemeSeeder::class);

        $this->themes = (fn () => $this->themes)->call($this->themeSeeder);
    }

    public function handle()
    {
        $this->themes->each(function ($theme) {
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
                            $locale
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
        string $locale
    ): void {
        $url1 = Arr::get($object, $field);
        $url2 = Arr::get($translation, $field);

        if ($url1 !== $url2) {
            $similarity = $this->getSimilarity($url1, $url2);

            if ($similarity > 70) {
                return;
            }

            $this->error($type.' '.$object['id'].' Img:'.$url1);
            $this->error(Str::upper($locale).' '.$type.' '.$translation['id'].' Img:'.$url2);
            $this->error('Similarity: '.$similarity);
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
