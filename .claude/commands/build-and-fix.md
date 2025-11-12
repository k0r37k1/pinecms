# Build and Fix Command

Run complete build checks and systematically fix ALL errors.

## Important Rules

🚨 **CRITICAL:**

- ❌ DO NOT proceed to next error until current is fixed and verified
- ❌ DO NOT skip any errors
- ❌ DO NOT mark todo as complete until error is verified fixed
- ❌ DO NOT batch fixes - Fix one, verify, repeat
- ✅ Report progress after each fix
- ✅ Re-run specific check after fixing related errors
- ✅ Use TodoWrite to track each error as a separate task

## Step 1: Backend Quality Checks

Run all backend checks and collect errors:

### PHPStan Analysis (Level 8)

```bash
composer analyse
```

**Parse output:**

- Count total errors
- Extract file paths and line numbers
- Group by error type (if helpful)

### Laravel Pint (Code Style)

```bash
vendor/bin/pint --test
```

**Parse output:**

- Identify files with style violations
- Note specific violations

### PHPUnit Tests

```bash
composer test
```

**Parse output:**

- List failed tests
- Extract failure messages and stack traces
- Identify affected files

## Step 2: Frontend Quality Checks

Run all frontend checks and collect errors:

### TypeScript Type Checking

```bash
npm run type-check
```

**Parse output:**

- Count TypeScript errors
- Extract file paths and line numbers
- Group by error type

### ESLint

```bash
npm run lint
```

**Parse output:**

- Count linting errors
- Extract file paths and violations

### Vitest Tests

```bash
npm test
```

**Parse output:**

- List failed tests
- Extract failure messages
- Identify affected files

## Step 3: Error Analysis & TodoWrite

After collecting ALL errors:

1. **Categorize errors:**
    - Critical (blocking, security issues)
    - High (test failures, type errors)
    - Medium (code style, linting)
    - Low (minor improvements)

2. **Create TodoWrite tasks:**

    Create ONE todo per error (or logical group of related errors):

    ```markdown
    Task: Fix PHPStan error in UserService.php:45 - Property $name has no type
    Task: Fix TypeScript error in LoginForm.vue:23 - Type 'string | undefined' not assignable
    Task: Fix failing test: UserServiceTest::testCreateUser
    ```

3. **Prioritize:**
    - Fix critical/high priority first
    - Fix by file (all errors in one file together)
    - OR fix by category (all type errors, then style, etc.)

## Step 4: Systematic Fixing

For EACH error (one at a time):

1. **Mark todo as in_progress**
2. **Read the affected file**
3. **Understand the error** (use `search-docs` if needed)
4. **Fix the error** (use Edit tool)
5. **Re-run the specific check:**
    - PHPStan error? → `composer analyse`
    - TypeScript error? → `npm run type-check`
    - Test failure? → Run specific test file
6. **Verify fix worked:**
    - If fixed → Mark todo as completed
    - If not fixed → Analyze why, fix again
7. **Move to next error**

### Example Fix Flow

```
Error: PHPStan - Property UserService::$repository has no type
File: app/Services/UserService.php:45

1. Read app/Services/UserService.php
2. Find line 45: private $repository;
3. Fix: private UserRepository $repository;
4. Run: composer analyse
5. Verify: Error gone? ✅
6. Mark todo completed
7. Next error...
```

## Step 5: Progressive Verification

After fixing errors in each category, re-run that category's checks:

- Fixed all PHPStan errors? → Run `composer analyse` → Should show 0 errors
- Fixed all TypeScript errors? → Run `npm run type-check` → Should pass
- Fixed all tests? → Run full test suite → All green

## Step 6: Final Verification

After ALL errors are fixed:

### Run Complete Quality Check

```bash
composer quality  # Backend: format, analyze, test
npm run quality   # Frontend: format, lint, type-check, test
```

### Verify Zero Errors

Both commands should complete successfully with:

- ✅ 0 PHPStan errors
- ✅ 0 TypeScript errors
- ✅ 0 style violations (auto-fixed by Pint/Prettier)
- ✅ 0 test failures

### Create Test Marker File

If all checks pass, create marker for test-enforcement hook:

```bash
touch /tmp/pinecms-tests-passed
```

This allows future commits to proceed (if test-enforcement hook is enabled).

## Output Format

### Initial Error Summary

```markdown
## Build Check Results

### Backend Errors

❌ PHPStan: 5 errors found
❌ Pint: 3 files with style violations
❌ PHPUnit: 2 tests failing

### Frontend Errors

❌ TypeScript: 8 errors found
❌ ESLint: 4 linting errors
❌ Vitest: 1 test failing

### Total: 23 errors to fix

## Error Details

### Critical/High Priority

1. [PHPStan] app/Services/UserService.php:45 - Property $repository has no type
2. [TypeScript] resources/js/Pages/Login.vue:23 - Type error
3. [Test] tests/Feature/UserServiceTest.php - testCreateUser failing

### Medium Priority

4. [Pint] app/Http/Controllers/UserController.php - Style violations
   ...

Creating 23 TodoWrite tasks...
```

### Progress Reports (After Each Fix)

```markdown
✅ Fixed 1/23: PHPStan error in UserService.php:45

- Added type hint: private UserRepository $repository;
- Verified: composer analyse passes for this file
- Moving to next error...
```

### Final Summary

```markdown
## Build and Fix Complete! 🎉

### Summary

✅ Fixed 23/23 errors
✅ All quality checks passing
✅ Test marker file created

### Verification Results

✅ composer quality - PASS
✅ npm run quality - PASS

### Changed Files

- app/Services/UserService.php
- app/Http/Controllers/UserController.php
- resources/js/Pages/Login.vue
- tests/Feature/UserServiceTest.php

Ready to commit!
```

## PineCMS-Specific Fixes

### Common PHPStan Errors

**Missing type hints:**

```php
// ❌ Before
private $repository;

// ✅ After
private UserRepository $repository;
```

**Missing declare(strict_types=1):**

```php
// ✅ Add at top of every PHP file
<?php

declare(strict_types=1);
```

**Missing return type:**

```php
// ❌ Before
public function getUser()

// ✅ After
public function getUser(): User
```

### Common TypeScript Errors

**Missing type annotations:**

```typescript
// ❌ Before
const user = ref();

// ✅ After
const user = ref<User | null>(null);
```

**Prop types in Vue:**

```typescript
// ❌ Before
const props = defineProps(['user']);

// ✅ After
interface Props {
    user: User;
}
const props = defineProps<Props>();
```

### Common Test Failures

**Missing RefreshDatabase:**

```php
// ✅ Add to Feature tests
use RefreshDatabase;
```

**Database not reset between tests:**

```php
// ✅ In setUp()
protected function setUp(): void
{
    parent::setUp();
    $this->artisan('migrate:fresh');
}
```

## Notes

- Use `/quality` command for quick quality checks without fixing
- Use `/check` command for pre-commit checks
- This command is for systematic error resolution, not quick checks
- Expected time: 2-5 minutes per error (depends on complexity)

---

**Usage:** `/build-and-fix`
