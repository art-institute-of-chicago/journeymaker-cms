<?php

namespace Tests\Feature;

use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Models\Artwork;
use App\Models\Theme;
use App\Models\ThemePrompt;
use Database\Seeders\TestSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Mocks\ApiQueryBuilder as MockApiQueryBuilder;
use Tests\TestCase;

class JsonDataTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the API query builder to return data from stubs
        // Mocked data is used to in the seeder to create the database records
        $this->app->bind(ApiQueryBuilder::class, fn () => new MockApiQueryBuilder(fn ($endpoint) => match (true) {
            Str::startsWith($endpoint, '/api/v1/artworks') => json_decode(file_get_contents(base_path('tests/Mocks/stubs/artworks.json')))->data ?? null,
            Str::startsWith($endpoint, '/api/v1/galleries') => json_decode(file_get_contents(base_path('tests/Mocks/stubs/galleries.json')))->data ?? null,
        }));

        $this->seed(TestSeeder::class);
    }

    #[Test]
    public function it_provides_english_data(): void
    {
        $this->get('/json/data.json')
            ->assertStatus(200);
    }

    #[Test]
    public function it_provides_spanish_data(): void
    {
        $this->get('/json/data-es.json')
            ->assertStatus(200);
    }

    #[Test]
    public function it_provides_mandarin_data(): void
    {
        $this->get('/json/data-zh-hans.json')
            ->assertStatus(200);
    }

    #[Test]
    public function it_provides_english_data_for_unknown_locale(): void
    {
        $this->get('/json/unknown.json')
            ->assertStatus(200);
    }

    #[Test]
    public function it_provides_activity_templates(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(5, 'activityTemplates')
            ->assertJsonStructure([
                'activityTemplates' => [
                    '*' => ['id', 'img'],
                ],
            ]);
    }

    #[Test]
    public function it_provides_themes(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(4, 'themes')
            ->assertJsonStructure([
                'themes' => [
                    '*' => [
                        'id',
                        'title',
                        'intro',
                        'shapeFace',
                        'icon' => [
                            'url',
                            'width',
                            'height',
                        ],
                        'guideCoverArt' => [
                            'url',
                            'width',
                            'height',
                        ],
                        'guideCoverArtHome' => [
                            'url',
                            'width',
                            'height',
                        ],
                        'bgs' => [
                            '*' => [
                                'url',
                                'width',
                                'height',
                            ],
                        ],
                        'prompts',
                        'journey_guide',
                    ],
                ],
            ])
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('themes.0.id', 1)
                ->where('themes.0.title', 'Test Theme 1')
                ->where('themes.0.intro', 'Test Theme 1 Intro')
                ->where('themes.0.journey_guide', 'Test Theme 1 Journey Guide')
                ->where('themes.0.icon.width', 76)
                ->where('themes.0.icon.height', 77)
                ->where('themes.0.guideCoverArt.width', 1125)
                ->where('themes.0.guideCoverArt.height', 1500)
                ->where('themes.0.guideCoverArtHome.width', 1125)
                ->where('themes.0.guideCoverArtHome.height', 1500)
                ->whereType('themes.0.bgs', 'array')
                ->whereType('themes.0.prompts', 'array')
                ->etc()
            );
    }

    #[Test]
    public function it_provides_prompts(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(4, 'themes.0.prompts')
            ->assertJsonStructure([
                'themes' => [
                    '*' => [
                        'prompts' => [
                            '*' => [
                                'id',
                                'title',
                                'subtitle',
                                'artworks',
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('themes.0.prompts.0.id', 1)
                ->where('themes.0.prompts.0.title', 'Test Prompt 1')
                ->where('themes.0.prompts.0.subtitle', 'Test Prompt 1 Subtitle')
                ->whereType('themes.0.prompts.0.artworks', 'array')
                ->etc()
            );
    }

    #[Test]
    public function it_provides_artworks(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(8, 'themes.0.prompts.0.artworks')
            ->assertJsonStructure([
                'themes' => [
                    '*' => [
                        'prompts' => [
                            '*' => [
                                'artworks' => [
                                    '*' => [
                                        'id',
                                        'title',
                                        'img',
                                        'artwork_thumbnail',
                                        'img_medium',
                                        'img_large',
                                        'artist',
                                        'year',
                                        'medium',
                                        'credit',
                                        'galleryId',
                                        'galleryName',
                                        'closerLook',
                                        'detailNarrative',
                                        'viewingDescription',
                                        'activityTemplate',
                                        'activityInstructions',
                                        'locationDirections',
                                        'mapX',
                                        'mapY',
                                        'floor',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('themes.0.prompts.0.artworks.0.id', 1)
                ->where('themes.0.prompts.0.artworks.0.title', 'A13: New England Bedroom, 1750-1850')
                ->where('themes.0.prompts.0.artworks.0.artist', 'Narcissa Niblack Thorne')
                ->whereType('themes.0.prompts.0.artworks.0.img', 'array')
                ->whereType('themes.0.prompts.0.artworks.0.artwork_thumbnail', 'array')
                ->whereType('themes.0.prompts.0.artworks.0.img_medium', 'array')
                ->whereType('themes.0.prompts.0.artworks.0.img_large', 'array')
                ->etc()
            );
    }

    #[Test]
    public function only_eight_artworks_are_visible(): void
    {
        $themePrompt = ThemePrompt::find(1);

        $visibleArtworks = $themePrompt->artworks()->active()->count();

        $this->assertGreaterThan(8, $visibleArtworks);

        $this->get('/json/data.json')
            ->assertJsonCount(8, 'themes.0.prompts.0.artworks')
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('themes.0.prompts.0.id', $themePrompt->id)
                ->etc()
            );
    }

    #[Test]
    public function only_published_themes_are_public(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(4, 'themes');

        Theme::find(1)->update(['published' => false]);

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(3, 'themes');
    }

    #[Test]
    public function only_published_prompts_are_public(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(4, 'themes.0.prompts');

        ThemePrompt::find(1)->update(['published' => false]);

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(3, 'themes.0.prompts');
    }

    #[Test]
    public function only_published_artworks_are_public(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(8, 'themes.0.prompts.0.artworks');

        $visibleArtworks = Artwork::active()->get();

        // Prompts can have many associated artworks but only the first 8 visible artworks are included
        // Take all the currently public artworks and unpublish enough to leave 7
        $visibleArtworks->take(8 - $visibleArtworks->count() - 1)
            ->each(fn ($artwork) => $artwork->update(['published' => false]));

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(7, 'themes.0.prompts.0.artworks');
    }

    #[Test]
    public function themes_require_all_translations_to_be_active(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(4, 'themes');

        Theme::find(1)->translations()->where('locale', 'es')->update(['active' => false]);

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(3, 'themes');
    }

    #[Test]
    public function prompts_require_all_translations_to_be_active(): void
    {
        $this->get('/json/data.json')
            ->assertJsonCount(4, 'themes.0.prompts');

        ThemePrompt::find(1)->translations()->where('locale', 'es')->update(['active' => false]);

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(3, 'themes.0.prompts');
    }

    #[Test]
    public function prompt_artworks_require_all_translations_to_be_active(): void
    {
        $themePrompt = ThemePrompt::find(1);

        $this->get('/json/data.json')
            ->assertJsonCount(8, 'themes.0.prompts.0.artworks');

        $visibleArtworks = $themePrompt->artworks()->active()->get();

        // Prompts can have many associated artworks but only the first 8 visible artworks are included
        // Take all the currently public artworks and remove enough translations to leave 7
        $visibleArtworks->take(8 - $visibleArtworks->count() - 1)
            ->each(fn ($artwork) => $artwork->translations()->where('locale', 'es')->update(['active' => false]));

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(7, 'themes.0.prompts.0.artworks');

        // Artwork also requires all translations to be active
        $themePrompt->artworks()->active()->get()->first()
            ->artwork->translations()->where('locale', 'es')->update(['active' => false]);

        Cache::forget('data.json');

        $this->get('/json/data.json')
            ->assertJsonCount(6, 'themes.0.prompts.0.artworks');
    }
}
