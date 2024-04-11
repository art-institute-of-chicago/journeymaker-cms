<?php

namespace App\Libraries\Api\Builders\Grammar;

use App\Libraries\Api\Builders\ApiQueryBuilder;

class SearchGrammar extends AicGrammar
{
    protected function compileBoost(ApiQueryBuilder $query, bool $boost): array
    {
        return [
            'boost' => $boost,
        ];
    }
}
