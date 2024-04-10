<?php

namespace App\Support\Forms\Components;

use A17\Twill\View\Components\Fields\TwillFormComponent;
use Illuminate\Contracts\View\View;

class QueryArtwork extends TwillFormComponent
{
    public function __construct(
        string $name,
        string $label,
        bool $renderForBlocks = false,
        bool $renderForModal = false,
        bool $translated = false,
        bool $required = false,
        ?string $note = '',
        mixed $default = null,
        bool $disabled = false,
        bool $readOnly = false,
        bool $inModal = false,
        // Component specific
        public ?string $placeholder = '',
        public ?array $updateFormFields = [],
    ) {
        parent::__construct(
            name: $name,
            label: $label,
            note: $note,
            inModal: $inModal,
            readOnly: $readOnly,
            renderForBlocks: $renderForBlocks,
            renderForModal: $renderForModal,
            disabled: $disabled,
            required: $required,
            translated: $translated,
            default: $default
        );
    }

    public function render(): View
    {
        return view('forms.query-artwork', $this->data());
    }
}
