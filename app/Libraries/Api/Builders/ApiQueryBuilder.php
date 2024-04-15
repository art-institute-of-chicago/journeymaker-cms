<?php

namespace App\Libraries\Api\Builders;

use App\Helpers\CollectionHelpers;
use App\Libraries\Api\Builders\Connection\AicConnection;
use App\Libraries\Api\Builders\Grammar\SearchGrammar;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class ApiQueryBuilder
{
    /**
     * The orderings for the query.
     */
    public ?array $orders = null;

    /**
     * The maximum number of records to return.
     */
    public ?int $limit = null;

    /**
     * The number of records to skip.
     */
    public ?int $offset = null;

    /**
     * Whether to apply boosting or not
     */
    public bool $boost = true;

    /**
     * The current page number
     */
    public ?int $page;

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
     * The ids of the records that should be returned.
     */
    public array $ids = [];

    /**
     * The list of extra fields to be included
     */
    public array $include = [];

    /**
     * The where constraints for the query.
     */
    public array $wheres = [];

    /**
     * Search constraints for the query.
     */
    public string $searchText = '';

    /**
     * Search parameters for a raw ES query.
     */
    public array $searchParameters = [];

    /**
     * Completely raw ES query.
     */
    public array $rawQuery = [];

    /**
     * Aggregations parameters for a raw ES query.
     */
    public array $aggregationParameters = [];

    /**
     * Search specific resources. Useful only for general searches
     */
    public array $searchResources = [];

    /**
     * Pagination data saved after a request
     */
    public $paginationData;

    public function __construct(public AicConnection $connection, $grammar = null)
    {
        $this->connection = $connection;
        $this->grammar = $grammar ?: $connection->getQueryGrammar();
    }

    /**
     * Add an "order by" clause to the query
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orders[] = [
            $column => ['order' => strtolower($direction) === 'asc' ? 'asc' : 'desc'],
        ];

        return $this;
    }

    /**
     * Add an "ids" clause to the query. This will bring only records with these ids
     */
    public function ids(array $ids = []): static
    {
        if ($ids !== []) {
            $this->ids = $ids;
        }

        return $this;
    }

    /**
     * Add an "includes" clause to the query. This will add those attributes
     */
    public function include(array $inclusions = []): static
    {
        if ($inclusions !== []) {
            $this->include = $inclusions;
        }

        return $this;
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = [], $pageName = 'page', $page = null)
    {
        ray('paginate');
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $results = $this->forPage($page, $perPage)->get($columns);

        $paginationData = $this->getPaginationData();
        $total = $paginationData ? $paginationData->total : $results->count();

        $data = $results['body']->data;

        return $this->paginator($data, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            $options
        );
    }

    /**
     * Set the limit and offset for a given page.
     *
     * @param  int  $page
     * @param  int  $perPage
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function forPage($page, $perPage = 15)
    {
        $this->page = $page;

        return $this->skip(($page - 1) * $perPage)->take($perPage);
    }

    /**
     * Alias to set the "offset" value of the query.
     */
    public function skip(int $value): Builder|static
    {
        return $this->offset($value);
    }

    /**
     * Set the "offset" value of the query.
     */
    public function offset(int $value): static
    {
        $this->offset = max(0, $value);

        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     */
    public function take(int $value): Builder|static
    {
        return $this->limit($value);
    }

    /**
     * Set the "limit" value of the query.
     */
    public function limit(int $value): static
    {
        if ($value >= 0) {
            $this->limit = $value;
        }

        return $this;
    }

    /**
     * Set the "boost" value of the query.
     */
    public function boost(bool $value = true): static
    {
        $this->boost = $value;

        return $this;
    }

    /**
     * Search for specific resources
     */
    public function resources($resources): static
    {
        $this->searchResources = $resources;

        return $this;
    }

    /**
     * Perform a search
     */
    public function search(string $search): static
    {
        $this->searchText = $search === '' || $search === '0' ? null : $search;

        return $this;
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
     * Perform a completely raw ES query
     */
    public function rawQuery(array $search): static
    {
        $this->rawQuery = $search;

        return $this;
    }

    /**
     * Add aggregations to the raw ES search
     */
    public function aggregations(array $aggregations): static
    {
        $this->aggregationParameters = array_merge_recursive($this->aggregationParameters, $aggregations);

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
            // If it's a single element return as a collection with 1 element
            $collection = CollectionHelpers::collectApi($results->body->data);
        } else {
            $collection = CollectionHelpers::collectApi([$results->body->data]);
        }

        $collection = $this->getSortedCollection($collection);

        $collection->setMetadata([
            'pagination' => $results->body->pagination ?? null,
            'aggregations' => $results->body->aggregations ?? null,
            'suggestions' => $results->body->suggest ?? null,
        ]);

        return $collection;
    }

    /**
     * Execute a get query and return a raw response
     */
    public function getRaw(array $columns = [], ?string $endpoint = null): Collection
    {
        $original = $this->columns;

        if (is_null($original)) {
            $this->columns = $columns;
        }

        $results = $this->runGet($endpoint);

        if (is_array($results->body)) {
            $collection = CollectionHelpers::collectApi($results->body);
        } else {
            $collection = CollectionHelpers::collectApi([$results->body]);
        }

        $collection = $this->getSortedCollection($collection);

        $collection->setMetadata([
            'pagination' => $results->body->pagination ?? null,
            'aggregations' => $results->body->aggregations ?? null,
            'suggestions' => $results->body->suggest ?? null,
        ]);

        return $collection;
    }

    /**
     * Execute a GET query and return the total number of results noted in the
     * pagination data.
     */
    public function count(?string $endpoint = null): int
    {
        return $this->limit(0)->get([], $endpoint)->getMetadata('pagination')->total;
    }

    public function getPaginationData()
    {
        return $this->paginationData;
    }

    /**
     * Build and execute against the API connection a GET call
     */
    public function runGet(string $endpoint): stdClass
    {
        $grammar = null;

        if (Str::endsWith($endpoint, '/search')) {
            $grammar = new SearchGrammar();
        }

        return $this->connection->get($endpoint, $this->resolveParameters($grammar));
    }

    /**
     * Use grammar to generate all parameters from the scopes as an array
     */
    public function resolveParameters($grammar = null): array
    {
        if ($grammar) {
            return $grammar->compileParameters($this);
        }

        return $this->grammar->compileParameters($this);
    }

    /**
     * WEB-1626: If this was an `ids` query, reorder results to match `ids`.
     */
    private function getSortedCollection(Collection $collection): Collection
    {
        if ($this->ids === []) {
            return $collection;
        }

        return $collection->sort(function ($a, $b) {
            if (! isset($a->id) || ! isset($b->id)) {
                return 0;
            }

            $ia = array_search($a->id, $this->ids);
            $ib = array_search($b->id, $this->ids);

            return $ia <=> $ib;
        });
    }
}
