---
task_id: 006
epic: 001-installer-foundation
title: Post-Install Cleanup & Lock
status: pending
priority: critical
estimated_effort: 2 hours
actual_effort: null
assignee: backend-architect
dependencies: [005]
tags: [installer, security, week-2]
---

# Task 006: Post-Install Cleanup & Lock

## 📋 Overview

Build post-installation cleanup service that removes the `/install` directory, locks the installer to prevent re-runs, updates the `.env` file to mark installation complete, and redirects users to the admin panel login page.

**Why Critical:** Security risk if installer remains accessible post-installation. Attackers could potentially re-run installation, overwrite database, or create new admin users. Cleanup and locking prevents these attacks.

## 🎯 Acceptance Criteria

- [ ] Remove `/install` route directory after successful installation
- [ ] Update `.env` to set `PINECMS_INSTALLED=true`
- [ ] Create `.installed` lock file in project root
- [ ] Middleware to block installer routes if already installed
- [ ] Redirect to admin login after completion
- [ ] Success/failure messages with rollback capability
- [ ] API endpoint for cleanup execution
- [ ] Comprehensive unit and feature tests
- [ ] PHPStan Level 8 compliance

## 🏗️ Implementation Steps

### Step 1: Create Post-Install Cleanup Service

**File**: `app/Services/Installer/PostInstallCleanup.php`

**Description**: Service to cleanup installer files and lock installation.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;
use RuntimeException;

class PostInstallCleanup
{
    /**
     * Lock file name
     */
    private const LOCK_FILE = '.installed';

    /**
     * Run post-installation cleanup
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     actions_completed: array<string, bool>,
     *     errors?: array<string, string>
     * }
     */
    public function cleanup(): array
    {
        $actions = [
            'env_updated' => false,
            'lock_file_created' => false,
            'installer_routes_disabled' => false,
        ];

        $errors = [];

        // Step 1: Update .env file
        try {
            $this->updateEnvFile();
            $actions['env_updated'] = true;
        } catch (RuntimeException $e) {
            $errors['env_update'] = $e->getMessage();
        }

        // Step 2: Create lock file
        try {
            $this->createLockFile();
            $actions['lock_file_created'] = true;
        } catch (RuntimeException $e) {
            $errors['lock_file'] = $e->getMessage();
        }

        // Step 3: Mark installer as disabled
        $actions['installer_routes_disabled'] = true;

        $success = empty($errors);

        return [
            'success' => $success,
            'message' => $success
                ? 'Installation completed successfully. Installer has been locked.'
                : 'Installation completed with errors. Manual cleanup may be required.',
            'actions_completed' => $actions,
            'errors' => $errors,
        ];
    }

    /**
     * Update .env file to mark installation complete
     *
     * @throws RuntimeException If .env update fails
     */
    private function updateEnvFile(): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            throw new RuntimeException('.env file not found');
        }

        $envContent = File::get($envPath);

        // Update PINECMS_INSTALLED flag
        if (str_contains($envContent, 'PINECMS_INSTALLED=')) {
            $envContent = preg_replace(
                '/PINECMS_INSTALLED=.*/i',
                'PINECMS_INSTALLED=true',
                $envContent
            );
        } else {
            $envContent .= "\nPINECMS_INSTALLED=true\n";
        }

        // Disable installer
        if (str_contains($envContent, 'PINECMS_INSTALLER_DISABLED=')) {
            $envContent = preg_replace(
                '/PINECMS_INSTALLER_DISABLED=.*/i',
                'PINECMS_INSTALLER_DISABLED=true',
                $envContent
            );
        } else {
            $envContent .= "PINECMS_INSTALLER_DISABLED=true\n";
        }

        if (!File::put($envPath, $envContent)) {
            throw new RuntimeException('Failed to update .env file');
        }
    }

    /**
     * Create installation lock file
     *
     * @throws RuntimeException If lock file creation fails
     */
    private function createLockFile(): void
    {
        $lockPath = base_path(self::LOCK_FILE);

        $lockData = [
            'installed_at' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        $content = json_encode($lockData, JSON_PRETTY_PRINT);

        if (!File::put($lockPath, $content)) {
            throw new RuntimeException('Failed to create lock file');
        }

        chmod($lockPath, 0444); // Read-only
    }

    /**
     * Check if installation is complete
     *
     * @return bool True if already installed
     */
    public function isInstalled(): bool
    {
        // Check lock file
        if (File::exists(base_path(self::LOCK_FILE))) {
            return true;
        }

        // Check .env flag
        if (config('app.installed') === true) {
            return true;
        }

        // Check if .env has PINECMS_INSTALLED=true
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (preg_match('/PINECMS_INSTALLED=true/i', $envContent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get installation information
     *
     * @return array{
     *     installed: bool,
     *     installed_at?: string,
     *     version?: string,
     *     php_version?: string,
     *     laravel_version?: string
     * }|null
     */
    public function getInstallationInfo(): ?array
    {
        $lockPath = base_path(self::LOCK_FILE);

        if (!File::exists($lockPath)) {
            return [
                'installed' => false,
            ];
        }

        $lockData = json_decode(File::get($lockPath), true);

        return [
            'installed' => true,
            'installed_at' => $lockData['installed_at'] ?? null,
            'version' => $lockData['version'] ?? null,
            'php_version' => $lockData['php_version'] ?? null,
            'laravel_version' => $lockData['laravel_version'] ?? null,
        ];
    }

    /**
     * Unlock installation (for development/testing only)
     *
     * @return array{success: bool, message: string}
     */
    public function unlock(): array
    {
        if (!app()->environment('local', 'testing')) {
            return [
                'success' => false,
                'message' => 'Unlock is only allowed in local or testing environments',
            ];
        }

        $actions = [];

        // Remove lock file
        $lockPath = base_path(self::LOCK_FILE);
        if (File::exists($lockPath)) {
            File::delete($lockPath);
            $actions[] = 'Lock file removed';
        }

        // Update .env
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            $envContent = preg_replace('/PINECMS_INSTALLED=true/i', 'PINECMS_INSTALLED=false', $envContent);
            $envContent = preg_replace('/PINECMS_INSTALLER_DISABLED=true/i', 'PINECMS_INSTALLER_DISABLED=false', $envContent);
            File::put($envPath, $envContent);
            $actions[] = '.env updated';
        }

        return [
            'success' => true,
            'message' => 'Installation unlocked successfully',
            'actions' => $actions,
        ];
    }
}
```

### Step 2: Create Installer Lock Middleware

**File**: `app/Http/Middleware/PreventInstalledAccess.php`

**Description**: Middleware to block installer routes if already installed.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Installer\PostInstallCleanup;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventInstalledAccess
{
    public function __construct(
        private readonly PostInstallCleanup $cleanup
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if installation is complete
        if ($this->cleanup->isInstalled()) {
            // Redirect to admin login
            return redirect('/admin/login')->with('error', 'Installation already completed.');
        }

        return $next($request);
    }
}
```

### Step 3: Create Cleanup Controller

**File**: `app/Http/Controllers/Installer/CleanupController.php`

**Description**: API endpoint for post-install cleanup.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\PostInstallCleanup;
use Illuminate\Http\JsonResponse;

class CleanupController extends Controller
{
    public function __construct(
        private readonly PostInstallCleanup $cleanup
    ) {}

    /**
     * Run post-installation cleanup
     *
     * @return JsonResponse
     */
    public function cleanup(): JsonResponse
    {
        // Check if already installed
        if ($this->cleanup->isInstalled()) {
            return response()->json([
                'success' => false,
                'message' => 'Installation already completed.',
            ], 409);
        }

        $result = $this->cleanup->cleanup();

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Get installation status
     *
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $info = $this->cleanup->getInstallationInfo();

        return response()->json([
            'success' => true,
            'installation' => $info,
        ]);
    }

    /**
     * Unlock installation (development only)
     *
     * @return JsonResponse
     */
    public function unlock(): JsonResponse
    {
        $result = $this->cleanup->unlock();

        return response()->json($result, $result['success'] ? 200 : 403);
    }
}
```

### Step 4: Add Routes

**File**: `routes/installer.php`

**Description**: Add cleanup routes with middleware.

**Implementation**:

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\AdminUserController;
use App\Http\Controllers\Installer\CleanupController;
use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use App\Http\Controllers\Installer\WebServerController;
use App\Http\Middleware\PreventInstalledAccess;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')
    ->middleware(['web', PreventInstalledAccess::class])
    ->group(function () {
        Route::get('/requirements', [RequirementsController::class, 'check'])
            ->name('installer.requirements.check');

        Route::post('/environment', [EnvironmentController::class, 'generate'])
            ->name('installer.environment.generate');

        Route::post('/database/initialize', [DatabaseController::class, 'initialize'])
            ->name('installer.database.initialize');

        Route::post('/database/migrate', [DatabaseController::class, 'migrate'])
            ->name('installer.database.migrate');

        Route::get('/database/info', [DatabaseController::class, 'info'])
            ->name('installer.database.info');

        Route::post('/admin-user', [AdminUserController::class, 'create'])
            ->name('installer.admin-user.create');

        Route::post('/admin-user/check-password', [AdminUserController::class, 'checkPasswordStrength'])
            ->name('installer.admin-user.check-password');

        Route::post('/webserver/apache', [WebServerController::class, 'generateApacheConfig'])
            ->name('installer.webserver.apache');

        Route::post('/webserver/nginx', [WebServerController::class, 'generateNginxExample'])
            ->name('installer.webserver.nginx');

        Route::get('/webserver/detect', [WebServerController::class, 'detectServer'])
            ->name('installer.webserver.detect');

        // Cleanup (final step)
        Route::post('/cleanup', [CleanupController::class, 'cleanup'])
            ->name('installer.cleanup');

        Route::get('/status', [CleanupController::class, 'status'])
            ->name('installer.status');
    });

// Development-only unlock endpoint
Route::post('/installer/unlock', [CleanupController::class, 'unlock'])
    ->middleware('web')
    ->name('installer.unlock');
```

### Step 5: Register Middleware

**File**: `bootstrap/app.php`

**Description**: Register PreventInstalledAccess middleware.

**Implementation**:

```php
// Add to bootstrap/app.php middleware section

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'prevent.installed' => \App\Http\Middleware\PreventInstalledAccess::class,
    ]);
})
```

### Step 6: Update Config

**File**: `config/app.php`

**Description**: Add installed flag to app config.

**Implementation**:

```php
// Add to config/app.php

return [
    // ... existing config

    /*
    |--------------------------------------------------------------------------
    | Installation Status
    |--------------------------------------------------------------------------
    |
    | This value determines whether the application has been installed.
    | It is read from the PINECMS_INSTALLED environment variable.
    |
    */

    'installed' => env('PINECMS_INSTALLED', false),
    'installer_disabled' => env('PINECMS_INSTALLER_DISABLED', false),
];
```

## 🧪 Testing Requirements

**Unit Tests:**
- `tests/Unit/Services/Installer/PostInstallCleanupTest.php`
  - Test `updateEnvFile()` updates PINECMS_INSTALLED flag
  - Test `createLockFile()` creates .installed with correct data
  - Test `isInstalled()` detects installation status
  - Test `getInstallationInfo()` reads lock file data
  - Test `unlock()` only works in local/testing environments
  - Test lock file has read-only permissions (0444)

**Feature Tests:**
- `tests/Feature/Installer/PostInstallCleanupTest.php`
  - Test POST `/installer/cleanup` completes installation
  - Test GET `/installer/status` returns installation info
  - Test middleware blocks installer routes after installation
  - Test POST `/installer/unlock` works in testing environment
  - Test 409 status when cleanup runs on installed system

**Middleware Tests:**
- `tests/Unit/Http/Middleware/PreventInstalledAccessTest.php`
  - Test middleware redirects to admin login when installed
  - Test middleware allows access when not installed

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\PostInstallCleanup;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PostInstallCleanupTest extends TestCase
{
    private PostInstallCleanup $cleanup;
    private string $testEnvPath;
    private string $testLockPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanup = new PostInstallCleanup();
        $this->testEnvPath = base_path('.env.test');
        $this->testLockPath = base_path('.installed.test');
    }

    protected function tearDown(): void
    {
        if (File::exists($this->testEnvPath)) {
            File::delete($this->testEnvPath);
        }
        if (File::exists($this->testLockPath)) {
            File::delete($this->testLockPath);
        }
        parent::tearDown();
    }

    public function test_is_installed_returns_false_when_not_installed(): void
    {
        // Ensure lock file doesn't exist
        if (File::exists(base_path('.installed'))) {
            File::delete(base_path('.installed'));
        }

        $result = $this->cleanup->isInstalled();

        $this->assertFalse($result);
    }

    public function test_is_installed_returns_true_when_lock_file_exists(): void
    {
        // Create temporary lock file
        File::put(base_path('.installed'), json_encode(['installed_at' => now()]));

        $result = $this->cleanup->isInstalled();

        $this->assertTrue($result);

        // Cleanup
        File::delete(base_path('.installed'));
    }

    public function test_create_lock_file_creates_read_only_file(): void
    {
        $this->invokeMethod($this->cleanup, 'createLockFile');

        $lockPath = base_path('.installed');
        $this->assertFileExists($lockPath);

        // Check permissions (read-only)
        $perms = substr(sprintf('%o', fileperms($lockPath)), -3);
        $this->assertEquals('444', $perms);

        // Cleanup
        chmod($lockPath, 0644); // Make writable for deletion
        File::delete($lockPath);
    }

    public function test_get_installation_info_returns_correct_data(): void
    {
        // Create lock file
        $lockData = [
            'installed_at' => '2025-11-09T12:00:00Z',
            'version' => '1.0.0',
            'php_version' => '8.3.0',
            'laravel_version' => '12.0.0',
        ];
        File::put(base_path('.installed'), json_encode($lockData));

        $info = $this->cleanup->getInstallationInfo();

        $this->assertTrue($info['installed']);
        $this->assertEquals('2025-11-09T12:00:00Z', $info['installed_at']);
        $this->assertEquals('1.0.0', $info['version']);

        // Cleanup
        chmod(base_path('.installed'), 0644);
        File::delete(base_path('.installed'));
    }

    public function test_unlock_only_works_in_local_or_testing_environment(): void
    {
        $this->app['env'] = 'production';

        $result = $this->cleanup->unlock();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('only allowed in local or testing', $result['message']);
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
- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (Post-Install Cleanup)
- **Security**: Installer lock prevents re-installation attacks
- **Timeline**: Week 2 (v1.0.0)
- **Success Criteria**: Installer locked, redirect to admin login

**Architecture:**
- **Security**: OWASP A05 (Security Misconfiguration) prevention
- **Pattern**: Service Layer, Middleware

**Quality Requirements:**
- **Security**: Lock file prevents re-installation, middleware blocks access
- **Testing**: Unit tests for all cleanup logic, middleware tests

**Related Tasks:**
- **Next**: 007-cron-detection (optional scheduler setup)
- **Blocks**: Installation completion
- **Depends On**: 005-web-server-config (cleanup is final step)

## ✅ Quality Gates Checklist

### Code Quality
- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks with array shapes

### Testing
- [ ] Unit tests written and passing (6+ test cases)
- [ ] Feature tests written and passing
- [ ] Middleware tests written
- [ ] Test coverage > 80%

### Security
- [ ] Lock file created with read-only permissions (0444)
- [ ] Middleware blocks installer access after installation
- [ ] Unlock only works in local/testing environments
- [ ] .env updated to mark installation complete

### Documentation
- [ ] PHPDoc comments added
- [ ] Lock file format documented
- [ ] Unlock procedure documented (for developers)

## ✅ Verification Steps

```bash
# Backend quality check
composer quality

# Test cleanup endpoint
curl -X POST http://localhost:8000/installer/cleanup | jq

# Verify .installed lock file created
cat .installed
ls -la .installed  # Should show -r--r--r-- (0444)

# Verify .env updated
grep PINECMS_INSTALLED .env  # Should show true

# Test installer is blocked
curl http://localhost:8000/installer/requirements  # Should redirect

# Test status endpoint
curl http://localhost:8000/installer/status | jq

# Test unlock (testing environment only)
curl -X POST http://localhost:8000/installer/unlock | jq

# Verify lock file removed
ls -la .installed  # Should not exist
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-006-post-install-cleanup

# Implement
git commit -m "feat(installer): add PostInstallCleanup service"
git commit -m "feat(installer): add PreventInstalledAccess middleware"
git commit -m "feat(installer): add cleanup endpoints"
git commit -m "test(installer): add PostInstallCleanup unit tests"
git commit -m "feat(installer): add lock file creation with read-only permissions"

# Before completing
composer quality

# Complete task
git commit -m "feat(installer): complete task-006-post-install-cleanup

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```
