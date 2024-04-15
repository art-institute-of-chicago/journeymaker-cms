<?php

/**
 * Extended Laravel Collection to include metadata.
 * This way we can just save information from search results
 * such as aggregations, pagination, suggestions, etc.
 */

namespace App\Libraries\Api\Models;

use Illuminate\Support\Collection;

class ApiCollection extends Collection
{
    protected ?Collection $metadata = null;

    public function getMetadata(mixed $name = null): mixed
    {
        if (! $this->metadata instanceof Collection) {
            return null;
        }

        if ($name) {
            return $this->metadata->get($name);
        }

        return $this->metadata;
    }

    public function setMetadata(array|Collection $data): static
    {
        $data = $data instanceof Collection
            ? $data
            : collect($data);

        $this->metadata = $this->metadata instanceof Collection
            ? $this->metadata->merge($data)
            : $data;

        return $this;
    }
}
