<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Laravel\Boost\BoostServiceProvider::class,
    Laravel\Mcp\McpServiceProvider::class,
    Sentry\Laravel\ServiceProvider::class,
];
