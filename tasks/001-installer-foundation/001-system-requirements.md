---
task_id: 001
epic: 001-installer-foundation
title: System Requirements Check
status: pending
priority: critical
estimated_effort: 4 hours
actual_effort: null
assignee: backend-architect
dependencies: []
tags: [installer, validation, week-1]
---

# Task 001: System Requirements Check

## 📋 Overview

Build comprehensive system requirements validation for PineCMS installation. Must check PHP version, required/optional extensions, file permissions, disk space, and provide actionable error messages for shared hosting users.

**Why Critical:** Without proper validation, users may encounter cryptic errors during installation. Clear upfront validation ensures 95%+ installation success rate.

## 🎯 Acceptance Criteria

- [ ] PHP version >= 8.3.0 validated
- [ ] All required PHP extensions detected
- [ ] Optional extensions detected with warnings
- [ ] File permission checks for storage/, bootstrap/cache/, database/
- [ ] Disk space check (minimum 500MB free)
- [ ] Memory limit check (minimum 256MB)
- [ ] Max execution time check (minimum 60s)
- [ ] Clear error/warning messages with resolution steps
- [ ] JSON API endpoint for Vue wizard
- [ ] Comprehensive unit tests for all validation logic

## 🏗️ Implementation Steps

### Step 1: Create Requirements Checker Service

**File**: `app/Services/Installer/RequirementsChecker.php`

**Description**: Service class to validate all system requirements with detailed error messages.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;

class RequirementsChecker
{
    /**
     * Required PHP version
     */
    private const REQUIRED_PHP_VERSION = '8.3.0';

    /**
     * Required PHP extensions
     */
    private const REQUIRED_EXTENSIONS = [
        'pdo',
        'pdo_sqlite',
        'mbstring',
        'xml',
        'curl',
        'zip',
        'fileinfo',
        'openssl',
        'tokenizer',
        'json',
        'ctype',
    ];

    /**
     * Optional PHP extensions with benefits
     */
    private const OPTIONAL_EXTENSIONS = [
        'imagick' => 'Better image processing performance and WebP support',
        'gd' => 'Image manipulation (fallback if Imagick unavailable)',
        'opcache' => 'PHP bytecode caching for better performance',
        'redis' => 'Cache driver for improved performance (not required)',
        'intl' => 'Internationalization support',
    ];

    /**
     * Writable directories
     */
    private const WRITABLE_DIRECTORIES = [
        'storage',
        'storage/app',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache',
        'database',
    ];

    /**
     * Check all system requirements
     *
     * @return array{
     *     php: array{
     *         version: string,
     *         meets_requirement: bool,
     *         required_version: string
     *     },
     *     extensions: array{
     *         required: array<string, bool>,
     *         optional: array<string, array{installed: bool, benefit: string}>
     *     },
     *     permissions: array<string, array{path: string, writable: bool}>,
     *     environment: array{
     *         memory_limit: string,
     *         memory_limit_ok: bool,
     *         max_execution_time: int,
     *         max_execution_time_ok: bool,
     *         disk_space_free: int,
     *         disk_space_ok: bool
     *     },
     *     overall_status: bool
     * }
     */
    public function checkAll(): array
    {
        $php = $this->checkPhpVersion();
        $extensions = $this->checkExtensions();
        $permissions = $this->checkPermissions();
        $environment = $this->checkEnvironment();

        return [
            'php' => $php,
            'extensions' => $extensions,
            'permissions' => $permissions,
            'environment' => $environment,
            'overall_status' => $this->determineOverallStatus(
                $php,
                $extensions,
                $permissions,
                $environment
            ),
        ];
    }

    /**
     * Check PHP version
     *
     * @return array{version: string, meets_requirement: bool, required_version: string}
     */
    private function checkPhpVersion(): array
    {
        $currentVersion = PHP_VERSION;
        $meetsRequirement = version_compare($currentVersion, self::REQUIRED_PHP_VERSION, '>=');

        return [
            'version' => $currentVersion,
            'meets_requirement' => $meetsRequirement,
            'required_version' => self::REQUIRED_PHP_VERSION,
        ];
    }

    /**
     * Check required and optional PHP extensions
     *
     * @return array{
     *     required: array<string, bool>,
     *     optional: array<string, array{installed: bool, benefit: string}>
     * }
     */
    private function checkExtensions(): array
    {
        $required = [];
        foreach (self::REQUIRED_EXTENSIONS as $extension) {
            $required[$extension] = extension_loaded($extension);
        }

        $optional = [];
        foreach (self::OPTIONAL_EXTENSIONS as $extension => $benefit) {
            $optional[$extension] = [
                'installed' => extension_loaded($extension),
                'benefit' => $benefit,
            ];
        }

        return [
            'required' => $required,
            'optional' => $optional,
        ];
    }

    /**
     * Check directory permissions
     *
     * @return array<string, array{path: string, writable: bool, exists: bool}>
     */
    private function checkPermissions(): array
    {
        $permissions = [];

        foreach (self::WRITABLE_DIRECTORIES as $directory) {
            $path = base_path($directory);
            $exists = File::exists($path);
            $writable = $exists && File::isWritable($path);

            $permissions[$directory] = [
                'path' => $path,
                'exists' => $exists,
                'writable' => $writable,
            ];
        }

        return $permissions;
    }

    /**
     * Check environment settings
     *
     * @return array{
     *     memory_limit: string,
     *     memory_limit_bytes: int,
     *     memory_limit_ok: bool,
     *     max_execution_time: int,
     *     max_execution_time_ok: bool,
     *     disk_space_free: int,
     *     disk_space_ok: bool
     * }
     */
    private function checkEnvironment(): array
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        $memoryLimitOk = $memoryLimitBytes === -1 || $memoryLimitBytes >= 256 * 1024 * 1024; // 256MB

        $maxExecutionTime = (int) ini_get('max_execution_time');
        $maxExecutionTimeOk = $maxExecutionTime === 0 || $maxExecutionTime >= 60;

        $diskSpaceFree = disk_free_space(base_path());
        $diskSpaceOk = $diskSpaceFree >= 500 * 1024 * 1024; // 500MB

        return [
            'memory_limit' => $memoryLimit,
            'memory_limit_bytes' => $memoryLimitBytes,
            'memory_limit_ok' => $memoryLimitOk,
            'max_execution_time' => $maxExecutionTime,
            'max_execution_time_ok' => $maxExecutionTimeOk,
            'disk_space_free' => $diskSpaceFree,
            'disk_space_ok' => $diskSpaceOk,
        ];
    }

    /**
     * Convert PHP memory limit string to bytes
     *
     * @param string $value Memory limit string (e.g., "256M", "1G")
     * @return int Bytes (-1 for unlimited)
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);

        if ($value === '-1') {
            return -1;
        }

        $unit = strtolower($value[strlen($value) - 1]);
        $numeric = (int) $value;

        return match ($unit) {
            'g' => $numeric * 1024 * 1024 * 1024,
            'm' => $numeric * 1024 * 1024,
            'k' => $numeric * 1024,
            default => $numeric,
        };
    }

    /**
     * Determine overall status
     *
     * @param array<string, mixed> $php
     * @param array<string, mixed> $extensions
     * @param array<string, mixed> $permissions
     * @param array<string, mixed> $environment
     * @return bool True if all critical requirements met
     */
    private function determineOverallStatus(
        array $php,
        array $extensions,
        array $permissions,
        array $environment
    ): bool {
        // PHP version must meet requirement
        if (!$php['meets_requirement']) {
            return false;
        }

        // All required extensions must be loaded
        foreach ($extensions['required'] as $loaded) {
            if (!$loaded) {
                return false;
            }
        }

        // All directories must be writable
        foreach ($permissions as $permission) {
            if (!$permission['writable']) {
                return false;
            }
        }

        // Environment settings must be adequate
        if (!$environment['memory_limit_ok'] ||
            !$environment['max_execution_time_ok'] ||
            !$environment['disk_space_ok']) {
            return false;
        }

        return true;
    }

    /**
     * Get resolution instructions for failed requirements
     *
     * @param array<string, mixed> $requirements
     * @return array<string, string> Array of issues with resolution steps
     */
    public function getResolutionInstructions(array $requirements): array
    {
        $instructions = [];

        // PHP version
        if (!$requirements['php']['meets_requirement']) {
            $instructions['php_version'] = sprintf(
                'PHP %s or higher is required. Current version: %s. Contact your hosting provider to upgrade PHP.',
                self::REQUIRED_PHP_VERSION,
                $requirements['php']['version']
            );
        }

        // Required extensions
        foreach ($requirements['extensions']['required'] as $extension => $loaded) {
            if (!$loaded) {
                $instructions["extension_{$extension}"] = sprintf(
                    'Required PHP extension "%s" is not installed. Add this to your php.ini: extension=%s',
                    $extension,
                    $extension
                );
            }
        }

        // Permissions
        foreach ($requirements['permissions'] as $dir => $permission) {
            if (!$permission['exists']) {
                $instructions["permission_{$dir}_missing"] = sprintf(
                    'Directory "%s" does not exist. Create it: mkdir -p %s',
                    $dir,
                    $permission['path']
                );
            } elseif (!$permission['writable']) {
                $instructions["permission_{$dir}"] = sprintf(
                    'Directory "%s" is not writable. Fix permissions: chmod -R 775 %s',
                    $dir,
                    $permission['path']
                );
            }
        }

        // Environment
        if (!$requirements['environment']['memory_limit_ok']) {
            $instructions['memory_limit'] = sprintf(
                'PHP memory_limit must be at least 256MB. Current: %s. Update php.ini: memory_limit = 256M',
                $requirements['environment']['memory_limit']
            );
        }

        if (!$requirements['environment']['max_execution_time_ok']) {
            $instructions['max_execution_time'] = sprintf(
                'PHP max_execution_time must be at least 60 seconds. Current: %d. Update php.ini: max_execution_time = 60',
                $requirements['environment']['max_execution_time']
            );
        }

        if (!$requirements['environment']['disk_space_ok']) {
            $instructions['disk_space'] = sprintf(
                'At least 500MB free disk space required. Current: %s MB. Free up disk space.',
                number_format($requirements['environment']['disk_space_free'] / 1024 / 1024, 2)
            );
        }

        return $instructions;
    }
}
```

### Step 2: Create Installer Controller

**File**: `app/Http/Controllers/Installer/RequirementsController.php`

**Description**: API controller for requirements check endpoint.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\RequirementsChecker;
use Illuminate\Http\JsonResponse;

class RequirementsController extends Controller
{
    public function __construct(
        private readonly RequirementsChecker $requirementsChecker
    ) {}

    /**
     * Check system requirements
     *
     * @return JsonResponse
     */
    public function check(): JsonResponse
    {
        $requirements = $this->requirementsChecker->checkAll();

        $response = [
            'requirements' => $requirements,
            'can_proceed' => $requirements['overall_status'],
        ];

        if (!$requirements['overall_status']) {
            $response['resolution_instructions'] = $this->requirementsChecker
                ->getResolutionInstructions($requirements);
        }

        return response()->json($response);
    }
}
```

### Step 3: Define Installer Routes

**File**: `routes/installer.php`

**Description**: Dedicated routes file for installer endpoints.

**Implementation**:

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\RequirementsController;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')->middleware('web')->group(function () {
    Route::get('/requirements', [RequirementsController::class, 'check'])
        ->name('installer.requirements.check');
});
```

### Step 4: Register Installer Routes

**File**: `bootstrap/app.php`

**Description**: Register installer routes in application bootstrap.

**Implementation**:

```php
// Add to existing bootstrap/app.php

->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        // Load installer routes only if not already installed
        if (!file_exists(base_path('.env'))) {
            Route::middleware('web')
                ->group(base_path('routes/installer.php'));
        }
    }
)
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Services/Installer/RequirementsCheckerTest.php`
    - Test `checkPhpVersion()` with various PHP versions
    - Test `checkExtensions()` with mocked extension_loaded()
    - Test `checkPermissions()` with mocked file system
    - Test `checkEnvironment()` with various php.ini settings
    - Test `convertToBytes()` with G/M/K units
    - Test `determineOverallStatus()` logic
    - Test `getResolutionInstructions()` message generation

**Feature Tests:**

- `tests/Feature/Installer/RequirementsCheckTest.php`
    - Test `/installer/requirements` endpoint returns correct JSON structure
    - Test `can_proceed` is false when requirements not met
    - Test `resolution_instructions` provided when failed
    - Test endpoint returns 200 status

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\RequirementsChecker;
use Tests\TestCase;

class RequirementsCheckerTest extends TestCase
{
    private RequirementsChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checker = new RequirementsChecker();
    }

    public function test_convert_to_bytes_with_megabytes(): void
    {
        $result = $this->invokeMethod($this->checker, 'convertToBytes', ['256M']);

        $this->assertEquals(256 * 1024 * 1024, $result);
    }

    public function test_convert_to_bytes_with_gigabytes(): void
    {
        $result = $this->invokeMethod($this->checker, 'convertToBytes', ['2G']);

        $this->assertEquals(2 * 1024 * 1024 * 1024, $result);
    }

    public function test_convert_to_bytes_with_unlimited(): void
    {
        $result = $this->invokeMethod($this->checker, 'convertToBytes', ['-1']);

        $this->assertEquals(-1, $result);
    }

    /**
     * Helper to invoke private methods for testing
     */
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

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (System Requirements Check)
- **Timeline**: Week 1-2 (v1.0.0)
- **Success Criteria**: Installation completion rate > 95%, clear error messages

**Architecture:**

- **Pattern**: Service Layer (`docs/prd/04-ARCHITECTURE.md` Section 9)
- **Events**: None (validation only)
- **Storage**: File system validation only

**Quality Requirements:**

- **Security**: File permission validation (`docs/prd/09-QUALITY-REQUIREMENTS.md` Section 2.5)
- **Performance**: Validation < 1 second
- **Testing**: Unit tests for all validation logic, Feature tests for API endpoint
- **Accessibility**: N/A (Backend only)

**Related Tasks:**

- **Next**: 002-environment-generator
- **Blocks**: 003-database-setup (can't proceed without validation)
- **Depends On**: None (first task)

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes (`composer analyse`)
- [ ] Laravel Pint formatted (`vendor/bin/pint`)
- [ ] No console.log statements (N/A for backend)
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks for all public methods with array shapes

### Testing

- [ ] Unit tests written and passing (8+ test cases)
- [ ] Feature tests written and passing (API endpoint test)
- [ ] Test coverage > 80% for RequirementsChecker service
- [ ] Edge cases covered (unlimited memory, missing directories)

### Security

- [ ] No shell_exec or system() calls
- [ ] File paths validated (no directory traversal)
- [ ] Error messages don't expose sensitive paths
- [ ] JSON response sanitized

### Documentation

- [ ] PHPDoc comments added with array shape types
- [ ] Complex validation logic explained
- [ ] Resolution instructions clear and actionable

## ✅ Verification Steps

Run these commands before marking task complete:

```bash
# Backend quality check
composer quality

# Verify endpoint manually
php artisan serve
curl http://localhost:8000/installer/requirements | jq

# Manual verification checklist
# 1. Response includes php, extensions, permissions, environment
# 2. overall_status is boolean
# 3. resolution_instructions provided when overall_status = false
# 4. All error messages are actionable
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-001-system-requirements

# Implement with frequent commits
git commit -m "feat(installer): add RequirementsChecker service"
git commit -m "feat(installer): add requirements API endpoint"
git commit -m "test(installer): add RequirementsChecker unit tests"
git commit -m "feat(installer): register installer routes"

# Before completing
composer quality

# Complete task
git commit -m "feat(installer): complete task-001-system-requirements"
```
