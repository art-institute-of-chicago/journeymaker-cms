<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artwork extends Model implements Sortable
{
    use HasTranslation, HasMedias, HasRevisions, HasPosition, HasFactory;
    use HasApiModel;
    use HasApiRelations;

    protected $apiModelClass = \App\Models\Api\Artwork::class;

    protected $fillable = [
        'published',
        'title',
        'description',
        'image_id',
        'position',
    ];

    public $translatedAttributes = [
        'title',
        'description',
    ];

}
