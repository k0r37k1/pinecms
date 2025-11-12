# Installation Wizard E2E Tests - Design Document

**Date:** 2025-11-11
**Task:** 008 - Installation Wizard E2E Tests
**Epic:** 001 - Installer & Foundation
**Status:** Design Validated, Ready for Implementation

---

## 🎯 Overview

Complete end-to-end test suite using Playwright to validate the full installation flow from system requirements check through post-install cleanup. Tests ensure 95%+ installation success rate and catch integration bugs that unit/feature tests miss.

---

## 🏗️ Architecture

### Test Structure

```
tests/E2E/
├── helpers/
│   ├── InstallerHelper.ts          # High-level workflow methods
│   └── TestCleanupHelper.ts        # Unlock/cleanup for test isolation
├── Installer/
│   ├── CompleteFlow.spec.ts        # Happy path (all steps)
│   ├── RequirementsCheck.spec.ts   # Requirements validation
│   ├── EnvironmentConfig.spec.ts   # .env generation
│   ├── DatabaseInit.spec.ts        # Database initialization
│   ├── AdminUserWizard.spec.ts     # User creation (multi-step)
│   ├── WebServerConfig.spec.ts     # .htaccess generation
│   ├── SchedulerConfig.spec.ts     # Cron setup
│   ├── PostInstallCleanup.spec.ts  # Cleanup & lock
│   └── ReInstallPrevention.spec.ts # Security: prevent re-install
```

### Test Isolation Strategy

**Problem:** Installer modifies system state (`.env`, `database/pinecms.sqlite`, `.installed`)

**Solution:** Testing-only unlock endpoint
- `POST /installer/unlock` - Only active in `testing` environment
- Deletes: `.installed`, `.env`, `database/pinecms.sqlite`
- Called in `beforeEach()` to ensure clean state
- No filesystem manipulation from tests
- Fast, secure, testable

**Backend Implementation:**
```php
// app/Http/Controllers/Installer/UnlockController.php
class UnlockController extends Controller
{
    public function unlock(): JsonResponse
    {
        if (! app()->environment('testing')) {
            abort(403);
        }

        File::delete(base_path('.installed'));
        File::delete(base_path('.env'));
        File::delete(database_path('pinecms.sqlite'));

        return response()->json(['success' => true]);
    }
}
```

---

## 🔧 Helper Classes

### TestCleanupHelper

**Purpose:** Backend communication for test isolation

```typescript
export class TestCleanupHelper {
    constructor(private page: Page) {}

    /**
     * Unlock installer (testing environment only)
     */
    async unlock(): Promise<void> {
        const response = await this.page.request.post('/installer/unlock');
        if (!response.ok()) {
            throw new Error(`Unlock failed: ${response.status()}`);
        }
    }
}
```

### InstallerHelper

**Purpose:** High-level workflow abstraction with sensible defaults

**Key Methods:**
- `navigateToInstaller()` - Navigate to `/installer`
- `completeRequirementsCheck()` - Verify requirements and proceed
- `completeEnvironmentConfig(config?)` - Fill .env form with defaults
- `completeDatabaseInit()` - Initialize DB and run migrations
- `completeAdminUserCreation(userData?)` - 3-step wizard with defaults
- `completeWebServerConfig()` - Generate .htaccess
- `completeSchedulerConfig(mode?)` - Select scheduler mode
- `completeCleanup()` - Finish installation and lock

**Default Test Data:**
```typescript
const defaults = {
    appName: 'PineCMS Test Installation',
    appUrl: 'http://localhost:8000',
    adminName: 'Test Admin',
    adminEmail: 'admin@test.local',
    adminPassword: 'SecureTestPass123!',
};
```

**Design Rationale:**
- Optional parameters with sensible defaults
- Tests can override defaults when needed
- Encapsulates multi-step workflows (e.g., 3-step admin wizard)
- Explicit waits for async operations
- Granular testid selectors for stability

---

## 🎯 Test Coverage

### 1. CompleteFlow.spec.ts (Happy Path)
- Complete installation from start to finish
- Verify redirect to admin login
- Verify `.installed` lock file created
- **Critical:** Main smoke test for installer

### 2. RequirementsCheck.spec.ts
- Display PHP version, extensions, permissions
- Block progression if requirements fail
- Show actionable error messages

### 3. EnvironmentConfig.spec.ts
- Validate required fields (app name, URL)
- Generate `.env` with secure APP_KEY
- Handle timezone selection

### 4. DatabaseInit.spec.ts
- Create SQLite database file
- Run migrations successfully
- Handle migration failures gracefully

### 5. AdminUserWizard.spec.ts (Multi-Step)
- Validate email format in real-time
- Password strength indicator (weak/good/strong)
- Password confirmation match validation
- Review step shows correct data
- Create admin user successfully

### 6. WebServerConfig.spec.ts
- Generate Apache `.htaccess`
- Display nginx configuration example
- Handle file write permissions

### 7. SchedulerConfig.spec.ts
- Select visit-triggered mode
- Select traditional cron mode
- Display cron setup instructions

### 8. PostInstallCleanup.spec.ts
- Create `.installed` lock file
- Remove installer routes (verify redirect)
- Display success message

### 9. ReInstallPrevention.spec.ts (Security)
- Redirect to admin login when already installed
- Block access to `/installer` after completion
- Verify middleware protection

---

## 🏷️ Frontend: data-testid Convention

**Naming Pattern:**
- **Inputs:** `{context}-{field}-input` (e.g., `admin-email-input`, `env-app-name`)
- **Buttons:** `btn-{action}` (e.g., `btn-next-step`, `btn-create-admin`) <!-- cspell:disable-line -->
- **Status/Messages:** `{context}-{type}` (e.g., `admin-success`, `password-strength`)
- **Checks:** `check-{name}` (e.g., `check-php-version`, `check-extensions`)

**Example (AdminUser.vue):**
```vue
<input data-testid="admin-name-input" v-model="form.name" />
<input data-testid="admin-email-input" v-model="form.email" />
<input data-testid="admin-password-input" v-model="form.password" />
<div data-testid="password-strength">{{ passwordStrength }}</div>
<!-- cspell:disable-next-line -->
<button data-testid="btn-next-substep">Next</button>
<!-- cspell:disable-next-line -->
<button data-testid="btn-create-admin">Create Admin</button>
<div data-testid="admin-success">Success!</div>
```

**All 7 Installer Views need testid attributes:**
- `Requirements.vue`
- `Environment.vue`
- `Database.vue`
- `AdminUser.vue`
- `WebServer.vue`
- `Scheduler.vue`
- `Cleanup.vue`

---

## 🔄 Test Execution Flow

### Single Test
```bash
npx playwright test tests/E2E/Installer/CompleteFlow.spec.ts
```

### All Installer Tests
```bash
npm run test:e2e
```

### Interactive UI Mode
```bash
npm run test:e2e:ui
```

### Debug Mode
```bash
npx playwright test --debug
```

### Cross-Browser (Local)
- Chromium, Firefox, WebKit run in parallel
- CI: Chromium only for speed

---

## ✅ Success Criteria

- [ ] All E2E tests pass on Chromium, Firefox, WebKit
- [ ] Test execution time < 10 minutes
- [ ] No flaky tests (consistent pass rate)
- [ ] Screenshots captured on failures
- [ ] HTML report generated
- [ ] Installation success rate > 95% (measured via tests)

---

## 📦 Implementation Checklist

### Backend
- [ ] Create `UnlockController` with environment guard
- [ ] Add route `POST /installer/unlock`
- [ ] Test unlock endpoint (Feature test)

### Frontend
- [ ] Add `data-testid` to all 7 Installer views
- [ ] Follow naming convention consistently
- [ ] Test manually that testids exist

### Tests
- [ ] Create `TestCleanupHelper.ts`
- [ ] Create `InstallerHelper.ts` with all workflow methods
- [ ] Write 9 test spec files
- [ ] Verify all tests pass locally

### CI/CD
- [ ] Verify GitHub Actions workflow runs E2E tests
- [ ] Upload test artifacts (screenshots, videos, reports)

---

## 🚀 Next Steps

1. **Create Git Worktree:** `git worktree add ../pinecms-task-008 -b task-008-installer-e2e-tests`
2. **Write Implementation Plan:** Detailed step-by-step breakdown
3. **TDD Workflow:** RED → GREEN → REFACTOR for each test
4. **Code Review:** Use `code-reviewer` agent after implementation
5. **Verification:** Run full quality suite before PR

---

**Design Status:** ✅ Validated
**Ready for Implementation:** Yes
**Estimated Effort:** 4 hours (per task spec)
