<?php

namespace App\Libraries\Api\Filters;

use A17\Twill\Services\Listings\Filters\FreeTextSearch;
use Illuminate\Database\Eloquent\Builder;

/**
 * This filter is used in the Twill admin base api controllers to search the API.
 */
class Search extends FreeTextSearch
{
    public function applyFilter(Builder $builder): Builder
    {
        if (! empty($this->searchString) && $this->searchColumns !== []) {
            $shoulds = [];
            foreach ($this->searchColumns as $col) {
                if ($col != 'id' || is_numeric($this->searchString)) {
                    $shoulds[] = [
                        'match' => [
                            $col => $this->searchString,
                        ],
                    ];
                }
            }
            $params = [
                'bool' => [
                    'should' => $shoulds,
                    'minimum_should_match' => 1,
                ],
            ];

            // \App\Libraries\Api\Models\BaseApiModel with \App\Libraries\Api\Models\Behaviors\HasApiCalls::rawSearch()
            return $builder->getModel()->rawSearch($params);
        }

        return $builder;
    }
}