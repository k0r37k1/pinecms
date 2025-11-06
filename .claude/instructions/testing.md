# Testing Instructions

## Testing Tools

- **Backend:** PHPUnit (`php artisan test`)
- **Frontend:** Vitest (`npm test`)
- **E2E:** Playwright (`npm run test:e2e`)

## PHPUnit Testing Rules

### Critical Rules

- **NEVER remove tests without approval** - Tests are NOT temporary files!
- Use `php artisan make:test --phpunit <name>` to create tests
- If you see Pest syntax, convert to PHPUnit
- Every feature requires tests (unit or feature tests)

### Test Coverage Requirements

**Test ALL paths:**

- ✅ Happy paths (expected success scenarios)
- ✅ Failure paths (validation errors, exceptions)
- ✅ Edge cases (empty data, boundary conditions)

### Running Tests

**Run minimal tests using filters:**

```bash
# Run all tests
php artisan test

# Run specific file
php artisan test tests/Feature/ExampleTest.php

# Filter by test name (recommended after changes)
php artisan test --filter=testName
```

**Best Practice:**

1. Run specific test after changes
2. After feature tests pass, ask user if they want full suite run

### Test Structure

**Use Factories:**

```php
// ✅ Good - Use factories
$user = User::factory()->create();

// ❌ Bad - Manual setup
$user = new User();
$user->name = 'Test';
$user->save();
```

**Check for Factory States:**
Before manually setting up models, check if factory has custom states:

```php
// Example: User factory might have ->admin() state
$admin = User::factory()->admin()->create();
```

### Faker Usage

Follow existing conventions:

```php
// Option 1: $this->faker
$name = $this->faker->word();

// Option 2: fake() helper
$email = fake()->email();
```

Check sibling tests to see which pattern the project uses.

## Vitest Testing (Frontend)

### Structure

```typescript
import { describe, it, expect } from 'vitest';

describe('ComponentName', () => {
    it('should render correctly', () => {
        // Arrange
        const wrapper = mount(Component);

        // Act
        // ... perform actions

        // Assert
        expect(wrapper.text()).toContain('Expected text');
    });
});
```

### Best Practices

- Test component behavior, not implementation
- Use data-testid for reliable element selection
- Mock external dependencies
- Keep tests isolated and independent

## Playwright E2E Testing

### Usage

```bash
# Run all E2E tests
npm run test:e2e

# Run in UI mode
npm run test:e2e:ui

# Run specific test
npx playwright test tests/e2e/login.spec.ts
```

### Best Practices

- Test critical user flows only
- Use page object pattern for reusability
- Handle async operations properly with `await`
- Take screenshots on failures

## Quality Check Before Commit

**ALWAYS run before finalizing:**

```bash
composer quality  # Laravel: Pint + PHPStan + PHPUnit
npm run quality   # JS: Prettier + ESLint + TypeScript + Vitest
```

**If tests fail or linting fails, FIX before proceeding.**
