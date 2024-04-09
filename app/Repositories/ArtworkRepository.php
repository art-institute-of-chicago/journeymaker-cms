<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Artwork;

class ArtworkRepository extends ModuleRepository
{
    use HandleTranslations, HandleMedias, HandleRevisions;

    public function __construct(Artwork $model)
    {
        $this->model = $model;
    }
}
