<?php

declare(strict_types=1);

return [
    // Content storage path (flatfiles)
    'path' => env('CONTENT_PATH', storage_path('content')),

    // Cache settings
    'cache' => [
        'enabled' => env('CONTENT_CACHE_ENABLED', true),
        'lifetime' => env('CONTENT_CACHE_LIFETIME', 3600), // 1 hour
    ],

    // Markdown settings
    'markdown' => [
        'parser' => 'commonmark',
        'extensions' => [
            'table' => true,
            'strikethrough' => true,
            'autolink' => true,
            'task_lists' => true,
        ],
    ],

    // Yaml front matter settings
    'front_matter' => [
        'required_fields' => ['title', 'slug'],
        'optional_fields' => ['description', 'author', 'date', 'tags', 'image'],
    ],

    // Content types
    'types' => [
        'pages' => [
            'path' => 'pages',
            'extension' => '.md',
            'localized' => true,
        ],
        'posts' => [
            'path' => 'posts',
            'extension' => '.md',
            'localized' => true,
            'date_based' => true, // Organize by year/month
        ],
        'settings' => [
            'path' => 'settings',
            'extension' => '.yaml',
            'localized' => false,
        ],
    ],
];
