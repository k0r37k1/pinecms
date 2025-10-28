# Quality Check Commands

## All-in-One Quality Check

**Run before EVERY commit:**

```bash
# PHP Quality Check
composer quality

# JavaScript Quality Check
npm run quality
```

## Individual PHP Commands

```bash
# Format code with Laravel Pint
composer format
vendor/bin/pint
vendor/bin/pint --dirty  # Only changed files

# Static Analysis with PHPStan (Level 8)
composer analyse
vendor/bin/phpstan analyse

# Run PHPUnit tests
composer test
php artisan test
php artisan test --filter=testName

# Generate test coverage
php artisan test --coverage
```

## Individual JavaScript Commands

```bash
# Format with Prettier
npm run format
npx prettier --write .

# Lint with ESLint
npm run lint
npm run lint:fix  # Auto-fix issues

# Type check with TypeScript
npm run type-check

# Run Vitest tests
npm test
npm run test:coverage

# Run Playwright E2E tests
npm run test:e2e
npm run test:e2e:ui  # Interactive mode
```

## Pre-Commit Checklist

✅ Code formatted (Pint + Prettier)
✅ No linting errors (PHPStan + ESLint)
✅ All tests passing (PHPUnit + Vitest)
✅ Type checking passed (TypeScript)
✅ No console.log() statements
✅ No debug code left

## Quality Gate Enforcement

**MANDATORY before finalizing:**
```bash
composer quality && npm run quality
```

**If ANY check fails:**
1. Fix the issues
2. Re-run quality checks
3. Only proceed when ALL checks pass
