<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default API Base URL
    |--------------------------------------------------------------------------
    |
    | It will be used by our API consumer to augment our Eloquent models with
    | data coming from their sources.
    |
    */

    'base_uri' => env('API_BASE_URI', 'https://api-test.artic.edu'),

    /*
    |--------------------------------------------------------------------------
    | Public API Base URL
    |--------------------------------------------------------------------------
    |
    | Used on the frontend to support collection autosuggest.
    |
    */

    'public_uri' => env('API_PUBLIC_URI', 'https://api-test.artic.edu'),

    'token' => env('API_TOKEN', null),
];
