<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Artwork;

class ArtworkTranslation extends Model
{
    protected $baseModuleModel = Artwork::class;
}
