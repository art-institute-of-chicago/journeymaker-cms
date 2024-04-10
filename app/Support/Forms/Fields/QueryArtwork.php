<?php

namespace App\Support\Forms\Fields;

use A17\Twill\Services\Forms\Fields\BaseFormField;
use App\Support\Forms\Components\QueryArtwork as QueryArtworkComponent;

class QueryArtwork extends BaseFormField
{
    protected array $updateFormFields = [];

    public static function make(): static
    {
        return new self(
            component: QueryArtworkComponent::class,
            mandatoryProperties: ['name']
        );
    }

    /**
     * Fields to update when artwork is selected.
     */
    public function updateFormField(string $formField, string $artworkField, ?string $locale = null): static
    {
        $this->updateFormFields[] = [
            'formField' => $formField,
            'artworkField' => $artworkField,
            'locale' => $locale,
        ];

        return $this;
    }
}
