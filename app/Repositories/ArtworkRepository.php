<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Api\Artwork as ApiArtwork;
use App\Models\Artwork;
use Illuminate\Support\Arr;

class ArtworkRepository extends ModuleRepository
{
    use HandleMedias, HandleRevisions, HandleTranslations;

    public function __construct(Artwork $model)
    {
        $this->model = $model;
    }

    public function prepareFieldsBeforeCreate(array $fields): array
    {
        $apiFields = ApiArtwork::query()
            ->find($fields['datahub_id'], [
                'position',
                'artist_display',
                'is_on_view',
                'credit_line',
                'copyright_notice',
                'latitude',
                'longitude',
                'image_id',
                'gallery_id',
            ]
            )->toArray();

        $translatedFields = [
            'artist_display' => [
                'en' => Arr::pull($apiFields, 'artist_display'),
            ],
        ];

        return parent::prepareFieldsBeforeCreate([...$fields, ...$apiFields, ...$translatedFields]);
    }
}
