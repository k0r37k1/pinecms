# Task 008: Installation Wizard E2E Tests - Implementation Plan

**Date:** 2025-11-11
**Epic:** 001 - Installer & Foundation
**Branch:** `task-008-installer-e2e-tests`
**Design Doc:** `docs/plans/2025-11-11-installer-e2e-tests-design.md`

---

## 📋 Overview

Detailed step-by-step implementation plan for building comprehensive Playwright E2E tests for the PineCMS installer. This plan follows TDD principles and uses specialized agents for quality assurance.

**Total Estimated Time:** 4 hours
**Approach:** TDD (RED → GREEN → REFACTOR)
**Test Isolation:** Testing-only unlock endpoint

---

## 🎯 Implementation Phases

### Phase 1: Backend Foundation (30 min)

Create the testing unlock endpoint for test isolation.

#### 1.1 Create UnlockController (RED)

**File:** `tests/Feature/Installer/UnlockControllerTest.php`

**Test Cases:**
- ✅ Returns 403 in production/local environment
- ✅ Returns 200 in testing environment
- ✅ Deletes `.installed` file
- ✅ Deletes `.env` file
- ✅ Deletes `database/pinecms.sqlite`
- ✅ Returns success JSON response

**Implementation:**
```php
// tests/Feature/Installer/UnlockControllerTest.php
test('unlock endpoint returns 403 in non-testing environment', function () {
    config(['app.env' => 'production']);

    $response = $this->postJson('/installer/unlock');

    $response->assertStatus(403);
});

test('unlock endpoint deletes installation files in testing environment', function () {
    // Create test files
    File::put(base_path('.installed'), '');
    File::put(base_path('.env'), 'APP_KEY=test');
    File::put(database_path('pinecms.sqlite'), '');

    $response = $this->postJson('/installer/unlock');

    $response->assertOk();
    $response->assertJson(['success' => true]);

    expect(File::exists(base_path('.installed')))->toBeFalse();
    expect(File::exists(base_path('.env')))->toBeFalse();
    expect(File::exists(database_path('pinecms.sqlite')))->toBeFalse();
});
```

**Run Test (RED):**
```bash
php artisan test --filter=UnlockControllerTest
```

#### 1.2 Implement UnlockController (GREEN)

**File:** `app/Http/Controllers/Installer/UnlockController.php`

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

/**
 * Unlock installer for E2E testing purposes.
 * Only works in testing environment for security.
 */
class UnlockController extends Controller
{
    /**
     * Unlock installer by removing installation artifacts.
     */
    public function unlock(): JsonResponse
    {
        // Security: Only allow in testing environment
        if (! app()->environment('testing')) {
            abort(403, 'Unlock endpoint only available in testing environment');
        }

        // Delete installation lock file
        $this->deleteIfExists(base_path('.installed'));

        // Delete environment configuration
        $this->deleteIfExists(base_path('.env'));

        // Delete SQLite database
        $this->deleteIfExists(database_path('pinecms.sqlite'));

        return response()->json([
            'success' => true,
            'message' => 'Installation unlocked successfully',
        ]);
    }

    /**
     * Delete file if it exists.
     */
    private function deleteIfExists(string $path): void
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
```

**Run Test (GREEN):**
```bash
php artisan test --filter=UnlockControllerTest
```

#### 1.3 Add Route

**File:** `routes/web.php`

```php
// Add to installer routes group
Route::post('/installer/unlock', [UnlockController::class, 'unlock'])
    ->name('installer.unlock');
```

**Verify:**
```bash
php artisan route:list --path=installer
```

---

### Phase 2: Frontend data-testid Attributes (60 min)

Add granular testid attributes to all 7 installer views.

#### 2.1 Requirements.vue

**File:** `resources/js/Pages/Installer/Requirements.vue`

<!-- cspell:disable -->
**Required testids:**
- `installer-wizard` - Main container
- `check-php-version` - PHP version check
- `check-extensions` - Extensions check
- `check-permissions` - File permissions check
- `requirements-status` - Overall status message
- `btn-next-step` - Next button

#### 2.2 Environment.vue

**File:** `resources/js/Pages/Installer/Environment.vue`

**Required testids:**
- `env-app-name` - App name input
- `env-app-url` - App URL input
- `env-timezone` - Timezone select
- `btn-generate-env` - Generate button
- `env-success-message` - Success message
- `btn-next-step` - Next button

#### 2.3 Database.vue

**File:** `resources/js/Pages/Installer/Database.vue`

**Required testids:**
- `btn-init-database` - Initialize button
- `db-success` - DB creation success message
- `btn-run-migrations` - Run migrations button
- `migrations-success` - Migrations success message
- `btn-next-step` - Next button

#### 2.4 AdminUser.vue (Multi-Step)

**File:** `resources/js/Pages/Installer/AdminUser.vue`

**Required testids:**

**Substep 1 (Basic Info):**
- `admin-name-input` - Name input
- `admin-email-input` - Email input
- `btn-next-substep` - Next substep button

**Substep 2 (Password):**
- `admin-password-input` - Password input
- `admin-password-confirm` - Password confirmation
- `password-strength` - Strength indicator
- `password-mismatch-error` - Mismatch error message
- `btn-next-substep` - Next substep button

**Substep 3 (Review):**
- `review-admin-name` - Name display
- `review-admin-email` - Email display
- `btn-create-admin` - Create button
- `admin-success` - Success message
- `btn-next-step` - Next step button

#### 2.5 WebServer.vue

**File:** `resources/js/Pages/Installer/WebServer.vue`

**Required testids:**
- `btn-generate-htaccess` - Generate .htaccess button
- `htaccess-success` - Success message
- `btn-next-step` - Next button

#### 2.6 Scheduler.vue

**File:** `resources/js/Pages/Installer/Scheduler.vue`

**Required testids:**
- `scheduler-mode-visit-triggered` - Visit mode radio
- `scheduler-mode-traditional-cron` - Cron mode radio
- `btn-save-scheduler-mode` - Save button
- `scheduler-success` - Success message
- `btn-next-step` - Next button

#### 2.7 Cleanup.vue

**File:** `resources/js/Pages/Installer/Cleanup.vue`

**Required testids:**
- `btn-finish-installation` - Finish button
- `cleanup-success` - Success message
<!-- cspell:enable -->

**Verification:**
```bash
npm run dev
# Manually verify testids exist in browser DevTools
```

---

### Phase 3: E2E Test Helpers (30 min)

Create helper classes for test workflows.

#### 3.1 TestCleanupHelper.ts

**File:** `tests/E2E/helpers/TestCleanupHelper.ts`

```typescript
import { Page } from '@playwright/test';

/**
 * Helper for cleaning installation state between E2E tests.
 */
export class TestCleanupHelper {
    constructor(private page: Page) {}

    /**
     * Unlock installer by removing installation artifacts.
     * Only works in testing environment.
     */
    async unlock(): Promise<void> {
        const response = await this.page.request.post('/installer/unlock');

        if (!response.ok()) {
            throw new Error(
                `Unlock failed with status ${response.status()}: ${await response.text()}`
            );
        }
    }
}
```

#### 3.2 InstallerHelper.ts

**File:** `tests/E2E/helpers/InstallerHelper.ts`

**Methods to implement:**
- `navigateToInstaller()` - Navigate to /installer
- `completeRequirementsCheck()` - Complete step 1
- `completeEnvironmentConfig(config?)` - Complete step 2 with defaults
- `completeDatabaseInit()` - Complete step 3
- `completeAdminUserCreation(userData?)` - Complete step 4 (3 substeps)
- `completeWebServerConfig()` - Complete step 5
- `completeSchedulerConfig(mode?)` - Complete step 6
- `completeCleanup()` - Complete step 7
- `verifyRedirectToAdminLogin()` - Verify final redirect

**Default Values:**
```typescript
const DEFAULT_CONFIG = {
    appName: 'PineCMS Test Installation',
    appUrl: 'http://localhost:8000',
    timezone: 'UTC',
};

const DEFAULT_ADMIN = {
    name: 'Test Admin',
    email: 'admin@test.local',
    password: 'SecureTestPass123!',
};
```

---

### Phase 4: E2E Test Specs (90 min)

Write 9 test specification files following TDD principles.

#### 4.1 CompleteFlow.spec.ts (Happy Path) - 15 min

**File:** `tests/E2E/Installer/CompleteFlow.spec.ts`

**Tests:**
- ✅ Complete full installation successfully
- ✅ Verify redirect to admin login
- ✅ Verify `.installed` lock exists

**Priority:** **CRITICAL** - This is the smoke test

#### 4.2 RequirementsCheck.spec.ts - 10 min

**File:** `tests/E2E/Installer/RequirementsCheck.spec.ts`

**Tests:**
- ✅ Display PHP version, extensions, permissions
- ✅ Disable next button when requirements fail

#### 4.3 EnvironmentConfig.spec.ts - 10 min

**File:** `tests/E2E/Installer/EnvironmentConfig.spec.ts`

**Tests:**
- ✅ Validate required fields (app name, URL)
- ✅ Generate `.env` successfully
- ✅ Handle timezone selection

#### 4.4 DatabaseInit.spec.ts - 10 min

**File:** `tests/E2E/Installer/DatabaseInit.spec.ts`

**Tests:**
- ✅ Create SQLite database file
- ✅ Run migrations successfully
- ✅ Display success messages

#### 4.5 AdminUserWizard.spec.ts - 15 min

**File:** `tests/E2E/Installer/AdminUserWizard.spec.ts`

**Tests:**
- ✅ Validate email format in real-time
- ✅ Show password strength indicator
- ✅ Validate password confirmation match
- ✅ Display review data correctly
- ✅ Create admin user successfully

**Priority:** **HIGH** - Complex multi-step wizard

#### 4.6 WebServerConfig.spec.ts - 10 min

**File:** `tests/E2E/Installer/WebServerConfig.spec.ts`

**Tests:**
- ✅ Generate Apache `.htaccess`
- ✅ Display success message

#### 4.7 SchedulerConfig.spec.ts - 10 min

**File:** `tests/E2E/Installer/SchedulerConfig.spec.ts`

**Tests:**
- ✅ Select visit-triggered mode
- ✅ Select traditional cron mode
- ✅ Save configuration successfully

#### 4.8 PostInstallCleanup.spec.ts - 5 min

**File:** `tests/E2E/Installer/PostInstallCleanup.spec.ts`

**Tests:**
- ✅ Create `.installed` lock file
- ✅ Display success message
- ✅ Redirect to admin login

#### 4.9 ReInstallPrevention.spec.ts - 5 min

**File:** `tests/E2E/Installer/ReInstallPrevention.spec.ts`

**Tests:**
- ✅ Redirect to admin when already installed
- ✅ Block access to `/installer` after completion

**Priority:** **HIGH** - Security test

---

### Phase 5: Verification & Quality (30 min)

Run all tests and quality checks.

#### 5.1 Run E2E Tests

```bash
# Run all E2E tests
npm run test:e2e

# Run specific test file
npx playwright test tests/E2E/Installer/CompleteFlow.spec.ts

# Run in UI mode (for debugging)
npm run test:e2e:ui

# Run in debug mode
npx playwright test --debug

# Generate HTML report
npx playwright show-report storage/playwright/html-report
```

**Expected Results:**
- ✅ All tests pass on Chromium, Firefox, WebKit (local)
- ✅ Test execution time < 10 minutes
- ✅ No flaky tests (consistent pass rate)
- ✅ Screenshots captured on failures
- ✅ HTML report generated

#### 5.2 Run Quality Checks

```bash
# Frontend quality
npm run quality

# Backend quality
composer quality

# Specific checks
npm run lint
npm run type-check
npm run test:coverage
composer analyse
composer test
```

#### 5.3 Code Review with Agent

```bash
# Use code-reviewer agent
Task tool with subagent_type="code-reviewer"
```

---

## 🚀 Execution Workflow

### Step-by-Step

1. **Create Feature Branch** ✅ (Already done)
   ```bash
   git checkout -b task-008-installer-e2e-tests
   ```

2. **Phase 1: Backend Foundation** (30 min)
   - Write failing tests (RED)
   - Implement UnlockController (GREEN)
   - Refactor if needed
   - Commit: `test(installer): add UnlockController for E2E test isolation`

3. **Phase 2: Frontend testids** (60 min)
   - Add testids to all 7 views
   - Manually verify in browser
   - Commit: `feat(installer): add data-testid attributes for E2E tests`

4. **Phase 3: Test Helpers** (30 min)
   - Implement TestCleanupHelper
   - Implement InstallerHelper
   - Commit: `test(installer): add E2E test helper classes`

5. **Phase 4: Test Specs** (90 min)
   - Write CompleteFlow.spec.ts first (critical smoke test)
   - Then write remaining 8 specs
   - Commit after each spec group:
     - `test(installer): add E2E tests for happy path`
     - `test(installer): add E2E tests for validation`
     - `test(installer): add E2E tests for security`

6. **Phase 5: Verification** (30 min)
   - Run all E2E tests
   - Run quality checks
   - Code review with agent
   - Fix any issues
   - Commit: `test(installer): complete Task 008 E2E tests`

7. **Create Completion Report**
   - Document test coverage
   - Document any deviations from plan
   - Capture metrics (execution time, pass rate)

---

## 🎯 Success Criteria

- [ ] All 9 E2E test specs implemented
- [ ] All tests passing on Chromium, Firefox, WebKit
- [ ] Test execution time < 10 minutes
- [ ] No flaky tests
- [ ] 100% code quality checks passing
- [ ] Code review completed
- [ ] Completion report created

---

## 📊 Agents to Use (Sequential)

1. **test-engineer** - For writing E2E test specs
2. **debugger** - If tests fail, for troubleshooting
3. **code-reviewer** - After implementation, for quality review
4. **error-detective** - If encountering issues, for log analysis

---

## 🔄 Git Commit Strategy

Use conventional commits with descriptive messages:

```bash
test(installer): add UnlockController for E2E test isolation
feat(installer): add data-testid attributes for E2E tests
test(installer): add E2E test helper classes
test(installer): add CompleteFlow E2E test (happy path)
test(installer): add RequirementsCheck E2E tests
test(installer): add EnvironmentConfig E2E tests
test(installer): add DatabaseInit E2E tests
test(installer): add AdminUserWizard E2E tests
test(installer): add WebServerConfig E2E tests
test(installer): add SchedulerConfig E2E tests
test(installer): add PostInstallCleanup E2E tests
test(installer): add ReInstallPrevention E2E test (security)
test(installer): complete Task 008 E2E tests

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

---

**Plan Status:** ✅ Ready for Execution
**Next Step:** Begin Phase 1 (Backend Foundation)
