<?php

namespace Database\Seeders;

use A17\Twill\Models\Model;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Models\ActivityTemplate;
use App\Models\Artwork;
use App\Models\Theme;
use App\Models\ThemePrompt;
use App\Models\ThemePromptArtwork;
use Database\Seeders\Behaviors\HasTwillSeeding;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    use HasTwillSeeding;

    private ApiQueryBuilder $api;

    private $locales = ['es', 'zh'];

    public function __construct()
    {
        $this->api = app(ApiQueryBuilder::class);
    }

    public function run(): void
    {
        collect(range(1, 4))->each(function ($themeNumber) {
            $theme = Theme::factory()->create([
                'title' => 'Test Theme '.$themeNumber,
                'intro' => 'Test Theme '.$themeNumber.' Intro',
                'journey_guide' => 'Test Theme '.$themeNumber.' Journey Guide',
                'published' => true,
            ]);

            $this->addTranslations($theme, ['title', 'intro', 'journey_guide']);

            $this->addImage($theme, base_path('tests/data/images/theme-shape-face.png'), 'shape_face');
            $this->addImage($theme, base_path('tests/data/images/theme-icon.png'), 'icon');
            $this->addImage($theme, base_path('tests/data/images/theme-cover.png'), 'cover');
            $this->addImage($theme, base_path('tests/data/images/theme-cover-home.png'), 'cover_home');

            collect(range(1, 4))->each(function ($promptNumber) use ($theme) {
                $themePrompt = ThemePrompt::factory()->create([
                    'title' => 'Test Prompt '.$promptNumber,
                    'subtitle' => 'Test Prompt '.$promptNumber.' Subtitle',
                    'theme_id' => $theme->id,
                    'published' => true,
                ]);

                $this->addTranslations(
                    $themePrompt,
                    ['title', 'subtitle']
                );

                $artworks = $this->api->get(endpoint: '/api/v1/artworks')
                    ->map(fn ($artwork) => $artwork = Artwork::firstOrCreate(
                        ['datahub_id' => $artwork->id],
                        [
                            'title' => $artwork->title,
                            'artist' => $artwork->artist_title,
                            'location_directions' => 'Test Location Directions',
                            'is_on_view' => $artwork->is_on_view,
                            'image_id' => $artwork->image_id,
                            'published' => true,
                        ]
                    ));

                $artworks->each(fn ($artwork) => $this->addTranslations(
                    $artwork,
                    ['title', 'artist', 'location_directions']
                )
                );

                $themePromptArtworks = $artworks->map(fn ($artwork) => ThemePromptArtwork::factory()->create([
                    'detail_narrative' => 'Test Detail Narrative',
                    'viewing_description' => 'Test Viewing Description',
                    'activity_instructions' => 'Test Activity Instructions',
                    'theme_prompt_id' => $themePrompt->id,
                    'artwork_id' => $artwork->id,
                    'activity_template' => ActivityTemplate::all()->random()->id,
                ]));

                $themePromptArtworks->each(fn ($themePromptArtwork) => $this->addTranslations(
                    $themePromptArtwork,
                    ['detail_narrative', 'viewing_description', 'activity_instructions']
                )
                );
            });
        });
    }

    private function addTranslations(Model $model, array $columns): void
    {
        collect($this->locales)->each(
            fn ($locale) => $this->addTranslation(
                $model,
                (array) $model->only($columns),
                $locale
            )
        );

        $model->translations()->update(['active' => true]);
    }
}
