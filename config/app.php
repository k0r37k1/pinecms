<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'PineCMS'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    // Localization
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
    'supported_locales' => explode(',', env('APP_SUPPORTED_LOCALES', 'en,de')),

    // Maintenance Mode
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];
