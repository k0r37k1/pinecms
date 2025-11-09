<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Environment file generator for PineCMS installation.
 *
 * Generates .env file with secure defaults and user-provided configuration.
 */
class EnvironmentGenerator
{
    /**
     * Generate environment file with the provided configuration.
     *
     * @param  array{app_name?: string, app_url?: string, admin_email?: string, db_name?: string, timezone?: string}  $config
     * @return array{success: bool, path: string, message: string}
     */
    public function generate(array $config): array
    {
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            return [
                'success' => false,
                'path' => $envPath,
                'message' => 'Environment file already exists',
            ];
        }

        $content = $this->buildEnvironmentContent($config);

        try {
            $this->writeAtomic($envPath, $content);
        } catch (RuntimeException $e) {
            return [
                'success' => false,
                'path' => $envPath,
                'message' => 'Failed to write environment file: ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'path' => $envPath,
            'message' => 'Environment file created successfully',
        ];
    }

    /**
     * Validate configuration array.
     *
     * @param  array{app_name?: string, app_url?: string, admin_email?: string}  $config
     * @return array{valid: bool, errors: array<string, string>}
     */
    public function validateConfig(array $config): array
    {
        $errors = [];

        if (! isset($config['app_name']) || trim($config['app_name']) === '') {
            $errors['app_name'] = 'App name is required';
        }

        if (! isset($config['app_url']) || trim($config['app_url']) === '') {
            $errors['app_url'] = 'App URL is required';
        } elseif (filter_var($config['app_url'], FILTER_VALIDATE_URL) === false) {
            $errors['app_url'] = 'App URL must be a valid URL';
        }

        if (isset($config['admin_email']) && trim($config['admin_email']) !== '' && filter_var($config['admin_email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['admin_email'] = 'Admin email must be a valid email address';
        }

        return [
            'valid' => $errors === [],
            'errors' => $errors,
        ];
    }

    /**
     * Generate a secure application key.
     */
    private function generateAppKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }

    /**
     * Build environment file content from configuration.
     *
     * @param  array{app_name?: string, app_url?: string, admin_email?: string, db_name?: string, timezone?: string}  $config
     */
    private function buildEnvironmentContent(array $config): string
    {
        $appName = $config['app_name'] ?? 'PineCMS';
        $appUrl = $config['app_url'] ?? 'http://localhost';
        $timezone = $config['timezone'] ?? 'UTC';
        $dbName = $config['db_name'] ?? 'pinecms_' . Str::random(8);
        $adminEmail = $config['admin_email'] ?? null;

        $appKey = $this->generateAppKey();
        $dbPath = $this->getDatabasePath($dbName);
        $mailFromAddress = $this->extractDomain($appUrl);

        $content = <<<ENV
APP_NAME="{$appName}"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_TIMEZONE={$timezone}
APP_URL={$appUrl}
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE={$dbPath}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=null
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=file
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS={$mailFromAddress}
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"
ENV;

        if ($adminEmail !== null) {
            $content .= "\nADMIN_EMAIL={$adminEmail}";
        }

        return $content;
    }

    /**
     * Get full database path with sanitized filename.
     */
    private function getDatabasePath(string $dbName): string
    {
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $dbName);

        return base_path('database/' . $sanitized . '.sqlite');
    }

    /**
     * Extract domain from URL for mail configuration.
     */
    private function extractDomain(string $url): string
    {
        $parsed = parse_url($url);

        return $parsed['host'] ?? 'example.com';
    }

    /**
     * Write content to file atomically with secure permissions.
     *
     * @throws RuntimeException
     */
    private function writeAtomic(string $path, string $content): void
    {
        $tempPath = $path . '.tmp';

        if (File::put($tempPath, $content) === false) {
            throw new RuntimeException('Failed to write temporary file');
        }

        if (! File::move($tempPath, $path)) {
            File::delete($tempPath);
            throw new RuntimeException('Failed to rename temporary file');
        }

        chmod($path, 0600);
    }
}
