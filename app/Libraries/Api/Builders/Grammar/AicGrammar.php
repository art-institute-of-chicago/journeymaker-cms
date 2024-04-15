<?php

namespace App\Libraries\Api\Builders\Grammar;

use App\Libraries\Api\Builders\ApiQueryBuilder;

class AicGrammar
{
    protected array $selectComponents = [
        'columns',
        'searchParameters',
        'rawQuery',
    ];

    /**
     * Compile all components into API parameters.
     */
    public function compileParameters(ApiQueryBuilder $query): array
    {
        $original = $query->columns;

        // To compile the query, we'll spin through each component of the query and
        // see if that component exists. If it does we'll just call the compiler
        // function for the component which is responsible for making the parameters
        $compiled = $this->compileComponents($query);

        $query->columns = $original;

        return $compiled;
    }

    /**
     * Compile the components necessary for a select clause.
     */
    protected function compileComponents(ApiQueryBuilder $query): array
    {
        $parameters = [];

        foreach ($this->selectComponents as $component) {
            // To compile the query, we'll spin through each component of the query and
            // see if that component exists. If it does we'll just call the compiler
            // function for the component which is responsible for making the parameter/s.
            if (! is_null($query->{$component})) {
                $method = 'compile'.ucfirst((string) $component);
                $parameters = array_merge($parameters, $this->{$method}($query->{$component}));
            }
        }

        return $parameters;
    }

    protected function compileColumns(array $columns): array
    {
        return $columns === [] ? [] : ['fields' => implode(',', $columns)];
    }

    protected function compileSearchParameters(array $elasticParameters): array
    {
        return $elasticParameters === [] ? [] : ['query' => $elasticParameters];
    }

    protected function compileRawQuery(array $rawQuery): array
    {
        return [];
    }
}
