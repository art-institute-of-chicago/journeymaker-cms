<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Media;
use A17\Twill\Models\Model;
use A17\Twill\Services\MediaLibrary\ImageService;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use stdClass;

class Artwork extends Model
{
    use HasFactory;
    use HasMedias;
    use HasRevisions;
    use HasTranslation;

    public const ARTWORK_API_FIELDS = [
        'id',
        'main_reference_number',
        'thumbnail',
        'artist_title',
        'artist_display',
        'medium_display',
        'date_display',
        'credit_line',
        'copyright_notice',
        'is_on_view',
        'image_id',
        'gallery_id',
    ];

    public const GALLERY_API_FIELDS = [
        'id',
        'title',
        'latitude',
        'longitude',
        'floor',
    ];

    protected $fillable = [
        'published',
        'datahub_id',
        'title',
        'artist',
        'location_directions',
        'is_on_view',
        'image_id',
    ];

    protected $casts = [
        'is_on_view' => 'boolean',
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:14',
    ];

    public $translatedAttributes = [
        'title',
        'artist',
        'location_directions',
    ];

    public $mediasParams = [
        'override' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 0,
                ],
            ],
            'thumbnail' => [
                [
                    'name' => 'thumbnail',
                    'ratio' => 1,
                    'height' => 112,
                    'width' => 112,
                ],
            ],
        ],
        'iiif' => [
            'default' => [
                [
                    'name' => 'full',
                    'height' => 800,
                    'width' => 800,
                ],
            ],
            'thumbnail' => [
                [
                    'name' => 'thumbnail',
                    'height' => 112,
                    'width' => 112,
                ],
            ],
        ],
    ];

    public function themePrompts(): HasManyThrough
    {
        return $this->hasManyThrough(
            ThemePrompt::class,
            ThemePromptArtwork::class,
            'artwork_id',
            'id',
            'id',
            'theme_prompt_id'
        );
    }

    public function apiLog(): HasMany
    {
        return $this->hasMany(ApiLog::class, 'datahub_id', 'datahub_id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->published()->onView()->translated();
    }

    public function scopeNotActive(Builder $query): void
    {
        $query->where('published', false)
            ->orWhere->offView()
            ->orWhere->missingTranslations();
    }

    public function scopeTranslated(Builder $query): void
    {
        $query->whereDoesntHave('translations', fn (Builder $query) => $query->where('active', false));
    }

    public function scopeMissingTranslations(Builder $query): void
    {
        $query->whereHas('translations', fn (Builder $query) => $query->where('active', false));
    }

    public function scopeOnView(Builder $query): void
    {
        $query->where('is_on_view', true);
    }

    public function scopeOffView(Builder $query): void
    {
        $query->where('is_on_view', false);
    }

    public function defaultCmsImage($params = [])
    {
        // If requesting a thumbnail, return the thumbnail image
        if ($params = ['w' => 100, 'h' => 100]) {
            return $this->image('override', 'thumbnail', $params, false, true, $this->medias->first());
        }

        $media = $this->medias->first();

        if ($media) {
            return $this->image(null, null, $params, true, true, $media) ?? ImageService::getTransparentFallbackUrl();
        }

        return ImageService::getTransparentFallbackUrl();
    }

    /**
     * Returns the URL of the attached image for a role and crop.
     */
    public function image(
        $role,
        $crop = 'default',
        array $params = [],
        bool $has_fallback = false,
        bool $cms = false,
        Media|null|bool $media = null
    ) {
        if ($media = $media ?: $this->findMedia($role, $crop)) {
            $crop_params = Arr::only($media->pivot->toArray(), $this->cropParamsKeys);

            return $cms
                ? ImageService::getCmsUrl($media->uuid, $crop_params + $params)
                : ImageService::getUrlWithCrop($media->uuid, $crop_params, $params);
        }

        if ($has_fallback) {
            return null;
        }

        if ($this->image_id) {
            return $this->getDimImageUrl(
                $this->getMediasParams()['iiif'][$crop][0] + $params
            );
        }

        return ImageService::getTransparentFallbackUrl();
    }

    public function getDimImageUrl(array $params = []): ?string
    {
        if (! $this->image_id) {
            return null;
        }

        $width = $params['width'] ?? '';
        $height = $params['height'] ?? '';
        $size = $params['size'] ?? 'full';

        $dimensions = '!3000,3000';

        if ($width != '' || $height != '') {
            $dimensions = '!'.$width.','.$height;
        }

        $baseUrl = config('dams.cdn_enabled') ? config('dams.base_url_cdn') : config('dams.base_url');
        $version = config('dams.version');

        return $baseUrl.$version.'/'.$this->image_id.'/'.$size.'/'.$dimensions.'/0/default.jpg';
    }

    public function getArtworkApiData(): stdClass
    {
        $nullArtwork = (object) array_combine(
            Artwork::ARTWORK_API_FIELDS,
            array_pad([], count(Artwork::ARTWORK_API_FIELDS), null)
        );

        return $this->getApiData(
            self::ARTWORK_API_FIELDS,
            "/api/v1/artworks/{$this->datahub_id}",
            $nullArtwork
        );
    }

    public function getGalleryApiData(?int $id): stdClass
    {
        $nullGallery = (object) array_combine(
            Artwork::GALLERY_API_FIELDS,
            array_pad([], count(Artwork::GALLERY_API_FIELDS), null)
        );

        if (! $id) {
            return $nullGallery;
        }

        return $this->getApiData(
            self::GALLERY_API_FIELDS,
            "/api/v1/galleries/{$id}",
            $nullGallery
        );
    }

    public function getApiData(array $columns, string $endpoint, object $default): object
    {
        try {
            return (object) Cache::remember(
                $endpoint,
                now()->addMinutes(5),
                fn () => app()->make(ApiQueryBuilder::class)->get($columns, $endpoint)->first()
            );
        } catch (Exception) {
            return $default;
        }
    }

    public static function cacheArtworkApiData(): void
    {
        self::all(['id', 'datahub_id', 'is_on_view', 'image_id'])
            ->chunk(100)
            ->each(function ($artworks) {
                $artworkIds = $artworks->pluck('datahub_id')->filter()->unique()->implode(',');
                $apiArtworks = app()->make(ApiQueryBuilder::class)
                    ->get(self::ARTWORK_API_FIELDS, "/api/v1/artworks?ids={$artworkIds}")
                    ->keyBy('id');

                $galleryIds = $apiArtworks->pluck('gallery_id')->filter()->unique()->implode(',');
                $apiGalleries = app()->make(ApiQueryBuilder::class)
                    ->get(self::GALLERY_API_FIELDS, "/api/v1/galleries?ids={$galleryIds}");

                $apiArtworks->each(fn ($artwork) => Cache::put("/api/v1/artworks/{$artwork->id}", $artwork, now()->addMinutes(5)));
                $apiGalleries->each(fn ($gallery) => Cache::put("/api/v1/galleries/{$gallery->id}", $gallery, now()->addMinutes(5)));

                $artworks->each(function ($artwork) use ($apiArtworks) {
                    $artwork->is_on_view = (bool) $apiArtworks[$artwork->datahub_id]->is_on_view;
                    $artwork->image_id = $apiArtworks[$artwork->datahub_id]->image_id;
                    if ($artwork->isDirty('is_on_view') || $artwork->isDirty('image_id')) {
                        $artwork->save();
                    }
                });
            });
    }

    public function __get($key)
    {
        if (in_array($key, self::ARTWORK_API_FIELDS) && ! $this->getAttribute($key)) {
            return $this->getArtworkApiData()->$key;
        }

        if (in_array($key, [...self::GALLERY_API_FIELDS, 'gallery_name']) && ! $this->getAttribute($key)) {
            $key = $key == 'gallery_name' ? 'title' : $key;

            return $this->getGalleryApiData($this->gallery_id)->$key;
        }

        return parent::__get($key);
    }
}
