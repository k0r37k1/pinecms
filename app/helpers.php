<?php

declare(strict_types=1);

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     */
    function app(?string $abstract = null, array $parameters = []): mixed
    {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }

        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     */
    function config(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return app('config');
        }

        return app('config')->get($key, $default);
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     */
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     */
    function base_path(string $path = ''): string
    {
        return app()->basePath($path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     */
    function storage_path(string $path = ''): string
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('content_path')) {
    /**
     * Get the path to the content folder (flatfiles).
     */
    function content_path(string $path = ''): string
    {
        $base = config('content.path', base_path('storage/content'));

        return $path !== '' ? $base . DIRECTORY_SEPARATOR . $path : $base;
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     */
    function public_path(string $path = ''): string
    {
        $base = base_path('public');

        return $path !== '' ? $base . DIRECTORY_SEPARATOR . $path : $base;
    }
}

if (!function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     */
    function resource_path(string $path = ''): string
    {
        $base = base_path('resources');

        return $path !== '' ? $base . DIRECTORY_SEPARATOR . $path : $base;
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     */
    function trans(?string $key = null, array $replace = [], ?string $locale = null): mixed
    {
        if (is_null($key)) {
            return app('translator');
        }

        return app('translator')->get($key, $replace, $locale);
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message (alias).
     */
    function __(string $key, array $replace = [], ?string $locale = null): string
    {
        return trans($key, $replace, $locale);
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     */
    function trans_choice(string $key, int|array $number, array $replace = [], ?string $locale = null): string
    {
        return app('translator')->choice($key, $number, $replace, $locale);
    }
}

if (!function_exists('app_locale')) {
    /**
     * Get or set the current application locale.
     */
    function app_locale(?string $locale = null): string
    {
        if (is_null($locale)) {
            return app('translator')->getLocale();
        }

        app('translator')->setLocale($locale);

        return $locale;
    }
}

if (!function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     */
    function view(?string $view = null, array $data = [], array $mergeData = []): mixed
    {
        $factory = app('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}

if (!function_exists('route')) {
    /**
     * Generate a URL to a named route.
     */
    function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return app('url')->route($name, $parameters, $absolute);
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     */
    function url(?string $path = null, array $parameters = [], ?bool $secure = null): string
    {
        if (is_null($path)) {
            return app('url');
        }

        return app('url')->to($path, $parameters, $secure);
    }
}
