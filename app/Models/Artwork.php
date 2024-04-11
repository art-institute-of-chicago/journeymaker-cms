<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artwork extends Model implements Sortable
{
    use HasApiModel;
    use HasFactory;
    use HasMedias;
    use HasPosition;
    use HasRevisions;
    use HasTranslation;

    protected $apiModelClass = \App\Models\Api\Artwork::class;

    protected $fillable = [
        'published',
        'position',
        'datahub_id',
        'title',
        'artist_display',
        'is_on_view',
        'credit_line',
        'copyright_notice',
        'latitude',
        'longitude',
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
}
