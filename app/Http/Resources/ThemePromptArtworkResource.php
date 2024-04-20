<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemePromptArtworkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $override = $this->artwork->imageObject('override')?->toCmsArray();

        if ($override) {
            // Cms image
        } else {
            /// API image
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            // 'img' => [
            //     'url' => $this->img,
            //     'width' => $this->img_width,
            //     'height' => $this->img_height,
            // ],
            // 'artwork_thumbnail' => $this->artwork_thumbnail,
            // 'img_medium' => [
            //     'url' => $this->img_medium,
            //     'width' => $this->img_medium_width,
            //     'height' => $this->img_medium_height,
            // ],
            // 'img_large' => [
            //     'url' => $this->img_large,
            //     'width' => $this->img_large_width,
            //     'height' => $this->img_large_height,
            // ],
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
}
