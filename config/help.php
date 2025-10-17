<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Help Center Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Help Center system
    | including FAQ, Support, and Guide modules.
    |
    */

    // Enable/disable the help system
    'enable' => true,

    // Default view layout
    'layout' => 'jiny-admin::layouts.app',

    // Help modules configuration
    'modules' => [
        'help' => [
            'enable' => true,
            'route_prefix' => 'help',
        ],
        'faq' => [
            'enable' => true,
            'route_prefix' => 'faq',
        ],
        'support' => [
            'enable' => true,
            'route_prefix' => 'help/support',
        ],
        'guide' => [
            'enable' => true,
            'route_prefix' => 'help/guide',
        ],
    ],

    // Admin configuration
    'admin' => [
        'prefix' => 'admin/cms',
        'middleware' => ['web', 'admin'],
    ],

    // Pagination
    'pagination' => [
        'per_page' => 15,
    ],

    // File upload configuration
    'uploads' => [
        'max_size' => 5120, // KB
        'allowed_types' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'],
        'path' => 'uploads/help',
    ],
];