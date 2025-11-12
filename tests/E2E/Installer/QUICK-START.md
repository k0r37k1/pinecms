# Quick Start - AdminUserWizard E2E Tests

## TL;DR - Run Tests Now

```bash
# Run all installer tests with UI (RECOMMENDED for first time)
npm run test:e2e:installer:ui

# Run all installer tests (headless)
npm run test:e2e:installer

# Run just AdminUserWizard tests
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts

# Debug a specific test
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts --debug -g "happy path"
```

## Prerequisites

1. **Server Running**: Make sure Laravel server is running on `http://localhost:8000`

    ```bash
    php artisan serve
    ```

    (Or Playwright will auto-start it per `playwright.config.js`)

2. **Installation Unlocked**: Tests automatically reset state via `POST /installer/unlock`

3. **Node Dependencies**: Already installed if you have Playwright
    ```bash
    npm install  # if needed
    ```

## What Gets Tested

27 comprehensive tests covering:

- **Happy Path**: Complete 3-step wizard flow
- **Step 1**: Name/email validation, email format checks
- **Step 2**: Password strength, matching, API debouncing, visibility toggle
- **Step 3**: Review, submit, redirect to `/admin/login`
- **Navigation**: Back button, form data persistence
- **Accessibility**: Labels, keyboard nav, autofocus
- **Progress Bar**: Visual feedback

## Quick Commands

### Development

```bash
# UI Mode (best for development)
npm run test:e2e:installer:ui

# Headed mode (see browser)
npx playwright test tests/E2E/Installer --headed

# Watch mode
npx playwright test tests/E2E/Installer --watch

# Debug mode (step through tests)
npm run test:e2e:debug tests/E2E/Installer/AdminUserWizard.spec.ts
```

### Specific Tests

```bash
# Run one test by name
npx playwright test -g "should complete full wizard flow"

# Run all Step 2 validation tests
npx playwright test -g "Step 2 Password Validation"

# Run accessibility tests only
npx playwright test -g "Accessibility"
```

### Browser Selection

```bash
# Chromium only (fastest)
npx playwright test tests/E2E/Installer --project=chromium

# Firefox only
npx playwright test tests/E2E/Installer --project=firefox

# WebKit/Safari only
npx playwright test tests/E2E/Installer --project=webkit

# All browsers (default)
npx playwright test tests/E2E/Installer
```

### Reports

```bash
# View last HTML report
npx playwright show-report storage/playwright/html-report

# Generate report after headless run
npm run test:e2e:installer && npx playwright show-report storage/playwright/html-report
```

## Test Results Location

After running tests, find artifacts here:

```
storage/playwright/
├── html-report/           # HTML report (open index.html)
├── test-results/          # Screenshots, videos, traces
│   └── <test-name>/
│       ├── screenshot.png
│       ├── video.webm
│       └── trace.zip
├── test-results.json      # JSON results
└── junit.xml              # JUnit XML (CI/CD)
```

## Common Issues

### "Cannot connect to server"

**Problem**: Tests can't reach `http://localhost:8000`

**Solution**:

```bash
# Start Laravel server manually
php artisan serve

# Or let Playwright start it (configured in playwright.config.js)
```

### "Installation already complete"

**Problem**: Installation state not reset

**Solution**: Tests handle this automatically via `beforeEach()` hook calling `POST /installer/unlock`

**Manual Reset**:

```bash
curl -X POST http://localhost:8000/installer/unlock
```

### Tests Fail Intermittently

**Problem**: Race conditions, timing issues

**Solution**: Tests already handle:

- 500ms password strength API debounce
- Waiting for API responses
- Proper element state checks

**Debug**:

```bash
# Run with debug to see step-by-step
npx playwright test tests/E2E/Installer/AdminUserWizard.spec.ts --debug

# Increase timeout (if needed)
npx playwright test --timeout=60000
```

### Password Strength Tests Fail

**Problem**: Backend API not responding correctly

**Check**:

1. Backend endpoint exists: `POST /installer/admin-user/check-password`
2. Returns JSON: `{ "strength": { "score": 0-5, "label": "...", "color": "..." } }`
3. No rate limiting blocking requests

### TypeScript Errors

**Problem**: Type check fails

**Solution**:

```bash
# Check TypeScript
npm run type-check

# Should show no errors in AdminUserWizard.spec.ts
```

## Test Structure

```typescript
// Page Object Pattern
class AdminUserWizardPage {
    // Semantic selectors
    get nameInput() { return this.page.locator('input#name'); }
    get emailInput() { return this.page.locator('input#email'); }

    // Reusable actions
    async fillUserInfo(name, email) { ... }
    async goToPasswordStep() { ... }
    async goToReviewStep() { ... }
}

// Test suite
test.describe('AdminUserWizard - Complete Flow', () => {
    test.beforeEach(async ({ page }) => {
        // Reset state + navigate
        await page.request.post('/installer/unlock');
        await page.goto('/installer/admin-user');
    });

    test('should complete full wizard flow', async ({ page }) => {
        // Arrange, Act, Assert
    });
});
```

## CI/CD Integration

Tests are CI-ready:

```yaml
# .github/workflows/test.yml (example)
- name: Run E2E Tests
  run: npm run test:e2e:installer

- name: Upload Test Results
  if: always()
  uses: actions/upload-artifact@v3
  with:
      name: playwright-report
      path: storage/playwright/html-report
```

## Next Steps

1. **Run Tests**: Start with UI mode to see what's happening

    ```bash
    npm run test:e2e:installer:ui
    ```

2. **Review Results**: Check HTML report for detailed results

    ```bash
    npx playwright show-report storage/playwright/html-report
    ```

3. **Fix Failures**: Use debug mode to investigate failures

    ```bash
    npm run test:e2e:debug tests/E2E/Installer/AdminUserWizard.spec.ts
    ```

4. **Add More Tests**: Follow the pattern in `AdminUserWizard.spec.ts`

## Documentation

- **README.md** - Comprehensive guide with all details
- **SUMMARY.md** - Test coverage and architecture overview
- **QUICK-START.md** - This file (you are here)

## Success Criteria

Tests pass when:

- All 27 tests pass (27/27)
- No screenshots in test-results (no failures)
- HTML report shows all green
- Time: < 2 minutes (headless, single browser)

## Support

Issues? Check these resources:

1. **README.md** - Troubleshooting section
2. **Playwright Docs** - https://playwright.dev/docs/intro
3. **Component Code** - `resources/js/Pages/Installer/AdminUserWizard.vue`
4. **Backend Routes** - `routes/web.php` (installer routes)

---

**Ready to test?**

```bash
npm run test:e2e:installer:ui
```

Happy testing! 🧪
