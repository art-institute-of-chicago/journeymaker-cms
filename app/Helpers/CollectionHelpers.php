<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class CollectionHelpers
{
    /**
     * Create a collection from the given value.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function collectApi(mixed $value = null)
    {
        return new class($value) extends Collection
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
        };
    }
}
