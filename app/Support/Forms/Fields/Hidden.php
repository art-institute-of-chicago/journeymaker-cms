<?php

namespace App\Support\Forms\Fields;

use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\View\Components\Fields\Hidden as HiddenComponent;

class Hidden extends BaseFormField
{
    public static function make(): static
    {
        return new self(
            component: HiddenComponent::class,
            mandatoryProperties: ['name']
        );
    }
}
