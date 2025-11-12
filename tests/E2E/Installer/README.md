# Installer E2E Tests

This directory contains Playwright E2E tests for the PineCMS installer flow.

## Test Files

- **AdminUserWizard.spec.ts** - Comprehensive E2E tests for the 3-step admin user creation wizard

## Running Tests

### All Installer Tests

```bash
# Run all installer E2E tests
npx playwright test tests/E2E/Installer

# Run with UI mode (recommended for development)
npx playwright test tests/E2E/Installer --ui

# Run in headed mode (see browser)
npx playwright test tests/E2E/Installer --headed
```

### Specific Test File

```bash
# Run only AdminUserWizard tests
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts

# Run specific test by name
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts -g "should complete full wizard flow"
```

### Browser Selection

```bash
# Run on specific browser
npx playwright test tests/E2E/Installer --project=chromium
npx playwright test tests/E2E/Installer --project=firefox
npx playwright test tests/E2E/Installer --project=webkit

# Run on all browsers
npx playwright test tests/E2E/Installer
```

### Debug Mode

```bash
# Run with Playwright Inspector (step-by-step debugging)
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts --debug

# Run specific test in debug mode
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts --debug -g "happy path"
```

### Test Reports

```bash
# View HTML report after test run
npx playwright show-report storage/playwright/html-report
```

## Test Structure

### AdminUserWizard.spec.ts

Tests are organized into the following test suites:

1. **Complete Flow** - End-to-end happy path and navigation tests
    - Full wizard completion with valid data
    - Back button navigation between steps
    - Form data preservation

2. **Step 1 Validation** - User information validation
    - Empty field validation
    - Email format validation (valid/invalid formats)
    - Next button state management

3. **Step 2 Password Validation** - Password strength and matching
    - Weak password rejection
    - Short password (< 12 chars) rejection
    - Password mismatch detection
    - Password strength API integration
    - Debounced API calls
    - Password visibility toggle
    - Strength indicator display

4. **Step 3 Review and Submit** - Final review and submission
    - Information display verification
    - Form submission and redirect
    - Loading state during submission
    - Server-side validation error handling

5. **Progress Bar** - Visual progress tracking
    - Progress updates across steps

6. **Accessibility** - A11y compliance
    - Form labels
    - Keyboard navigation
    - Autofocus behavior

## Test Isolation

Each test uses `test.beforeEach()` to:

1. Reset installation state via `POST /installer/unlock`
2. Navigate to `/installer/admin-user`
3. Wait for page to be fully loaded

This ensures tests are independent and can run in any order.

## Page Object Pattern

Tests use an `AdminUserWizardPage` helper class that provides:

- Semantic selectors for all UI elements
- Reusable action methods (`fillUserInfo`, `goToPasswordStep`, etc.)
- Consistent element access patterns

## Key Testing Patterns

### Password Strength API Testing

```typescript
// Wait for debounced API call (500ms debounce)
await page.waitForResponse(
    (response) => response.url().includes('/installer/admin-user/check-password') && response.status() === 200,
);
```

### Inertia Navigation Testing

```typescript
// Wait for Inertia redirect
await page.waitForURL('/admin/login', { timeout: 10000 });
expect(page.url()).toContain('/admin/login');
```

### PrimeVue Component Interaction

```typescript
// Password component with toggle mask
const toggleButton = page.locator('#password').locator('..').locator('button[type="button"]').first();
await toggleButton.click();
```

## Test Data

Consistent test data is defined in constants:

```typescript
const VALID_ADMIN = {
    name: 'Test Admin',
    email: 'admin@test.local',
    password: 'SecureTestPass123!',
};
```

## Troubleshooting

### Tests fail with "Cannot connect to server"

Ensure Laravel development server is running:

```bash
php artisan serve
```

Or let Playwright start it automatically (configured in `playwright.config.js`).

### Tests fail with "Installation already complete"

Reset installation state:

```bash
curl -X POST http://localhost:8000/installer/unlock
```

Or the test's `beforeEach` hook should handle this automatically.

### Password strength tests fail intermittently

This may indicate:

- Network latency issues (increase timeout)
- API rate limiting (check backend logs)
- Debounce timing issues (tests already account for 500ms debounce)

### Screenshots and videos

Failed tests automatically capture:

- Screenshots: `storage/playwright/test-results/`
- Videos: `storage/playwright/test-results/` (on failure only)
- Traces: Available in HTML report

## CI/CD Integration

Tests are configured to run in CI with:

- Chromium only (faster execution)
- 2 retries on failure
- Automatic artifact upload (reports, screenshots, videos)

See `.github/workflows/` for CI configuration.

## Best Practices

1. Always use semantic selectors (role, label, text) over CSS selectors
2. Wait for API responses explicitly when testing async operations
3. Use `expect().toBeEnabled()` with timeout for dynamically enabled buttons
4. Test both happy path AND validation/error scenarios
5. Keep tests independent - each test should be runnable in isolation

## Coverage

Current test coverage:

- 27 test scenarios
- All 3 wizard steps
- Happy path + validation paths
- Accessibility checks
- Cross-browser compatibility (Chrome, Firefox, Safari)
