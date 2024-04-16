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
use Facades\App\Libraries\DamsImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class Artwork extends Model implements Sortable
{
    use HasFactory;
    use HasMedias;
    use HasPosition;
    use HasRevisions;
    use HasTranslation;

    protected $fillable = [
        'published',
        'position',
        'datahub_id',
        'title',
        'artist_display',
        'location_directions',
        'is_on_view',
        'credit_line',
        'copyright_notice',
        'latitude',
        'longitude',
        'floor',
        'image_id',
        'gallery_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:13',
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
}
