---
task_id: 002
epic: 001-installer-foundation
title: Environment File Generator
status: pending
priority: critical
estimated_effort: 6 hours
actual_effort: null
assignee: backend-architect
dependencies: [001]
tags: [installer, configuration, security, week-1]
---

# Task 002: Environment File Generator

## 📋 Overview

Build secure `.env` file generation service that creates production-ready configuration with cryptographically secure APP_KEY, database path, and sensible defaults for shared hosting environments.

**Why Critical:** The `.env` file is the foundation of Laravel configuration. A secure, properly formatted `.env` ensures the application works out-of-the-box while maintaining security best practices.

## 🎯 Acceptance Criteria

- [ ] Generate secure random APP_KEY (base64:44 characters)
- [ ] Set APP_ENV=production, APP_DEBUG=false
- [ ] Configure SQLite database path
- [ ] Set session/cache/queue drivers (file/database for shared hosting)
- [ ] Generate unique DB_DATABASE filename
- [ ] Set secure session configuration (httponly, secure, samesite)
- [ ] Configure mail settings placeholder
- [ ] API endpoint for environment generation
- [ ] Atomic file write (temp file → rename for safety)
- [ ] Comprehensive unit tests
- [ ] PHPStan Level 8 compliance

## 🏗️ Implementation Steps

### Step 1: Create Environment Generator Service

**File**: `app/Services/Installer/EnvironmentGenerator.php`

**Description**: Service to generate secure `.env` configuration file.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class EnvironmentGenerator
{
    /**
     * Generate .env file with secure defaults
     *
     * @param array{
     *     app_name: string,
     *     app_url: string,
     *     admin_email?: string,
     *     db_name?: string,
     *     timezone?: string
     * } $config
     * @return array{success: bool, path: string, message: string}
     */
    public function generate(array $config): array
    {
        $envPath = base_path('.env');

        // Prevent overwriting existing .env
        if (File::exists($envPath)) {
            return [
                'success' => false,
                'path' => $envPath,
                'message' => '.env file already exists. Installation may already be complete.',
            ];
        }

        $content = $this->buildEnvironmentContent($config);

        try {
            $this->writeAtomic($envPath, $content);

            return [
                'success' => true,
                'path' => $envPath,
                'message' => '.env file created successfully.',
            ];
        } catch (RuntimeException $e) {
            return [
                'success' => false,
                'path' => $envPath,
                'message' => 'Failed to write .env file: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build environment file content
     *
     * @param array{
     *     app_name: string,
     *     app_url: string,
     *     admin_email?: string,
     *     db_name?: string,
     *     timezone?: string
     * } $config
     * @return string .env file content
     */
    private function buildEnvironmentContent(array $config): string
    {
        $appKey = $this->generateAppKey();
        $dbName = $config['db_name'] ?? 'pinecms_' . Str::random(8);
        $timezone = $config['timezone'] ?? 'UTC';

        return <<<ENV
APP_NAME="{$config['app_name']}"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_TIMEZONE={$timezone}
APP_URL={$config['app_url']}
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

# Database Configuration (SQLite)
DB_CONNECTION=sqlite
DB_DATABASE={$this->getDatabasePath($dbName)}
DB_FOREIGN_KEYS=true

# Session Configuration (File-based for shared hosting)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Broadcasting (Disabled)
BROADCAST_CONNECTION=null

# Cache Configuration (File-based for shared hosting)
CACHE_STORE=file
CACHE_PREFIX=pinecms_cache

# Filesystem Disk
FILESYSTEM_DISK=local

# Queue Configuration (Database for shared hosting)
QUEUE_CONNECTION=database

# Mail Configuration (Requires SMTP setup)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@{$this->extractDomain($config['app_url'])}"
MAIL_FROM_NAME="{$config['app_name']}"

# Security Headers
SECURE_HEADERS=true
FORCE_HTTPS=false

# PineCMS Specific
PINECMS_VERSION=1.0.0
PINECMS_INSTALLED=true
PINECMS_INSTALLER_DISABLED=false
ENV;
    }

    /**
     * Generate secure application key
     *
     * @return string base64 encoded 32-byte random key
     */
    private function generateAppKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }

    /**
     * Get database file path
     *
     * @param string $dbName Database filename
     * @return string Absolute path to SQLite database
     */
    private function getDatabasePath(string $dbName): string
    {
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $dbName);

        return base_path('database/' . $sanitized . '.sqlite');
    }

    /**
     * Extract domain from URL
     *
     * @param string $url Application URL
     * @return string Domain portion
     */
    private function extractDomain(string $url): string
    {
        $parsed = parse_url($url);

        return $parsed['host'] ?? 'example.com';
    }

    /**
     * Write file atomically (temp file → rename)
     *
     * @param string $path Target file path
     * @param string $content File content
     * @throws RuntimeException If write fails
     */
    private function writeAtomic(string $path, string $content): void
    {
        $tempPath = $path . '.tmp';

        if (File::put($tempPath, $content) === false) {
            throw new RuntimeException('Failed to write temporary file');
        }

        if (!rename($tempPath, $path)) {
            File::delete($tempPath);
            throw new RuntimeException('Failed to rename temporary file to .env');
        }

        chmod($path, 0600); // Restrict to owner read/write only
    }

    /**
     * Validate configuration array
     *
     * @param array<string, mixed> $config
     * @return array{valid: bool, errors: array<string, string>}
     */
    public function validateConfig(array $config): array
    {
        $errors = [];

        if (empty($config['app_name'])) {
            $errors['app_name'] = 'Application name is required';
        }

        if (empty($config['app_url'])) {
            $errors['app_url'] = 'Application URL is required';
        } elseif (!filter_var($config['app_url'], FILTER_VALIDATE_URL)) {
            $errors['app_url'] = 'Application URL must be a valid URL';
        }

        if (isset($config['admin_email']) && !filter_var($config['admin_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['admin_email'] = 'Admin email must be a valid email address';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
}
```

### Step 2: Create Environment Controller

**File**: `app/Http/Controllers/Installer/EnvironmentController.php`

**Description**: API endpoint for environment generation.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\EnvironmentGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EnvironmentController extends Controller
{
    public function __construct(
        private readonly EnvironmentGenerator $generator
    ) {}

    /**
     * Generate .env file
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url|max:255',
            'admin_email' => 'nullable|email|max:255',
            'db_name' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_-]+$/',
            'timezone' => 'nullable|string|timezone:all',
        ]);

        $configValidation = $this->generator->validateConfig($validated);

        if (!$configValidation['valid']) {
            throw ValidationException::withMessages($configValidation['errors']);
        }

        $result = $this->generator->generate($validated);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'env_path' => $result['path'],
        ], $result['success'] ? 201 : 409);
    }
}
```

### Step 3: Add Route

**File**: `routes/installer.php`

**Description**: Add environment generation route.

**Implementation**:

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')->middleware('web')->group(function () {
    Route::get('/requirements', [RequirementsController::class, 'check'])
        ->name('installer.requirements.check');

    Route::post('/environment', [EnvironmentController::class, 'generate'])
        ->name('installer.environment.generate');
});
```

### Step 4: Create .env.example Template

**File**: `.env.example`

**Description**: Template for manual .env creation.

**Implementation**:

```env
APP_NAME=PineCMS
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=http://localhost

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
DB_DATABASE=database/pinecms.sqlite
DB_FOREIGN_KEYS=true

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=null

CACHE_STORE=file
CACHE_PREFIX=pinecms_cache

FILESYSTEM_DISK=local

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

SECURE_HEADERS=true
FORCE_HTTPS=false

PINECMS_VERSION=1.0.0
PINECMS_INSTALLED=false
PINECMS_INSTALLER_DISABLED=false
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Services/Installer/EnvironmentGeneratorTest.php`
    - Test `generateAppKey()` returns valid base64:32-byte format
    - Test `buildEnvironmentContent()` with various configurations
    - Test `getDatabasePath()` sanitizes filenames
    - Test `extractDomain()` handles various URL formats
    - Test `validateConfig()` catches invalid inputs
    - Test `.env` exists check prevents overwrite
    - Test atomic write creates temp file then renames
    - Test file permissions set to 0600

**Feature Tests:**

- `tests/Feature/Installer/EnvironmentGenerationTest.php`
    - Test POST `/installer/environment` creates .env file
    - Test validation errors for invalid inputs
    - Test 409 status when .env already exists
    - Test generated .env has correct structure

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\EnvironmentGenerator;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class EnvironmentGeneratorTest extends TestCase
{
    private EnvironmentGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new EnvironmentGenerator();
    }

    public function test_generate_app_key_format(): void
    {
        $key = $this->invokeMethod($this->generator, 'generateAppKey');

        $this->assertStringStartsWith('base64:', $key);
        $this->assertEquals(51, strlen($key)); // 'base64:' + 44 chars
    }

    public function test_validate_config_requires_app_name(): void
    {
        $result = $this->generator->validateConfig([
            'app_url' => 'https://example.com',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('app_name', $result['errors']);
    }

    public function test_validate_config_requires_valid_url(): void
    {
        $result = $this->generator->validateConfig([
            'app_name' => 'Test',
            'app_url' => 'not-a-url',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('app_url', $result['errors']);
    }

    public function test_get_database_path_sanitizes_filename(): void
    {
        $path = $this->invokeMethod($this->generator, 'getDatabasePath', ['test@db#name']);

        $this->assertStringContainsString('test_db_name', $path);
        $this->assertStringEndsWith('.sqlite', $path);
    }

    public function test_extract_domain_from_url(): void
    {
        $domain = $this->invokeMethod($this->generator, 'extractDomain', ['https://example.com/path']);

        $this->assertEquals('example.com', $domain);
    }

    private function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
```

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (Environment Setup)
- **Timeline**: Week 1-2 (v1.0.0)
- **Success Criteria**: Secure APP_KEY generation, production-ready defaults

**Architecture:**

- **Pattern**: Service Layer (`docs/prd/04-ARCHITECTURE.md` Section 9)
- **Security**: Secure key generation, file permissions
- **Storage**: File system (`.env` file)

**Quality Requirements:**

- **Security**: Cryptographically secure random keys, .env permissions 0600 (`docs/prd/09-QUALITY-REQUIREMENTS.md`)
- **Performance**: Generation < 1 second
- **Testing**: Unit tests for all methods, Feature test for API endpoint

**Related Tasks:**

- **Next**: 003-database-setup
- **Blocks**: 003-database-setup (needs .env for DB path)
- **Depends On**: 001-system-requirements

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes (`composer analyse`)
- [ ] Laravel Pint formatted (`vendor/bin/pint`)
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks with array shape types
- [ ] No hardcoded secrets in code

### Testing

- [ ] Unit tests written and passing (8+ test cases)
- [ ] Feature tests written and passing
- [ ] Test coverage > 80%
- [ ] Edge cases covered (existing .env, invalid URLs)

### Security

- [ ] Cryptographically secure random key generation
- [ ] File permissions set to 0600 (owner read/write only)
- [ ] No sensitive data in error messages
- [ ] Atomic file write prevents corruption
- [ ] Input validation prevents path traversal

### Documentation

- [ ] PHPDoc comments added
- [ ] .env.example template updated
- [ ] Security considerations documented

## ✅ Verification Steps

```bash
# Backend quality check
composer quality

# Test endpoint manually
php artisan serve
curl -X POST http://localhost:8000/installer/environment \
  -H "Content-Type: application/json" \
  -d '{
    "app_name": "Test CMS",
    "app_url": "https://test.com",
    "timezone": "America/New_York"
  }' | jq

# Verify .env file created
cat .env

# Check file permissions
ls -la .env  # Should show -rw------- (0600)

# Verify APP_KEY format
grep APP_KEY .env  # Should be base64:...
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-002-environment-generator

# Implement
git commit -m "feat(installer): add EnvironmentGenerator service"
git commit -m "feat(installer): add environment generation endpoint"
git commit -m "test(installer): add EnvironmentGenerator unit tests"
git commit -m "feat(installer): add .env.example template"

# Before completing
composer quality

# Complete task
git commit -m "feat(installer): complete task-002-environment-generator"
```
