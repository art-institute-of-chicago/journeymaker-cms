<?php

namespace App\Http\Resources;

use Facades\App\Libraries\DamsImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemePromptArtworkResource extends JsonResource
{
    public const IMAGE_SIZES = [
        'img' => 3000,
        'small' => 200,
        'medium' => 843,
        'large' => 1686,
    ];

    public function toArray(Request $request): array
    {
        $override = $this->artwork->imageObject('override')?->toCmsArray();

        $images = $override
            ? $this->getOverrideImages($override)
            : $this->getApiImages($this->artwork->image_id);

        return [
            'id' => $this->id,
            'title' => $this->artwork->title,
            ...$images,
            'artist' => $this->artwork->artist_display,
            'year' => $this->artwork->date_display,
            'medium' => $this->artwork->medium_display,
            'credit' => $this->artwork->credit_line,
            'galleryId' => $this->artwork->gallery_id,
            'galleryName' => $this->artwork->gallery_name,
            'closerLook' => null,
            'detailNarrative' => $this->detail_narrative,
            'viewingDescription' => $this->viewing_description,
            'activityTemplate' => $this->activity_template,
            'activityInstructions' => $this->activity_instructions,
            'locationDirections' => $this->artwork->location_directions,
            'mapX' => $this->artwork->latitude,
            'mapY' => $this->artwork->longitude,
            'floor' => $this->artwork->floor,
        ];
    }

    private function getOverrideImages(array $image): array
    {
        return [
            'img' => [
                'url' => $image['original'],
                'width' => $image['width'],
                'height' => $image['height'],
            ],
            'artwork_thumbnail' => [
                'url' => $image['original'].'?fm=jpg&q=60&fit=max&dpr=1&w='.static::IMAGE_SIZES['small'],
                ...$this->getDimensions(
                    $image['width'],
                    $image['height'],
                    static::IMAGE_SIZES['small']
                ),
            ],
            'img_medium' => [
                'url' => $image['original'].'?fm=jpg&q=80&fit=max&dpr=1&w=843',
                ...$this->getDimensions(
                    $image['width'],
                    $image['height'],
                    static::IMAGE_SIZES['medium']
                ),
            ],
            'img_large' => [
                'url' => $image['original'].'?fm=jpg&q=100&fit=max&dpr=1&w=843',
                ...$this->getDimensions(
                    $image['width'],
                    $image['height'],
                    static::IMAGE_SIZES['large']
                ),
            ],
        ];
    }

    private function getApiImages(?string $id): array
    {
        if (! $id) {
            return [];
        }

        $dimensions = DamsImageService::getDimensions($id);

        return collect([
            'img' => static::IMAGE_SIZES['img'],
            'artwork_thumbnail' => static::IMAGE_SIZES['small'],
            'img_medium' => static::IMAGE_SIZES['medium'],
            'img_large' => static::IMAGE_SIZES['large'],
        ])->map(fn ($size) => [
            'url' => $this->getApiImageUrl($id, $size),
            ...$this->getDimensions(
                $dimensions['width'],
                $dimensions['height'],
                $size
            ),
        ])->toArray();
    }

    private function getApiImageUrl(string $id, string|int $width): string
    {
        return "https://www.artic.edu/iiif/2/{$id}/full/{$width},/0/default.jpg";
    }

    private function getDimensions(int $width, int $height, int $newWidth): array
    {
        if ($width === 0 || $height === 0) {
            return ['width' => 0, 'height' => 0];
        }

        $aspectRatio = $width / $height;

        $width = $newWidth;
        $height = round($newWidth / $aspectRatio);

        // If the height is still greater than the max height
        // Set the height to the max height
        if ($height > static::IMAGE_SIZES['img']) {
            $height = static::IMAGE_SIZES['img'];
            $width = round(static::IMAGE_SIZES['img'] * $aspectRatio);
        }

        return ['width' => $width, 'height' => $height];
    }
}
