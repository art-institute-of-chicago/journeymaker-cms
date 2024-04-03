<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Theme;

class ThemeTranslation extends Model
{
    protected $baseModuleModel = Theme::class;
}
