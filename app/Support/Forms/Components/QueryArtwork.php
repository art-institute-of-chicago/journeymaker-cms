<?php

namespace App\Support\Forms\Components;

use A17\Twill\View\Components\Fields\TwillFormComponent;
use Illuminate\Contracts\View\View;

class QueryArtwork extends TwillFormComponent
{
    public function render(): View
    {
        return view('forms.query-artwork', $this->data());
    }
}
