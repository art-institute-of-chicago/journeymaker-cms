<?php

namespace App\Libraries\Api\Builders;

use App\Helpers\CollectionHelpers;
use App\Libraries\Api\Builders\Connection\AicConnection;
use App\Libraries\Api\Builders\Grammar\SearchGrammar;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class ApiQueryBuilder
{
    /**
     * The database query grammar instance.
     *
     * @var \Illuminate\Database\Query\Grammars\Grammar
     */
    public $grammar;

    /**
     * The columns that should be returned.
     */
    public array $columns = [];

    /**
     * Search parameters for a raw ES query.
     */
    public array $searchParameters = [];

    /**
     * Completely raw ES query.
     */
    public array $rawQuery = [];

    public function __construct(public AicConnection $connection, $grammar = null)
    {
        $this->connection = $connection;
        $this->grammar = $grammar ?: $connection->getQueryGrammar();
    }

    /**
     * Perform a raw ES search
     */
    public function rawSearch(array $search): static
    {
        $this->searchParameters = array_merge_recursive($this->searchParameters, $search);

        return $this;
    }

    /**
     * Execute a get query and setup pagination data
     */
    public function get(array $columns = [], ?string $endpoint = null): Collection
    {
        $original = $this->columns;

        if ($this->columns === []) {
            $this->columns = $columns;
        }

        $results = $this->runGet($endpoint);

        $this->columns = $original;

        if (is_array($results->body->data)) {
            $collection = CollectionHelpers::collectApi($results->body->data);
        } else {
            // If it's a single element return as a collection with 1 element
            $collection = CollectionHelpers::collectApi([$results->body->data]);
        }

        $collection->setMetadata([
            'pagination' => $results->body->pagination ?? null,
        ]);

        return $collection;
    }

    /**
     * Build and execute against the API connection a GET call
     */
    public function runGet(string $endpoint): stdClass
    {
        $grammar = Str::endsWith($endpoint, '/search')
            ? new SearchGrammar()
            : null;

        return $this->connection->get($endpoint, $this->resolveParameters($grammar));
    }

    /**
     * Use grammar to generate all parameters from the scopes as an array
     */
    public function resolveParameters($grammar = null): array
    {
        return $grammar
            ? $grammar->compileParameters($this)
            : $this->grammar->compileParameters($this);
    }
}
