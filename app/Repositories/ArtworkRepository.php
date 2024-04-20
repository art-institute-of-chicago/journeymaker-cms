<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Models\Artwork;
use Illuminate\Support\Arr;

class ArtworkRepository extends ModuleRepository
{
    use HandleMedias, HandleRevisions, HandleTranslations;

    public function __construct(Artwork $model, public ApiQueryBuilder $api)
    {
        $this->model = $model;
    }

    public function prepareFieldsBeforeCreate(array $fields): array
    {
        $apiFields = $this->api
            ->get(Artwork::ARTWORK_API_FIELDS, '/api/v1/artworks/'.$fields['datahub_id'])
            ->map(fn ($artwork) => (array) $artwork)
            ->first();

        if ($apiFields['is_on_view'] === true) {
            $galleryFields = $this->api
                ->get(endpoint: '/api/v1/galleries/'.$apiFields['gallery_id'])
                ->map(fn ($gallery) => (array) $gallery)
                ->first();

            $apiFields['gallery_name'] = $galleryFields['title'] ?? null;
            $apiFields['latitude'] = $galleryFields['latitude'] ?? null;
            $apiFields['longitude'] = $galleryFields['longitude'] ?? null;
            $apiFields['floor'] = $galleryFields['floor'] ?? null;
        }

        $translatedFields = [
            'artist_display' => [
                'en' => Arr::pull($apiFields, 'artist_display'),
            ],
        ];

        return parent::prepareFieldsBeforeCreate([...$fields, ...$apiFields, ...$translatedFields]);
    }
}
