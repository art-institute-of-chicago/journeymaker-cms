<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Facades\App\Libraries\DamsImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'detail_narrative',
        'look_again',
        'activity_instructions',
        'location_directions',
        'is_on_view',
        'credit_line',
        'copyright_notice',
        'latitude',
        'longitude',
        'floor',
        'activity_template',
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
     * Replaces `imageFront()`. This more closely resembles Twill 3's
     * `HasMedias::image()` method, see:
     * https://twillcms.com/docs/api/3.x/A17/Twill/Models/Behaviors/HasMedias.html#method_image
     *
     * Define $mediasParams as they are defined in the Twill documentation:
     * https://twillcms.com/docs/form-fields/medias.html#content-example
     *
     * An additional `field` key may be defined for a crop that specifies the field
     * on the API record that contains the image ID. The default is `image_id`.
     *
     * Example:
     *  public $mediasParams = [
     *      'iiif' => [
     *          'default' => [
     *              [
     *                  'name' => 'default',
     *                  'field' => 'image_id',
     *                  'height' => 800,
     *                  'width' => 800,
     *              ]
     *          ]
     *      ]
     *  ];
     */
    public function image($role, $crop = 'default', $params = [])
    {
        $cropParams = $this->getMediasParams()[$role][$crop][0];
        $imageField = $cropParams['field'] ?? 'image_id';

        return DamsImageService::getUrl($this->{$imageField}, $cropParams + $params);
    }
}
