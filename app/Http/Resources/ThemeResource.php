<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $icon = $this->imageObject('icon')->toCmsArray();
        $cover = $this->imageObject('cover')->toCmsArray();
        $cover_home = $this->imageObject('cover_home')->toCmsArray();
        $backgrounds = $this->imageObjects('backgrounds')
            ->map(fn ($image) => $image->toCmsArray())
            ->map(fn ($image) => [
                'url' => $image['original'],
                'width' => $image['width'],
                'height' => $image['height'],
            ])->values();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'intro' => $this->intro,
            'shapeFace' => $this->shapeFace,
            'icon' => [
                'url' => $icon['original'],
                'width' => $icon['width'],
                'height' => $icon['height'],
            ],
            'guideCoverArt' => [
                'url' => $cover['original'],
                'width' => $cover['width'],
                'height' => $cover['height'],
            ],
            'guideCoverArtHome' => [
                'url' => $cover_home['original'],
                'width' => $cover_home['width'],
                'height' => $cover_home['height'],
            ],
            'bgs' => $backgrounds,
            'prompts' => ThemePromptResource::collection($this->prompts),
        ];
    }
}
