<?php

namespace Tests\Mocks;

use App\Helpers\CollectionHelpers;
use App\Libraries\Api\Builders\ApiQueryBuilder as BaseApiQueryBuilder;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ApiQueryBuilder extends BaseApiQueryBuilder
{
    /**
     * Provide a closure to generate the mocked response based on the endpoint
     */
    public function __construct(private readonly Closure $getResponse)
    {
        //
    }

    /**
     * Override the get method to return the mocked data
     * Use the closure to generate the mocked response based on the endpoint
     */
    public function get(array $columns = [], ?string $endpoint = null): Collection
    {
        $data = is_callable($this->getResponse)
            ? call_user_func($this->getResponse, $endpoint)
            : null;

        if (! $data) {
            return parent::get($columns, $endpoint);
        }

        $collection = CollectionHelpers::collectApi(Arr::wrap($data));

        $collection->setMetadata([
            'pagination' => null,
        ]);

        return $collection;
    }
}
