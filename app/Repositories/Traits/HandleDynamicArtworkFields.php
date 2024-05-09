<?php

namespace App\Repositories\Traits;

use A17\Twill\Models\Contracts\TwillModelContract;
use App\Models\Artwork;
use App\Repositories\ArtworkRepository;

trait HandleDynamicArtworkFields
{
    public function prepareFieldsBeforeCreateHandleDynamicArtworkFields(array $fields): array
    {
        return $this->prepareFieldsBeforeSaveHandleDynamicArtworkFields(null, $fields);
    }

    public function prepareFieldsBeforeSaveHandleDynamicArtworkFields(?TwillModelContract $object, array $fields): array
    {
        if (static::class != ArtworkRepository::class) {
            return $fields;
        }

        $exclude = collect([...Artwork::ARTWORK_API_FIELDS, ...Artwork::GALLERY_API_FIELDS, 'gallery_name'])
            ->diff([...(new Artwork)->getFillable(), 'id']);

        return collect($fields)->except($exclude)->toArray();
    }
}
