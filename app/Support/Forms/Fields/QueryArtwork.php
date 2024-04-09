<?php

namespace App\Support\Forms\Fields;

use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\Services\Forms\Fields\Traits\HasOnChange;
use App\Support\Forms\Components\QueryArtwork as QueryArtworkComponent;

class QueryArtwork extends BaseFormField
{
    use HasOnChange;

    public static function make(): static
    {
        return new self(
            component: QueryArtworkComponent::class,
            mandatoryProperties: ['name', 'label']
        );
    }

}
