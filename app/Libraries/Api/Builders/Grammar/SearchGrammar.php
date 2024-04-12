<?php

namespace App\Libraries\Api\Builders\Grammar;

class SearchGrammar extends AicGrammar
{
    protected function compileBoost(bool $boost): array
    {
        return [
            'boost' => $boost,
        ];
    }
}
