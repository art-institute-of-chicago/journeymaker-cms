<?php

namespace App\Libraries\Api\Builders\Grammar;

use App\Libraries\Api\Builders\ApiQueryBuilder;

class AicGrammar
{
    protected array $selectComponents = [
        'wheres',
        'limit',
        'offset',
        'boost',
        'orders',
        'ids',
        'columns',
        'include',
        'searchText',
        'searchParameters',
        'searchResources',
        'aggregationParameters',
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
                $method = 'compile'.ucfirst($component);
                $parameters = array_merge($parameters, $this->{$method}($query->{$component}));
            }
        }

        return $parameters;
    }

    protected function compileWheres(): array
    {
        return [];
    }

    protected function compileColumns(array $columns): array
    {
        return empty($columns) ? [] : ['fields' => implode(',', $columns)];
    }

    protected function compileInclude(array $columns): array
    {
        return empty($columns) ? [] : ['include' => implode(',', $columns)];
    }

    protected function compileIds(array $ids): array
    {
        return empty($ids) ? [] : ['ids' => implode(',', $ids)];
    }

    protected function compileSearchResources(array $resources): array
    {
        return empty($resources) ? [] : ['resources' => implode(',', $resources)];
    }

    protected function compileSearchText(string $text): array
    {
        if ($text) {
            return ['q' => $text];
        }

        return [];
    }

    protected function compileSearchParameters(array $elasticParameters): array
    {
        return empty($elasticParameters) ? [] : ['query' => $elasticParameters];
    }

    protected function compileRawQuery(array $rawQuery): array
    {
        return empty($rawQuery) ? [] : $rawQuery;
    }

    protected function compileAggregationParameters(array $aggregations): array
    {
        return empty($aggregations) ? [] : ['aggregations' => $aggregations];
    }

    protected function compileOrders(string $order): array
    {
        return empty($order) ? [] : ['sort' => $order];
    }

    protected function compileLimit(int $limit): array
    {
        return [
            'limit' => $limit,
            'size' => $limit, // Elasticsearch search parameter for limiting
        ];
    }

    protected function compileOffset(int $offset): array
    {
        return [
            'offset' => $offset,
            'from' => $offset, // Elasticsearch search parameter for offset
        ];
    }

    protected function compileBoost(bool $boost): array
    {
        return [];
    }
}
