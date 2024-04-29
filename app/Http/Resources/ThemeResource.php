<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'intro' => $this->intro,
            'shapeFace' => $this->getImage('shape_face'),
            'icon' => $this->getImage('icon'),
            'guideCoverArt' => $this->getImage('cover'),
            'guideCoverArtHome' => $this->getImage('cover_home'),
            'bgs' => $this->getImages('backgrounds'),
            'prompts' => ThemePromptResource::collection($this->prompts),
            'journey_guide' => $this->journey_guide,
        ];
    }

    private function getImage(string $image): ?array
    {
        $image = $this->imageObject($image)?->toCmsArray();

        return $image
            ? [
                'url' => $image['original'],
                'width' => $image['width'],
                'height' => $image['height'],
            ]
            : null;
    }

    private function getImages(string $image): array
    {
        return $this->imageObjects($image)
            ->map(fn ($image) => $image->toCmsArray())
            ->map(fn ($image) => [
                'url' => $image['original'],
                'width' => $image['width'],
                'height' => $image['height'],
            ])->values()->toArray();
    }
}
