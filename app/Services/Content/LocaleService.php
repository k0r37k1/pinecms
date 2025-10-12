<?php

declare(strict_types=1);

namespace App\Services\Content;

class LocaleService
{
    private string $currentLocale;
    private string $fallbackLocale;
    private array $supportedLocales;

    public function __construct()
    {
        $this->currentLocale = env('APP_LOCALE', 'en');
        $this->fallbackLocale = env('APP_FALLBACK_LOCALE', 'en');
        $this->supportedLocales = explode(',', env('APP_SUPPORTED_LOCALES', 'en'));
    }

    public function getCurrentLocale(): string
    {
        return $this->currentLocale;
    }

    public function setCurrentLocale(string $locale): void
    {
        if ($this->isSupported($locale)) {
            $this->currentLocale = $locale;
        }
    }

    public function getFallbackLocale(): string
    {
        return $this->fallbackLocale;
    }

    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    public function isSupported(string $locale): bool
    {
        return in_array($locale, $this->supportedLocales, true);
    }

    /**
     * Get locale from URL path (/de/page, /en/page)
     */
    public function getLocaleFromPath(string $path): ?string
    {
        $segments = explode('/', trim($path, '/'));
        $firstSegment = $segments[0] ?? '';

        if ($this->isSupported($firstSegment)) {
            return $firstSegment;
        }

        return null;
    }

    /**
     * Remove locale prefix from path
     */
    public function stripLocaleFromPath(string $path): string
    {
        $locale = $this->getLocaleFromPath($path);

        if ($locale) {
            return '/' . ltrim(substr($path, strlen($locale) + 1), '/');
        }

        return $path;
    }

    /**
     * Get localized content path
     */
    public function getLocalizedContentPath(string $path, ?string $locale = null): string
    {
        $locale = $locale ?? $this->currentLocale;

        // Try current locale
        $localizedPath = $this->buildContentPath($path, $locale);
        if (file_exists($localizedPath)) {
            return $localizedPath;
        }

        // Try fallback locale
        if ($locale !== $this->fallbackLocale) {
            $fallbackPath = $this->buildContentPath($path, $this->fallbackLocale);
            if (file_exists($fallbackPath)) {
                return $fallbackPath;
            }
        }

        // Return original path
        return $path;
    }

    private function buildContentPath(string $path, string $locale): string
    {
        $contentBase = storage_path('content');
        $pathInfo = pathinfo($path);

        return sprintf(
            '%s/%s/%s/%s.%s',
            $contentBase,
            $pathInfo['dirname'],
            $locale,
            $pathInfo['filename'],
            $pathInfo['extension'] ?? 'md'
        );
    }
}
