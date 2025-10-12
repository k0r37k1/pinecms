<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'PineCMS'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'key' => env('APP_KEY'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    // Localization
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'supported_locales' => explode(',', env('APP_SUPPORTED_LOCALES', 'en,de')),

    // Service Providers
    'providers' => [
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],
];
