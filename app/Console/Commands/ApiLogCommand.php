<?php

namespace App\Console\Commands;

use App\Models\ApiLog;
use App\Models\Artwork;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ApiLogCommand extends Command
{
    protected $signature = 'app:api-log';

    protected $description = 'Track changes to API values.';

    public function handle()
    {
        Artwork::cacheArtworkApiData();

        $currentValues = ApiLog::getCurrentValues();

        $this->withProgressBar(Artwork::all(), fn (Artwork $artwork) => $this->getEndpointsForArtwork($artwork)
            ->each(fn ($endpoint) => collect($endpoint['fields'])
                ->reject(fn ($field) => $this->ignoring($field))
                ->reject(fn ($field) => $this->isCurrentValue($artwork, $field, $endpoint['data'], $currentValues))
                ->each(fn ($field) => $this->log($artwork, $field, $endpoint['data']))
            )
        );
    }

    private function getEndpointsForArtwork(Artwork $artwork): Collection
    {
        $artworkApiData = $artwork->getArtworkApiData();
        $galleryApiData = $artwork->getGalleryApiData($artworkApiData->gallery_id);

        return collect([
            [
                'data' => $artworkApiData,
                'fields' => Artwork::ARTWORK_API_FIELDS,
            ],
            [
                'data' => $galleryApiData,
                'fields' => Artwork::GALLERY_API_FIELDS,
            ],
        ]);
    }

    private function ignoring(string $field): bool
    {
        return in_array($field, ['id', 'main_reference_number', 'image_id', 'thumbnail']);
    }

    private function isCurrentValue(Artwork $artwork, string $field, object $data, Collection $currentValues): bool
    {
        return $currentValues->has(md5($artwork->datahub_id.$field.$data->$field));
    }

    private function log(Artwork $artwork, string $field, object $data)
    {
        tap($artwork->apiLog()->firstOrCreate(
            ['hash' => md5($artwork->datahub_id.$field.$data->$field)],
            ['field' => $field, 'value' => $data->$field],
        ), fn ($log) => $log->wasRecentlyCreated ? null : $log->touch());
    }
}
