<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Media;
use A17\Twill\Models\Model;
use A17\Twill\Services\MediaLibrary\ImageService;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use Facades\App\Libraries\DamsImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use stdClass;

class Artwork extends Model implements Sortable
{
    use HasFactory;
    use HasMedias;
    use HasPosition;
    use HasRevisions;
    use HasTranslation;

    public const ARTWORK_API_FIELDS = [
        'id',
        'main_reference_number',
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
        'position',
        'datahub_id',
        'title',
        'artist_display',
        'location_directions',
        'is_on_view',
    ];

    protected $casts = [
        'is_on_view' => 'boolean',
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:14',
    ];

    public $translatedAttributes = [
        'title',
        'artist_display',
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

    /**
     * Returns the URL of the attached image for a role and crop.
     */
    public function image(
        string $role,
        string $crop = 'default',
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
            return DamsImageService::getUrl(
                $this->image_id,
                $this->getMediasParams()['iiif'][$crop][0] + $params
            );
        }

        return ImageService::getTransparentFallbackUrl();
    }

    public function getArtworkApiData(): stdClass
    {
        $nullArtwork = (object) array_combine(
            Artwork::ARTWORK_API_FIELDS,
            array_pad([], count(Artwork::ARTWORK_API_FIELDS), null)
        );

        // @TODO - handle 404 exceptions in the API
        return (object) Cache::remember("artwork.{$this->datahub_id}", now()->addMinutes(1), fn () => app()->make(ApiQueryBuilder::class)
            ->get(self::ARTWORK_API_FIELDS, "/api/v1/artworks/{$this->datahub_id}")
            ->first() ?? $nullArtwork
        );
    }

    public function getGalleryApiData(?int $id): stdClass
    {
        $nullGallery = (object) array_combine(
            Artwork::GALLERY_API_FIELDS,
            array_pad([], count(Artwork::GALLERY_API_FIELDS), null)
        );

        return $id
            ? Cache::remember("gallery.{$id}", now()->addMinutes(1), fn () => app()->make(ApiQueryBuilder::class)
                ->get(self::GALLERY_API_FIELDS, "/api/v1/galleries/{$id}")
                ->first() ?? $nullGallery
            )
            : $nullGallery;
    }

    public static function cacheArtworkApiData(): void
    {
        self::all(['id', 'datahub_id', 'is_on_view'])
            ->chunk(100)
            ->each(function ($artworks) {
                $artworkIds = $artworks->pluck('datahub_id')->filter()->unique()->implode(',');
                $apiArtworks = app()->make(ApiQueryBuilder::class)
                    ->get(self::ARTWORK_API_FIELDS, "/api/v1/artworks?ids={$artworkIds}")
                    ->keyBy('id');

                $galleryIds = $apiArtworks->pluck('gallery_id')->filter()->unique()->implode(',');
                $apiGalleries = app()->make(ApiQueryBuilder::class)
                    ->get(self::GALLERY_API_FIELDS, "/api/v1/galleries?ids={$galleryIds}");

                $apiArtworks->each(fn ($artwork) => Cache::put("artwork.{$artwork->id}", $artwork, now()->addMinutes(5))
                );

                $apiGalleries->each(fn ($gallery) => Cache::put("gallery.{$gallery->id}", $gallery, now()->addMinutes(5))
                );

                $artworks->each(function ($artwork) use ($apiArtworks) {
                    $artwork->is_on_view = (bool) $apiArtworks[$artwork->datahub_id]->is_on_view;
                    if ($artwork->isDirty('is_on_view')) {
                        $artwork->save();
                    }
                });
            });
    }

    public function __get($key)
    {
        if (in_array($key, self::ARTWORK_API_FIELDS) && $key != 'id') {
            return $this->getArtworkApiData()->$key;
        }

        if (in_array($key, [...self::GALLERY_API_FIELDS, 'gallery_name']) && $key != 'id') {
            $key = $key == 'gallery_name' ? 'title' : $key;

            return $this->getGalleryApiData($this->gallery_id)->$key;
        }

        return parent::__get($key);
    }
}
