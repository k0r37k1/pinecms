<?php

declare(strict_types=1);

$providers = [
    App\Providers\AppServiceProvider::class,
    Laravel\Boost\BoostServiceProvider::class,
    Laravel\Mcp\McpServiceProvider::class,
    Sentry\Laravel\ServiceProvider::class,
];

// Only register Telescope in non-testing environments
// Multiple detection methods to ensure Telescope is never loaded during tests:
// 1. Check for PHPUNIT_VERSION constant (defined by PHPUnit)
// 2. Check for phpunit/artisan test in command line arguments
// 3. Check APP_ENV in both $_ENV and $_SERVER
$isRunningTests = defined('PHPUNIT_VERSION')
    || (isset($_SERVER['argv']) && (
        in_array('--filter', $_SERVER['argv'], true)
        || in_array('test', $_SERVER['argv'], true)
        || str_contains(implode(' ', $_SERVER['argv']), 'phpunit')
    ))
    || (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'testing')
    || (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'testing');

if (! $isRunningTests) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
