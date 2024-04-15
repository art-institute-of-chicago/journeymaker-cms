<?php

namespace App\Helpers;

class CollectionHelpers
{
    /**
     * Create a collection from the given value.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function collectApi(mixed $value = null)
    {
        return new \App\Libraries\Api\Models\ApiCollection($value);
    }
}
