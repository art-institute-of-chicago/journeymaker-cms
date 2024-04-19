<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\ThemePromptArtwork;

class ThemePromptArtworkRepository extends ModuleRepository
{
    use HandleRevisions, HandleTranslations;

    public function __construct(ThemePromptArtwork $model)
    {
        $this->model = $model;
    }
}
