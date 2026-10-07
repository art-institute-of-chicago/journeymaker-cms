<?php

namespace App\Http\Resources;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemePromptArtworkResource extends JsonResource
{
    public const ALLOWED_IMAGE_DIMS = [200,400,600,843,1200,1686];

    public const IMAGE_SIZES = [
        'img' => 1686,
        'small' => 1686,
        'medium' => 1686,
        'large' => 1686,
    ];

    public function toArray(Request $request): array
    {
        $override = $this->artwork->imageObject('override')?->toCmsArray();

        $images = $override
            ? $this->getOverrideImages($override)
            : $this->getApiImages($this->artwork);

        return [
            'id' => $this->id,
            'title' => $this->artwork->title,
            'img' => $images['img'] ?? null,
            'artwork_thumbnail' => $images['artwork_thumbnail'] ?? null,
            'img_medium' => $images['img_medium'] ?? null,
            'img_large' => $images['img_large'] ?? null,
            'artist' => $this->artwork->artist,
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
                'url' => $image['original'].'?fm=jpg&q=80&fit=max&dpr=1&w='.static::IMAGE_SIZES['medium'],
                ...$this->getDimensions(
                    $image['width'],
                    $image['height'],
                    static::IMAGE_SIZES['medium']
                ),
            ],
            'img_large' => [
                'url' => $image['original'].'?fm=jpg&q=100&fit=max&dpr=1&w='.static::IMAGE_SIZES['large'],
                ...$this->getDimensions(
                    $image['width'],
                    $image['height'],
                    static::IMAGE_SIZES['large']
                ),
            ],
        ];
    }

    private function getApiImages(Artwork $artwork): array
    {
        if (! $artwork->thumbnail) {
            return [
                'img' => null,
                'artwork_thumbnail' => null,
                'img_medium' => null,
                'img_large' => null,
            ];
        }

        return collect([
            'img' => static::IMAGE_SIZES['img'],
            'artwork_thumbnail' => static::IMAGE_SIZES['small'],
            'img_medium' => static::IMAGE_SIZES['medium'],
            'img_large' => static::IMAGE_SIZES['large'],
        ])->map(function ($size) use ($artwork) {
            $dimensions = $this->getDimensions(
                $artwork->thumbnail->width,
                $artwork->thumbnail->height,
                $size
            );

            return [
                'url' => $this->getApiImageUrl(
                    $artwork->image_id,
                    $dimensions['width'],
                    $dimensions['height']
                ),
                'width' => $dimensions['width'],
                'height' => $dimensions['height'],
            ];
        })->toArray();
    }

    private function getApiImageUrl(string $id, string|int $width, string|int $height): string
    {
        return config('journeymaker.image_base_uri') . "/iiif/2/{$id}/full/{$width},{$height}/0/default.jpg";
    }

    private function getDimensions(int $width, int $height, int $newSize): array
    {
        if ($width === 0 || $height === 0) {
            return ['width' => 0, 'height' => 0];
        }

        // Size the longest side, without upscaling past the requested size
        if ($width >= $height) {
            return ['width' => $this->getAllowedDimension(min($width, $newSize)), 'height' => ''];
        }

        return ['width' => '', 'height' => $this->getAllowedDimension(min($height, $newSize))];
    }

    /**
     * Returns the smallest allowed dimension that is not less than the given size,
     * or the largest allowed dimension if the size exceeds them all.
     */
    private function getAllowedDimension(int $size): int
    {
        foreach (static::ALLOWED_IMAGE_DIMS as $allowed) {
            if ($allowed >= $size) {
                return $allowed;
            }
        }

        return max(static::ALLOWED_IMAGE_DIMS);
    }
}
