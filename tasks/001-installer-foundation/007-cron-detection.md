---
task_id: 007
epic: 001-installer-foundation
title: Cron Job Detection & Scheduler Setup
status: pending
priority: medium
estimated_effort: 3 hours
actual_effort: null
assignee: backend-architect
dependencies: [006]
tags: [installer, scheduler, shared-hosting, week-2]
---

# Task 007: Cron Job Detection & Scheduler Setup

## 📋 Overview

Build cron job detection system that checks if traditional cron jobs are available (VPS) or if the user needs Visit-Triggered scheduler (shared hosting). Display setup instructions for both modes and allow users to choose their preferred scheduler implementation.

**Why Important:** Many shared hosting environments don't support cron jobs. PineCMS offers two scheduler modes: Visit-Triggered (default, zero configuration) and Traditional Cron (optional, better performance). This task helps users understand their options and configure accordingly.

## 🎯 Acceptance Criteria

- [ ] Detect cron job availability (check for cron binary)
- [ ] Display cron setup instructions for Traditional mode
- [ ] Explain Visit-Triggered mode (middleware-based)
- [ ] Allow user to select scheduler mode
- [ ] Update `.env` with selected scheduler mode
- [ ] Generate cron command for Traditional mode
- [ ] Test cron job execution (optional validation)
- [ ] API endpoint for cron detection
- [ ] Comprehensive unit and feature tests
- [ ] PHPStan Level 8 compliance

## 🏗️ Implementation Steps

### Step 1: Create Cron Detector Service

**File**: `app/Services/Installer/CronDetector.php`

**Description**: Service to detect cron availability and generate setup instructions.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;

class CronDetector
{
    /**
     * Scheduler modes
     */
    private const MODE_VISIT_TRIGGERED = 'visit-triggered';
    private const MODE_TRADITIONAL_CRON = 'traditional-cron';

    /**
     * Detect cron availability
     *
     * @return array{
     *     cron_available: bool,
     *     cron_path: string|null,
     *     recommended_mode: string,
     *     visit_triggered_info: array{
     *         description: string,
     *         pros: array<string>,
     *         cons: array<string>
     *     },
     *     traditional_cron_info: array{
     *         description: string,
     *         pros: array<string>,
     *         cons: array<string>,
     *         setup_command: string
     *     }
     * }
     */
    public function detect(): array
    {
        $cronAvailable = $this->isCronAvailable();
        $cronPath = $this->getCronPath();

        return [
            'cron_available' => $cronAvailable,
            'cron_path' => $cronPath,
            'recommended_mode' => $cronAvailable ? self::MODE_TRADITIONAL_CRON : self::MODE_VISIT_TRIGGERED,
            'visit_triggered_info' => $this->getVisitTriggeredInfo(),
            'traditional_cron_info' => $this->getTraditionalCronInfo(),
        ];
    }

    /**
     * Check if cron is available
     *
     * @return bool True if cron binary exists
     */
    private function isCronAvailable(): bool
    {
        // Check for cron binary
        $cronPaths = [
            '/usr/bin/crontab',
            '/usr/sbin/cron',
            '/bin/crontab',
        ];

        foreach ($cronPaths as $path) {
            if (File::exists($path)) {
                return true;
            }
        }

        // Try to execute crontab -l (may be restricted)
        $output = [];
        $returnCode = 0;
        @exec('crontab -l 2>&1', $output, $returnCode);

        // Return code 0 or 1 (empty crontab) indicates cron is available
        return in_array($returnCode, [0, 1], true);
    }

    /**
     * Get cron binary path
     *
     * @return string|null Cron binary path or null if not found
     */
    private function getCronPath(): ?string
    {
        $cronPaths = [
            '/usr/bin/crontab',
            '/usr/sbin/cron',
            '/bin/crontab',
        ];

        foreach ($cronPaths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Get Visit-Triggered scheduler information
     *
     * @return array{
     *     description: string,
     *     pros: array<string>,
     *     cons: array<string>
     * }
     */
    private function getVisitTriggeredInfo(): array
    {
        return [
            'description' => 'Visit-Triggered scheduler runs scheduled tasks when a page is visited. Uses a cache lock to ensure tasks run only once per minute.',
            'pros' => [
                'Zero configuration required',
                'Works on all shared hosting',
                'No server access needed',
                'Automatic - no manual setup',
            ],
            'cons' => [
                'Requires site traffic to run',
                'May not run if no visitors',
                'Slight overhead on page loads (cached)',
            ],
        ];
    }

    /**
     * Get Traditional Cron information
     *
     * @return array{
     *     description: string,
     *     pros: array<string>,
     *     cons: array<string>,
     *     setup_command: string
     * }
     */
    private function getTraditionalCronInfo(): array
    {
        return [
            'description' => 'Traditional cron runs scheduled tasks via system cron job. Requires server access to configure crontab.',
            'pros' => [
                'Runs independently of site traffic',
                'Precise timing (every minute)',
                'No page load overhead',
                'Better for scheduled publishing',
            ],
            'cons' => [
                'Requires cron access',
                'Manual configuration needed',
                'May not be available on basic shared hosting',
            ],
            'setup_command' => $this->generateCronCommand(),
        ];
    }

    /**
     * Generate cron command for Traditional mode
     *
     * @return string Cron command to add to crontab
     */
    private function generateCronCommand(): string
    {
        $basePath = base_path();
        $phpBinary = PHP_BINARY;

        return "* * * * * cd {$basePath} && {$phpBinary} artisan schedule:run >> /dev/null 2>&1";
    }

    /**
     * Set scheduler mode in .env
     *
     * @param string $mode Scheduler mode (visit-triggered or traditional-cron)
     * @return array{success: bool, message: string}
     */
    public function setSchedulerMode(string $mode): array
    {
        if (!in_array($mode, [self::MODE_VISIT_TRIGGERED, self::MODE_TRADITIONAL_CRON], true)) {
            return [
                'success' => false,
                'message' => 'Invalid scheduler mode. Must be: visit-triggered or traditional-cron',
            ];
        }

        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return [
                'success' => false,
                'message' => '.env file not found',
            ];
        }

        $envContent = File::get($envPath);

        // Update or add SCHEDULER_MODE
        if (str_contains($envContent, 'SCHEDULER_MODE=')) {
            $envContent = preg_replace(
                '/SCHEDULER_MODE=.*/i',
                "SCHEDULER_MODE={$mode}",
                $envContent
            );
        } else {
            $envContent .= "\nSCHEDULER_MODE={$mode}\n";
        }

        if (!File::put($envPath, $envContent)) {
            return [
                'success' => false,
                'message' => 'Failed to update .env file',
            ];
        }

        return [
            'success' => true,
            'message' => "Scheduler mode set to: {$mode}",
        ];
    }

    /**
     * Test cron job execution (validation)
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     last_run?: string,
     *     next_run?: string
     * }
     */
    public function testCronExecution(): array
    {
        // Create test file to check if cron runs
        $testFilePath = storage_path('framework/cache/cron-test-' . time() . '.txt');
        File::put($testFilePath, 'Cron test created at: ' . now()->toIso8601String());

        // Get Laravel scheduler status
        $output = [];
        $returnCode = 0;
        @exec('cd ' . base_path() . ' && ' . PHP_BINARY . ' artisan schedule:list 2>&1', $output, $returnCode);

        $scheduledTasks = implode("\n", $output);

        return [
            'success' => $returnCode === 0,
            'message' => $returnCode === 0
                ? 'Scheduler test completed. Check if scheduled tasks run as expected.'
                : 'Scheduler test failed. Manual verification required.',
            'scheduled_tasks' => $scheduledTasks,
            'test_file' => $testFilePath,
        ];
    }

    /**
     * Get current scheduler mode
     *
     * @return string Current scheduler mode
     */
    public function getCurrentMode(): string
    {
        return config('app.scheduler_mode', self::MODE_VISIT_TRIGGERED);
    }
}
```

### Step 2: Create Visit-Triggered Scheduler Middleware

**File**: `app/Http/Middleware/RunScheduledTasks.php`

**Description**: Middleware to run scheduler on page visits (Visit-Triggered mode).

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RunScheduledTasks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run for visit-triggered mode
        if (config('app.scheduler_mode') !== 'visit-triggered') {
            return $next($request);
        }

        // Use cache lock to prevent multiple executions
        $lockKey = 'scheduler:last-run';
        $lockTtl = 60; // 60 seconds

        if (!Cache::has($lockKey)) {
            // Run scheduler in background (non-blocking)
            if (function_exists('fastcgi_finish_request')) {
                // Send response to user first, then run scheduler
                register_shutdown_function(function () use ($lockKey, $lockTtl) {
                    Cache::put($lockKey, now()->timestamp, $lockTtl);
                    Artisan::call('schedule:run');
                });
            } else {
                // Run scheduler immediately (blocking)
                Cache::put($lockKey, now()->timestamp, $lockTtl);
                Artisan::call('schedule:run');
            }
        }

        return $next($request);
    }
}
```

### Step 3: Create Cron Controller

**File**: `app/Http/Controllers/Installer/CronController.php`

**Description**: API endpoints for cron detection and configuration.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\CronDetector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function __construct(
        private readonly CronDetector $detector
    ) {}

    /**
     * Detect cron availability
     *
     * @return JsonResponse
     */
    public function detect(): JsonResponse
    {
        $detection = $this->detector->detect();

        return response()->json([
            'success' => true,
            'detection' => $detection,
        ]);
    }

    /**
     * Set scheduler mode
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setMode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mode' => 'required|in:visit-triggered,traditional-cron',
        ]);

        $result = $this->detector->setSchedulerMode($validated['mode']);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Test cron execution
     *
     * @return JsonResponse
     */
    public function test(): JsonResponse
    {
        $result = $this->detector->testCronExecution();

        return response()->json($result);
    }

    /**
     * Get current scheduler mode
     *
     * @return JsonResponse
     */
    public function getMode(): JsonResponse
    {
        $mode = $this->detector->getCurrentMode();

        return response()->json([
            'success' => true,
            'mode' => $mode,
        ]);
    }
}
```

### Step 4: Add Routes

**File**: `routes/installer.php`

**Description**: Add cron detection routes.

**Implementation**:

```php
// Add to existing installer routes

Route::prefix('installer')
    ->middleware(['web', PreventInstalledAccess::class])
    ->group(function () {
        // ... existing routes

        // Cron detection and setup
        Route::get('/cron/detect', [CronController::class, 'detect'])
            ->name('installer.cron.detect');

        Route::post('/cron/set-mode', [CronController::class, 'setMode'])
            ->name('installer.cron.set-mode');

        Route::post('/cron/test', [CronController::class, 'test'])
            ->name('installer.cron.test');

        Route::get('/cron/mode', [CronController::class, 'getMode'])
            ->name('installer.cron.get-mode');
    });
```

### Step 5: Update Config

**File**: `config/app.php`

**Description**: Add scheduler mode configuration.

**Implementation**:

```php
// Add to config/app.php

return [
    // ... existing config

    /*
    |--------------------------------------------------------------------------
    | Scheduler Mode
    |--------------------------------------------------------------------------
    |
    | Determines how scheduled tasks are run:
    | - 'visit-triggered': Run via middleware on page visits (default)
    | - 'traditional-cron': Run via system cron job
    |
    */

    'scheduler_mode' => env('SCHEDULER_MODE', 'visit-triggered'),
];
```

### Step 6: Register Middleware

**File**: `bootstrap/app.php`

**Description**: Register Visit-Triggered middleware for public routes.

**Implementation**:

```php
// Add to bootstrap/app.php middleware section

->withMiddleware(function (Middleware $middleware) {
    // Register middleware alias
    $middleware->alias([
        'prevent.installed' => \App\Http\Middleware\PreventInstalledAccess::class,
        'scheduler.visit' => \App\Http\Middleware\RunScheduledTasks::class,
    ]);

    // Apply to web routes (public pages)
    $middleware->web(append: [
        \App\Http\Middleware\RunScheduledTasks::class,
    ]);
})
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Services/Installer/CronDetectorTest.php`
    - Test `isCronAvailable()` with mocked filesystem
    - Test `getCronPath()` finds cron binary
    - Test `generateCronCommand()` generates correct command
    - Test `setSchedulerMode()` updates .env file
    - Test `getCurrentMode()` reads from config

**Feature Tests:**

- `tests/Feature/Installer/CronDetectionTest.php`
    - Test GET `/installer/cron/detect` returns detection data
    - Test POST `/installer/cron/set-mode` updates scheduler mode
    - Test POST `/installer/cron/test` validates scheduler
    - Test GET `/installer/cron/mode` returns current mode

**Middleware Tests:**

- `tests/Unit/Http/Middleware/RunScheduledTasksTest.php`
    - Test middleware runs scheduler in visit-triggered mode
    - Test middleware skips in traditional-cron mode
    - Test cache lock prevents multiple executions

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\CronDetector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CronDetectorTest extends TestCase
{
    private CronDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new CronDetector();
    }

    public function test_detect_returns_correct_structure(): void
    {
        $result = $this->detector->detect();

        $this->assertArrayHasKey('cron_available', $result);
        $this->assertArrayHasKey('cron_path', $result);
        $this->assertArrayHasKey('recommended_mode', $result);
        $this->assertArrayHasKey('visit_triggered_info', $result);
        $this->assertArrayHasKey('traditional_cron_info', $result);
    }

    public function test_generate_cron_command_includes_correct_paths(): void
    {
        $command = $this->invokeMethod($this->detector, 'generateCronCommand');

        $this->assertStringContainsString('artisan schedule:run', $command);
        $this->assertStringContainsString(base_path(), $command);
        $this->assertStringContainsString(PHP_BINARY, $command);
    }

    public function test_set_scheduler_mode_rejects_invalid_mode(): void
    {
        $result = $this->detector->setSchedulerMode('invalid-mode');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid scheduler mode', $result['message']);
    }

    public function test_set_scheduler_mode_accepts_visit_triggered(): void
    {
        // Create temporary .env
        $envPath = base_path('.env');
        $originalContent = File::exists($envPath) ? File::get($envPath) : '';

        File::put($envPath, "APP_NAME=Test\n");

        $result = $this->detector->setSchedulerMode('visit-triggered');

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('visit-triggered', $result['message']);

        // Verify .env updated
        $envContent = File::get($envPath);
        $this->assertStringContainsString('SCHEDULER_MODE=visit-triggered', $envContent);

        // Restore original .env
        if ($originalContent) {
            File::put($envPath, $originalContent);
        }
    }

    public function test_get_current_mode_returns_default(): void
    {
        Config::set('app.scheduler_mode', 'visit-triggered');

        $mode = $this->detector->getCurrentMode();

        $this->assertEquals('visit-triggered', $mode);
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

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (Cron-Job Detection)
- **Architecture**: `docs/prd/04-ARCHITECTURE.md` Section 5.3 (Scheduler Implementation)
- **Timeline**: Week 2 (v1.0.0)
- **Success Criteria**: Both scheduler modes work, clear setup instructions

**Architecture:**

- **Visit-Triggered**: Middleware-based (shared hosting compatible)
- **Traditional Cron**: System cron job (VPS/dedicated servers)
- **Cache Lock**: Prevents duplicate scheduler runs

**Quality Requirements:**

- **Performance**: Visit-Triggered uses cache lock (minimal overhead)
- **Testing**: Unit tests for all scheduler logic, middleware tests

**Related Tasks:**

- **Next**: 008-e2e-tests
- **Blocks**: Scheduled publishing, automated backups
- **Depends On**: 006-post-install-cleanup (cron setup is post-installation)

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

### Functionality

- [ ] Cron detection works correctly
- [ ] Visit-Triggered mode runs scheduler via middleware
- [ ] Traditional Cron mode setup instructions displayed
- [ ] Scheduler mode saved to .env
- [ ] Cache lock prevents duplicate runs

### Documentation

- [ ] PHPDoc comments added
- [ ] Scheduler modes explained
- [ ] Cron command documented

## ✅ Verification Steps

```bash
# Backend quality check
composer quality

# Test cron detection
curl http://localhost:8000/installer/cron/detect | jq

# Test setting scheduler mode
curl -X POST http://localhost:8000/installer/cron/set-mode \
  -H "Content-Type: application/json" \
  -d '{"mode": "visit-triggered"}' | jq

# Verify .env updated
grep SCHEDULER_MODE .env

# Test Visit-Triggered mode
# Visit any public page and check if scheduler runs
curl http://localhost:8000/

# Check cache lock
php artisan tinker
>>> Cache::get('scheduler:last-run')

# Test Traditional Cron mode
php artisan schedule:run

# View scheduled tasks
php artisan schedule:list
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-007-cron-detection

# Implement
git commit -m "feat(installer): add CronDetector service"
git commit -m "feat(installer): add RunScheduledTasks middleware"
git commit -m "feat(installer): add cron detection endpoints"
git commit -m "test(installer): add CronDetector unit tests"
git commit -m "feat(installer): add scheduler mode configuration"

# Before completing
composer quality

# Complete task
git commit -m "feat(installer): complete task-007-cron-detection

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```
