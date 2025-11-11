# Test Architecture - AdminUserWizard E2E Tests

## Test Pyramid

```
                    /\
                   /  \
                  / E2E \ ← AdminUserWizard.spec.ts
                 /------\   (27 tests, 10%)
                /  INT   \
               /----------\  (20%, Integration tests)
              /   UNIT     \
             /--------------\ (70%, Unit tests)
```

**This test suite**: E2E layer (highest level, tests full user flow)

## System Under Test

```
┌─────────────────────────────────────────────────────────────┐
│                    AdminUserWizard Flow                     │
└─────────────────────────────────────────────────────────────┘

┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   Step 1     │    │   Step 2     │    │   Step 3     │
│              │    │              │    │              │
│ Name: ___    │───▶│ Password:    │───▶│ Review:      │
│              │    │  ********    │    │              │
│ Email: ___   │    │              │    │ Name: John   │
│              │    │ Confirm:     │    │ Email: @..   │
│  [ Next ]    │    │  ********    │    │ Role: Admin  │
│              │    │              │    │              │
│              │    │ [Back][Next] │    │ [Back][Submit]
└──────────────┘    └──────────────┘    └──────────────┘
       │                   │                    │
       │                   │                    │
       └───────────────────┴────────────────────┘
                           │
                           ▼
                 POST /installer/admin-user
                           │
                           ▼
                 Redirect /admin/login
```

## Test File Organization

```
tests/E2E/Installer/AdminUserWizard.spec.ts
├── Imports & Setup
│   ├── Playwright test functions
│   ├── Type definitions
│   └── Test data constants
│
├── AdminUserWizardPage (Page Object)
│   ├── Selectors (get properties)
│   │   ├── Step 1: nameInput, emailInput, nextButton
│   │   ├── Step 2: passwordInput, strengthLabel, matchMessage
│   │   └── Step 3: submitButton, reviewName, reviewEmail
│   │
│   └── Actions (async methods)
│       ├── fillUserInfo(name, email)
│       ├── fillPasswords(password, confirmation)
│       ├── goToPasswordStep()
│       └── goToReviewStep(password?)
│
├── Test Suite 1: Complete Flow (2 tests)
│   ├── beforeEach: Reset state + navigate
│   ├── Test: Display wizard header
│   ├── Test: Complete full flow (happy path) ★
│   └── Test: Navigate with Back button
│
├── Test Suite 2: Step 1 Validation (5 tests)
│   ├── beforeEach: Reset state + navigate
│   ├── Test: Disable Next with empty fields
│   ├── Test: Disable Next with invalid email
│   ├── Test: Enable Next with valid data
│   ├── Test: Validate common email formats
│   └── Test: Reject invalid email formats
│
├── Test Suite 3: Step 2 Password Validation (9 tests)
│   ├── beforeEach: Reset state + navigate to Step 2
│   ├── Test: Disable Next with weak password
│   ├── Test: Disable Next with short password
│   ├── Test: Show "Passwords do not match" message
│   ├── Test: Show "Passwords match" message
│   ├── Test: Call password strength API with debouncing ★
│   ├── Test: Display password strength indicator
│   ├── Test: Enable Next only with strong password
│   ├── Test: Show password requirements list
│   └── Test: Toggle password visibility
│
├── Test Suite 4: Step 3 Review and Submit (6 tests)
│   ├── beforeEach: Reset state + navigate to Step 3
│   ├── Test: Display all entered information
│   ├── Test: Display redirect information message
│   ├── Test: Show Back and Submit buttons
│   ├── Test: Submit form and redirect ★
│   ├── Test: Show loading state during submission
│   └── Test: Display server validation errors
│
├── Test Suite 5: Progress Bar (1 test)
│   ├── beforeEach: Reset state + navigate
│   └── Test: Update progress bar as user navigates ★
│
└── Test Suite 6: Accessibility (4 tests)
    ├── beforeEach: Reset state + navigate
    ├── Test: Have proper form labels
    ├── Test: Support keyboard navigation
    └── Test: Have autofocus on name field

★ = Critical path tests
```

## Page Object Pattern

```
┌────────────────────────────────────────────────────────┐
│          AdminUserWizardPage (Helper Class)            │
├────────────────────────────────────────────────────────┤
│                                                        │
│  Selectors (getters)                                   │
│  ├── nameInput: locator('input#name')                 │
│  ├── emailInput: locator('input#email')               │
│  ├── passwordInput: locator('input#password')         │
│  └── submitButton: getByRole('button', 'Create...')   │
│                                                        │
│  Actions (methods)                                     │
│  ├── async fillUserInfo(name, email)                  │
│  ├── async fillPasswords(password, confirmation)      │
│  ├── async goToPasswordStep()                         │
│  └── async goToReviewStep(password?)                  │
│                                                        │
└────────────────────────────────────────────────────────┘
                          │
                          │ Used by
                          ▼
┌────────────────────────────────────────────────────────┐
│                   Test Suites                          │
├────────────────────────────────────────────────────────┤
│                                                        │
│  test.beforeEach(async ({ page }) => {                │
│      wizardPage = new AdminUserWizardPage(page);      │
│  });                                                   │
│                                                        │
│  test('should do something', async () => {            │
│      await wizardPage.fillUserInfo('John', '@...');   │
│      await wizardPage.nextButton.click();             │
│      await expect(wizardPage.reviewName)              │
│          .toHaveText('John');                         │
│  });                                                   │
│                                                        │
└────────────────────────────────────────────────────────┘
```

## Test Isolation Strategy

```
Each Test Execution:
┌────────────────────────────────────┐
│  test.beforeEach()                 │
│  ├── POST /installer/unlock        │ ← Reset DB/state
│  ├── goto('/installer/admin-user') │ ← Navigate to page
│  ├── waitForLoadState('networkidle')│ ← Wait for ready
│  └── wizardPage = new ...()        │ ← Initialize helper
└────────────────────────────────────┘
            │
            ▼
┌────────────────────────────────────┐
│  test('actual test case')          │
│  ├── Arrange (setup test data)     │
│  ├── Act (perform actions)         │
│  └── Assert (verify outcomes)      │
└────────────────────────────────────┘
            │
            ▼
┌────────────────────────────────────┐
│  Test Artifacts (on failure)       │
│  ├── Screenshot                    │
│  ├── Video                         │
│  ├── Trace                         │
│  └── Console logs                  │
└────────────────────────────────────┘

Result: Tests are independent and can run in any order
```

## Async Operations Handling

### Password Strength API (500ms Debounce)

```
User Types Password:
─────────────────────────────────────────────────▶ Time

T+0ms:    Type 'S'
T+50ms:   Type 'e'
T+100ms:  Type 'c'
T+200ms:  Type 'u'
T+300ms:  Type 'r'
T+400ms:  Type 'e'
T+500ms:  Type '1'
T+600ms:  Type '2'
T+700ms:  Type '3'
T+800ms:  Type '!'
T+1300ms: ────────────┐
                      │ 500ms after last keystroke
                      ▼
                POST /installer/admin-user/check-password
                      │
                      ▼
                { strength: { score: 4, label: "Good", color: "lime" } }
                      │
                      ▼
                Update UI strength indicator
                      │
                      ▼
                Enable Next button (if score >= 4)

Test Approach:
1. Fill password field
2. page.waitForResponse('/check-password') ← Explicit wait
3. Verify strength label updated
4. Check button state
```

### Inertia.js Form Submission

```
Submit Button Clicked:
─────────────────────────────────────────────────▶ Time

T+0ms:    Click "Create Admin Account"
          │
          ▼
        form.processing = true  (button disabled)
          │
          ▼
        POST /installer/admin-user
          │
          ├─────────▶ Success (200)
          │           │
          │           ▼
          │         router.visit('/admin/login')
          │           │
          │           ▼
          │         Inertia redirect
          │           │
          │           ▼
          │         Load /admin/login
          │
          └─────────▶ Error (422)
                      │
                      ▼
                    form.errors = { ... }
                      │
                      ▼
                    Display error messages

Test Approach:
1. Click submit button
2. page.waitForURL('/admin/login', { timeout: 10000 })
3. expect(page.url()).toContain('/admin/login')
```

## Selector Strategy

### Priority Order

```
1. Role-based (BEST):
   page.getByRole('button', { name: 'Next' })
   ↑ Most resilient, semantic, accessible

2. Label-based (GOOD):
   page.getByLabel('Email Address')
   ↑ User-facing, stable

3. Text-based (GOOD):
   page.getByText('Passwords match')
   ↑ User-facing, but might change

4. Test ID (OKAY):
   page.getByTestId('password-strength')
   ↑ Stable, but requires adding data-testid

5. ID selector (OKAY):
   page.locator('input#email')
   ↑ Works, but less semantic

6. CSS selector (AVOID):
   page.locator('.p-inputtext.w-full')
   ↑ Fragile, implementation detail
```

### PrimeVue Components

```
PrimeVue Password Component:
<Password id="password" v-model="..." toggle-mask />

Rendered HTML:
<span class="p-password ...">
  <input id="password" type="password" />
  <button type="button" class="p-password-toggle-mask-icon">
    <i class="pi pi-eye"></i>
  </button>
</span>

Test Selector:
const passwordInput = page.locator('input#password')
const toggleButton = page.locator('#password')
                          .locator('..')
                          .locator('button[type="button"]')
                          .first()

Strategy: Use ID for input, traverse DOM for related elements
```

## Data Flow

```
┌──────────────┐
│   Browser    │
│  (Playwright)│
└──────┬───────┘
       │ HTTP
       ▼
┌──────────────┐
│  Laravel App │
│   (Web.php)  │
└──────┬───────┘
       │
       ├──────────────────────┬──────────────────────┐
       │                      │                      │
       ▼                      ▼                      ▼
┌────────────┐      ┌──────────────┐      ┌──────────────┐
│ Controller │      │   Service    │      │  Validation  │
│            │─────▶│              │◀─────│  (Form Req)  │
└────────────┘      └──────┬───────┘      └──────────────┘
                           │
                           ▼
                    ┌──────────────┐
                    │   Database   │
                    │   (SQLite)   │
                    └──────────────┘
                           │
                           ▼
                    ┌──────────────┐
                    │    Inertia   │
                    │   Response   │
                    └──────┬───────┘
                           │
                           ▼
                    ┌──────────────┐
                    │ Vue Component│
                    │  (Rendered)  │
                    └──────────────┘

Test intercepts at:
1. HTTP layer (page.request, page.goto)
2. API responses (page.waitForResponse)
3. DOM layer (locators, assertions)
4. Navigation (page.waitForURL)
```

## Test Execution Flow

```
npm run test:e2e:installer
        │
        ▼
┌───────────────────────────────────┐
│  Playwright Test Runner           │
└───────────────────────────────────┘
        │
        ├──────────────┬──────────────┬──────────────┐
        │              │              │              │
        ▼              ▼              ▼              ▼
   [Chromium]     [Firefox]      [WebKit]      (Local)
        │              │              │
        │              │              │
        └──────────────┴──────────────┘
                       │
                       ▼
┌───────────────────────────────────┐
│  Test Suite Execution             │
│  ├── Suite 1: Complete Flow       │
│  │   ├── Test 1.1 (Pass)         │
│  │   ├── Test 1.2 (Pass)         │
│  │   └── Test 1.3 (Pass)         │
│  ├── Suite 2: Step 1 Validation   │
│  │   └── ... (5 tests)           │
│  ├── Suite 3: Step 2 Validation   │
│  │   └── ... (9 tests)           │
│  └── ...                          │
└───────────────────────────────────┘
                       │
                       ▼
┌───────────────────────────────────┐
│  Artifacts Generation             │
│  ├── Screenshots (failures)       │
│  ├── Videos (failures)            │
│  ├── Traces (retries)             │
│  └── HTML Report                  │
└───────────────────────────────────┘
                       │
                       ▼
┌───────────────────────────────────┐
│  Test Results                     │
│  ├── Exit Code: 0 (success)       │
│  ├── Duration: 45s                │
│  ├── Tests: 27 passed, 0 failed   │
│  └── Report: storage/playwright/  │
└───────────────────────────────────┘
```

## Configuration Layers

```
┌────────────────────────────────────────────────────────┐
│  playwright.config.js (Root)                           │
│  ├── testDir: './tests/E2E'                            │
│  ├── baseURL: 'http://localhost:8000'                  │
│  ├── timeout: 30000                                    │
│  ├── projects: [chromium, firefox, webkit]             │
│  └── webServer: { command: 'php artisan serve' }       │
└────────────────────────────────────────────────────────┘
                          │
                          ▼
┌────────────────────────────────────────────────────────┐
│  AdminUserWizard.spec.ts (Test File)                   │
│  ├── Test constants (VALID_ADMIN, etc.)                │
│  ├── Page object class (AdminUserWizardPage)           │
│  ├── Test suites (describe blocks)                     │
│  └── Individual tests (test blocks)                    │
└────────────────────────────────────────────────────────┘
                          │
                          ▼
┌────────────────────────────────────────────────────────┐
│  Test Execution Context                                │
│  ├── Browser context (isolated)                        │
│  ├── Page instance (isolated)                          │
│  ├── Network interception                              │
│  └── Artifacts collection                              │
└────────────────────────────────────────────────────────┘
```

## CI/CD Integration

```
GitHub Actions Workflow:
┌────────────────────────────────────────────────────────┐
│  1. Checkout code                                      │
│  2. Setup Node.js                                      │
│  3. Install dependencies (npm ci)                      │
│  4. Install Playwright browsers                        │
│  5. Setup database (SQLite)                            │
│  6. Run Laravel migrations                             │
│  7. Start Laravel server (background)                  │
│  8. Run E2E tests (npm run test:e2e:installer)         │
│  9. Upload test artifacts (always)                     │
│ 10. Generate test report                               │
└────────────────────────────────────────────────────────┘

Playwright Config (CI Mode):
- projects: [chromium] only (faster)
- retries: 2 (handle flakiness)
- workers: 4 (parallel execution)
- video: retain-on-failure
- trace: on-first-retry
```

## Performance Characteristics

```
Test Execution Times (Estimated):

Single Browser (Chromium):
├── Complete Flow (2 tests):        ~15s
├── Step 1 Validation (5 tests):    ~10s
├── Step 2 Validation (9 tests):    ~25s
├── Step 3 Review (6 tests):        ~15s
├── Progress Bar (1 test):          ~5s
└── Accessibility (4 tests):        ~8s
                                    ─────
Total:                              ~78s

All Browsers (3x):
Total:                              ~3.5 min (parallel)

Optimization:
- Run in parallel (fullyParallel: true)
- Single browser in CI (chromium only)
- Reuse server (reuseExistingServer: true)
- Smart waiting (no arbitrary sleeps)
```

## Maintenance Strategy

### When Component Changes

```
UI Change                      Required Action
───────────────────────────────────────────────────────────
Add new field                  Add selector + test
Remove field                   Remove selector + test
Change label text              Update getByLabel() call
Change button text             Update getByRole() call
Add validation rule            Add validation test
Change API endpoint            Update waitForResponse() URL
Add new step                   Extend page object + tests
Modify component library       Update selector strategy
```

### Test Maintenance Checklist

- [ ] Update page object selectors (single source of truth)
- [ ] Update test data constants (if validation rules change)
- [ ] Update test assertions (if expected behavior changes)
- [ ] Update documentation (README, SUMMARY, this file)
- [ ] Run tests to verify changes
- [ ] Update CI/CD config (if needed)

---

**Visual Legend:**

```
┌─────┐
│ Box │   = Component/System
└─────┘

  │
  ▼       = Flow/Direction

  ├──▶    = Branch/Option

  ★       = Critical/Important

[Item]    = Optional/Alternative
```

**Last Updated**: 2025-11-11
**Maintained By**: Test Engineer
**Status**: Active
