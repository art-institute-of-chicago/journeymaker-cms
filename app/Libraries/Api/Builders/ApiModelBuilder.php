<?php

namespace App\Libraries\Api\Builders;

use App\Helpers\CollectionHelpers;
use App\Libraries\Api\Models\BaseApiModel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiModelBuilder extends Builder
{
    /**
     * The base query builder instance.
     *
     * @var \App\Libraries\Api\Builders\ApiQueryBuilder;
     */
    protected $query;

    /**
     * The model being queried.
     *
     * @var \App\Libraries\Api\Models\BaseApiModel
     */
    protected $model;

    /**
     * The methods that should be returned from query builder.
     *
     * @var array
     */
    protected $passthru = ['runGet'];

    /**
     * Flag to indicate if we are performing a search action.
     * Endpoints are different between listings and search.
     *
     * @var array
     */
    protected $performSearch = false;

    /**
     * Variable to force to use a specific endpoint. Just save the name defined on the model
     *
     * @var string
     */
    protected $customEndpoint;

    /**
     * Applied global scopes.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * Removed global scopes.
     *
     * @var array
     */
    protected $removedScopes = [];

    /**
     * Create a new Eloquent query builder instance.
     *
     * @param  ApiQueryBuilder  $query
     * @return void
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Set a model instance for the model being queried.
     *
     * @param  \App\Libraries\Api\Models\BaseApiModel
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  string  $operator
     * @param  mixed  $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->query->where(...func_get_args());

        return $this;
    }

    /**
     * Perform a search
     *
     * @param  string  $search
     * @return $this
     */
    public function search($search)
    {
        $this->query->search(...func_get_args());
        $this->performSearch = true;

        return $this;
    }

    /**
     * Perform a raw ES search
     *
     * @param  array  $search
     * @return $this
     */
    public function rawSearch($search)
    {
        $this->query->rawSearch(...func_get_args());
        $this->performSearch = true;

        return $this;
    }

    /**
     * Perform a raw ES query
     *
     * @param  array  $params
     * @return $this
     */
    public function rawQuery($params)
    {
        $this->query->rawQuery(...func_get_args());
        $this->performSearch = true;

        return $this;
    }

    /**
     * Add aggregations to the raw ES search
     *
     * @param  array  $aggregations
     * @return $this
     */
    public function aggregations($aggregations)
    {
        $this->query->aggregations(...func_get_args());

        return $this;
    }

    /**
     * When searching filter by specific resources
     *
     * @return $this
     */
    public function resources(array $resources)
    {
        $this->query->resources($resources);

        return $this;
    }

    /**
     * Filter elements by specific ID's
     *
     * @return $this
     */
    public function ids(array $ids)
    {
        $this->query->ids($ids);

        return $this;
    }

    /**
     * Include fields at the results
     *
     * @return $this
     */
    public function include(array $inclusions)
    {
        $this->query->include($inclusions);

        return $this;
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     */
    public function find($id, $columns = [])
    {
        if (is_array($id) || $id instanceof Arrayable) {
            return $this->findMany($id, $columns);
        }

        return $this->findSingle($id, $columns);
    }

    /**
     * Find a model by its primary key, return exception if empty.
     *
     * @param  mixed  $id
     * @param  array  $columns
     */
    public function findOrFail($id, $columns = [])
    {
        $result = $this->find($id, $columns);

        if (isset($result->status) && $result->status == 404) {
            abort(404);
        }

        if (is_array($id)) {
            if (count($result) == count(array_unique($id))) {
                return $result;
            }
        } elseif (! is_null($result)) {
            return $result;
        }

        throw (new ModelNotFoundException())->setModel(
            get_class($this->model),
            $id
        );
    }

    public function findSingle($id, $columns = [])
    {
        $builder = clone $this;

        return $builder->getSingle($id, $columns);
    }

    /**
     * Execute the query and return a collection of results
     *
     * @param  array  $columns
     */
    public function get($columns = [])
    {
        $builder = clone $this;

        $models = $builder->getModels($columns);

        return $builder->getModel()->newCollection($models);
    }

    /**
     * Execute the query and return a raw response
     *
     * @param  array  $columns
     */
    public function getRaw($columns = [])
    {
        return $this->query->getRaw($columns, $this->getEndpoint($this->resolveCollectionEndpoint()))->all();
    }

    /**
     * Execute a query and return the total count from the pagination data
     */
    public function count(): int
    {
        $builder = clone $this;

        return $builder->query->count($this->getEndpoint($this->resolveCollectionEndpoint()));
    }

    /**
     * Get the hydrated models
     *
     * @param  array  $columns
     */
    public function getModels($columns = [])
    {
        $results = $this->query->get($columns, $this->getEndpoint($this->resolveCollectionEndpoint()));

        $models = $this->model->hydrate($results->all());

        // Preserve metadata after hydrating the collection
        return CollectionHelpers::collectApi($models)->setMetadata($results->getMetadata());
    }

    /**
     * Execute the query and return a single element
     */
    public function getSingle($id, array $columns = []): BaseApiModel
    {
        $endpoint = $this->getEndpoint($this->resolveResourceEndpoint(), ['id' => $id]);

        $results = $this->query->get($columns, $endpoint);

        $models = $this->model->hydrate($results->all());

        return collect($models)->first();
    }

    /**
     * Get the model instance being queried.
     *
     * @return string
     */
    public function getEndpoint($name, $params = [])
    {
        return $this->model->parseEndpoint($name, $params);
    }

    /**
     * Force to use a specific endpoint
     *
     * @return string
     */
    public function forceEndpoint($name)
    {
        $this->customEndpoint = $name;

        return $this;
    }

    /**
     * Resolve endpoint. Because search and listing contains different ones
     * We will check if we are calling a search, and use that endpoint in that case
     *
     * @return string
     */
    public function resolveCollectionEndpoint()
    {
        if ($this->customEndpoint) {
            return $this->customEndpoint;
        }

        return $this->performSearch ? 'search' : 'collection';
    }

    /**
     * Resolve single element endpoint
     *
     * @return string
     */
    public function resolveResourceEndpoint()
    {
        return $this->customEndpoint ?? 'resource';
    }

    /**
     * Get the model instance being queried.
     *
     * @return \App\Libraries\Api\Models\BaseApiModel;
     */
    public function getModel()
    {
        return $this->model;
    }

    public function toBase()
    {
        return $this;
    }

    /**
     * Apply the given scope on the current builder instance.
     *
     * @param  array  $parameters
     * @return mixed
     */
    protected function callScope(callable $scope, $parameters = [])
    {
        array_unshift($parameters, $this);

        return $scope(...array_values($parameters)) ?? $this;
    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     */
    public function __call($method, $parameters): mixed
    {
        if (method_exists($this->model, $scope = 'scope'.ucfirst($method))) {
            return $this->callScope([$this->model, $scope], $parameters);
        }

        if (in_array($method, $this->passthru)) {
            return $this->query->{$method}(...$parameters);
        }

        $this->query->{$method}(...$parameters);

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     */
    public function __get($key): mixed
    {
        return $this->query->{$key};
    }
}
