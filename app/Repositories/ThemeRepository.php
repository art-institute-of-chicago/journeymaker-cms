<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Theme;

class ThemeRepository extends ModuleRepository
{
    use HandleMedias, HandleRevisions, HandleTranslations;

    public function __construct(Theme $model)
    {
        $this->model = $model;
    }
}
