<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class CollectionHelpers
{
    public static function collectApi(mixed $value = null): Collection
    {
        return new class($value) extends Collection
        {
            protected ?Collection $metadata = null;

            public function getMetadata(mixed $name = null): mixed
            {
                return $this->metadata instanceof Collection
                    ? $this->metadata->get($name, $this->metadata)
                    : null;
            }

            public function setMetadata(array|Collection $data): static
            {
                $data = collect($data);

                $this->metadata = $this->metadata instanceof Collection
                    ? $this->metadata->merge($data)
                    : $data;

                return $this;
            }
        };
    }
}
