<?php

return [
    'dashboard' => [
        'modules' => [
            'theme' => [
                'name' => 'themes',
                'count' => true,
                'create' => true,
                'activity' => true,
                'draft' => true,
                'search' => true,
                'search_fields' => ['title', 'intro'],
            ],
        ],
    ],
    'enabled' => [
        'users-management' => true,
        'media-library' => true,
        'file-library' => false,
        'block-editor' => false,
        'buckets' => true,
        'users-image' => false,
        'settings' => true,
        'dashboard' => true,
        'search' => true,
        'users-description' => false,
        'activitylog' => true,
        'users-2fa' => false,
        'users-oauth' => false,
        'permissions-management' => false,
    ],
];
