<?php

namespace App\Libraries\Api\Builders\Relations;

use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Libraries\Api\Models\BaseApiModel;
use Illuminate\Database\Eloquent\Collection;

class HasMany
{
    public function __construct(
        protected ApiQueryBuilder $query,
        protected BaseApiModel $parent,
        protected string $localKey,
        // Limit the number of related models by this amount. -1 means no limit.
        protected int $limit = -1
    ) {
        $this->addConstraints();
    }

    public function addConstraints(): void
    {
        // On this case we just save the Id's array coming from the API
        // And pass it to the query to filter by ID.
        $ids = $this->parent->{$this->localKey};

        // Sometimes it's just an id and not an array
        $ids = is_array($ids) ? $ids : [$ids];

        if ($this->limit > -1) {
            $ids = array_slice($ids, 0, $this->limit);
        }

        $this->query->ids($ids);
    }

    /**
     * Execute eager loading
     */
    public function getEager(): Collection
    {
        return $this->get();
    }

    /**
     * Execute the query
     */
    public function get(array $columns = []): Collection
    {
        return $this->query->get($columns);
    }
}
