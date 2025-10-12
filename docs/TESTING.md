# Testing Guide

PineCMS uses PHPUnit for testing with comprehensive code coverage and CI/CD integration.

---

## Table of Contents

- [Quick Start](#quick-start)
- [Test Structure](#test-structure)
- [Running Tests](#running-tests)
- [Code Coverage](#code-coverage)
- [Writing Tests](#writing-tests)
- [Database Testing](#database-testing)
- [HTTP Testing](#http-testing)
- [Browser Testing](#browser-testing)
- [Mocking & Faking](#mocking--faking)
- [CI/CD Integration](#cicd-integration)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## Quick Start

### Run All Tests

```bash
# Using Artisan (Recommended)
php artisan test

# Using PHPUnit directly
vendor/bin/phpunit

# Using Pest (if installed)
vendor/bin/pest
```

### Run Specific Test Suite

```bash
# Run only Unit tests
php artisan test --testsuite=Unit

# Run only Feature tests
php artisan test --testsuite=Feature
```

### Run Specific Test File

```bash
php artisan test tests/Unit/ExampleTest.php
```

### Run with Code Coverage

```bash
# Generate coverage report
php artisan test --coverage

# With minimum coverage threshold (80%)
php artisan test --coverage --min=80

# Detailed coverage (requires Xdebug or PCOV)
php artisan test --coverage --coverage-html coverage/html
```

---

## Test Structure

### Directory Layout

```
tests/
├── Feature/            # Integration tests
│   └── ExampleTest.php
├── Unit/               # Unit tests
│   └── ExampleTest.php
├── CreatesApplication.php
└── TestCase.php        # Base test class
```

### Test Suites

- **Unit Tests** (`tests/Unit/`) - Test individual classes and methods in isolation
- **Feature Tests** (`tests/Feature/`) - Test application features end-to-end
- **Integration Tests** - Test interactions between multiple components (optional)

### Example Test Structure

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_basic_test(): void
    {
        $this->assertTrue(true);
    }
}
```

---

## Running Tests

### Basic Commands

```bash
# Run all tests
php artisan test

# Run tests in parallel (faster)
php artisan test --parallel

# Run tests with detailed output
php artisan test --verbose

# Stop on first failure
php artisan test --stop-on-failure

# Run tests in random order
php artisan test --order=random
```

### Filtering Tests

```bash
# Run specific test method
php artisan test --filter test_basic_test

# Run tests matching pattern
php artisan test --filter ExampleTest

# Run tests from specific group
php artisan test --group=integration

# Exclude tests from group
php artisan test --exclude-group=slow
```

### Test Groups

Define test groups using PHPDoc annotations:

```php
/**
 * @group integration
 * @group slow
 */
public function test_api_integration(): void
{
    // Test code
}
```

Run specific groups:

```bash
php artisan test --group=integration
php artisan test --exclude-group=slow
```

---

## Code Coverage

### Generating Coverage Reports

```bash
# Console output
php artisan test --coverage

# HTML report
php artisan test --coverage --coverage-html coverage/html

# Clover XML (for CI/CD)
php artisan test --coverage --coverage-clover coverage/clover.xml

# Cobertura XML (for GitLab/Jenkins)
php artisan test --coverage --coverage-cobertura coverage/cobertura.xml
```

### Coverage Requirements

Install either **Xdebug** or **PCOV** for code coverage:

#### Install Xdebug (Development)

```bash
# macOS
brew install php@8.3-xdebug

# Ubuntu/Debian
sudo apt install php8.3-xdebug

# Check installation
php -v | grep Xdebug
```

#### Install PCOV (Faster for CI/CD)

```bash
# Install via PECL
pecl install pcov

# Enable extension
echo "extension=pcov.so" >> /etc/php/8.3/cli/php.ini

# Check installation
php -m | grep pcov
```

### Coverage Thresholds

Set minimum coverage requirements:

```bash
# Fail if coverage below 80%
php artisan test --coverage --min=80

# Fail if coverage below 90%
php artisan test --coverage --min=90
```

### Coverage Configuration

Coverage settings in `phpunit.xml`:

```xml
<coverage includeUncoveredFiles="true"
          pathCoverage="false"
          ignoreDeprecatedCodeUnits="true">
    <report>
        <clover outputFile="coverage/clover.xml"/>
        <cobertura outputFile="coverage/cobertura.xml"/>
        <html outputDirectory="coverage/html" lowUpperBound="50" highLowerBound="80"/>
        <text outputFile="php://stdout" showOnlySummary="true"/>
    </report>
</coverage>
```

### Viewing Coverage Reports

```bash
# Open HTML report in browser
open coverage/html/index.html

# macOS
open coverage/html/index.html

# Linux
xdg-open coverage/html/index.html
```

---

## Writing Tests

### Unit Test Example

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Calculator;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new Calculator();
    }

    public function test_it_can_add_two_numbers(): void
    {
        $result = $this->calculator->add(2, 3);

        $this->assertEquals(5, $result);
    }

    public function test_it_can_subtract_two_numbers(): void
    {
        $result = $this->calculator->subtract(5, 3);

        $this->assertEquals(2, $result);
    }
}
```

### Feature Test Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
```

### Common Assertions

```php
// Equality
$this->assertEquals($expected, $actual);
$this->assertSame($expected, $actual); // Strict comparison

// Boolean
$this->assertTrue($condition);
$this->assertFalse($condition);

// Null
$this->assertNull($value);
$this->assertNotNull($value);

// Empty
$this->assertEmpty($value);
$this->assertNotEmpty($value);

// Arrays
$this->assertArrayHasKey('key', $array);
$this->assertContains('value', $array);
$this->assertCount(3, $array);

// Strings
$this->assertStringContainsString('substring', $string);
$this->assertStringStartsWith('prefix', $string);
$this->assertStringEndsWith('suffix', $string);

// Exceptions
$this->expectException(\Exception::class);
$this->expectExceptionMessage('Error message');
```

---

## Database Testing

### Using RefreshDatabase Trait

Automatically migrate and rollback database after each test:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }
}
```

### Using DatabaseTransactions Trait

Wrap each test in a database transaction:

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_be_created(): void
    {
        $user = User::create(['name' => 'John']);

        $this->assertDatabaseHas('users', ['name' => 'John']);
    }
}
```

### Database Assertions

```php
// Assert record exists
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

// Assert record doesn't exist
$this->assertDatabaseMissing('users', ['email' => 'test@example.com']);

// Assert record count
$this->assertDatabaseCount('users', 5);

// Assert model exists
$this->assertModelExists($user);

// Assert model missing
$this->assertModelMissing($user);
```

### Factories

Generate test data using factories:

```php
use App\Models\User;

// Create single user
$user = User::factory()->create();

// Create multiple users
$users = User::factory()->count(10)->create();

// Create with attributes
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// Make without saving
$user = User::factory()->make();
```

### Seeders in Tests

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    // Run default seeder before each test
    protected $seed = true;

    public function test_with_seeded_data(): void
    {
        // Database is seeded
        $this->assertDatabaseCount('users', 10);
    }
}
```

---

## HTTP Testing

### Making Requests

```php
// GET request
$response = $this->get('/api/users');

// POST request
$response = $this->post('/api/users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

// PUT request
$response = $this->put('/api/users/1', [
    'name' => 'Jane Doe',
]);

// PATCH request
$response = $this->patch('/api/users/1', [
    'name' => 'Jane Doe',
]);

// DELETE request
$response = $this->delete('/api/users/1');
```

### Response Assertions

```php
// Status codes
$response->assertStatus(200);
$response->assertOk();
$response->assertCreated();
$response->assertNoContent();
$response->assertNotFound();
$response->assertForbidden();
$response->assertUnauthorized();

// Redirects
$response->assertRedirect('/home');

// JSON
$response->assertJson(['name' => 'John']);
$response->assertJsonCount(10, 'data');
$response->assertJsonStructure([
    'data' => [
        '*' => ['id', 'name', 'email']
    ]
]);

// Views
$response->assertViewIs('welcome');
$response->assertViewHas('user');
$response->assertSee('Welcome');
$response->assertDontSee('Error');
```

### Authenticated Requests

```php
use App\Models\User;

public function test_authenticated_user_can_access_dashboard(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
}
```

### API Testing with Sanctum

```php
use App\Models\User;
use Laravel\Sanctum\Sanctum;

public function test_authenticated_api_request(): void
{
    Sanctum::actingAs(
        User::factory()->create(),
        ['*']
    );

    $response = $this->get('/api/user');

    $response->assertOk();
}
```

---

## Browser Testing

### Laravel Dusk

For browser testing, install Laravel Dusk:

```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

### Basic Dusk Test

```php
<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/dashboard');
        });
    }
}
```

---

## Mocking & Faking

### Facades

```php
use Illuminate\Support\Facades\Cache;

public function test_cache_facade(): void
{
    Cache::shouldReceive('get')
        ->once()
        ->with('key')
        ->andReturn('value');

    $result = Cache::get('key');

    $this->assertEquals('value', $result);
}
```

### Mail Faking

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

public function test_order_sends_email(): void
{
    Mail::fake();

    // Perform action that sends email
    $this->post('/orders', [...]);

    Mail::assertSent(OrderShipped::class);
    Mail::assertSent(OrderShipped::class, function ($mail) {
        return $mail->hasTo('customer@example.com');
    });
}
```

### Queue Faking

```php
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessOrder;

public function test_order_is_queued(): void
{
    Queue::fake();

    // Perform action that queues job
    $this->post('/orders', [...]);

    Queue::assertPushed(ProcessOrder::class);
}
```

### Event Faking

```php
use Illuminate\Support\Facades\Event;
use App\Events\OrderShipped;

public function test_order_event_is_dispatched(): void
{
    Event::fake();

    // Perform action that dispatches event
    $this->post('/orders', [...]);

    Event::assertDispatched(OrderShipped::class);
}
```

### Storage Faking

```php
use Illuminate\Support\Facades\Storage;

public function test_file_upload(): void
{
    Storage::fake('public');

    // Perform file upload
    $response = $this->post('/upload', [
        'file' => UploadedFile::fake()->image('photo.jpg')
    ]);

    Storage::disk('public')->assertExists('photos/photo.jpg');
}
```

---

## CI/CD Integration

### GitHub Actions

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  tests:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring, xml, pcov
          coverage: pcov

      - name: Install Dependencies
        run: composer install --prefer-dist --no-progress

      - name: Run Tests
        run: php artisan test --coverage --min=80

      - name: Upload Coverage
        uses: codecov/codecov-action@v4
        with:
          files: ./coverage/clover.xml
```

### GitLab CI

Create `.gitlab-ci.yml`:

```yaml
test:
  stage: test
  image: php:8.3
  before_script:
    - apt-get update -qq
    - apt-get install -y -qq git unzip libpq-dev
    - pecl install pcov
    - docker-php-ext-enable pcov
    - curl -sS https://getcomposer.org/installer | php
    - php composer.phar install
  script:
    - php artisan test --coverage --min=80
  coverage: '/^\s*Lines:\s*\d+.\d+\%/'
  artifacts:
    reports:
      coverage_report:
        coverage_format: cobertura
        path: coverage/cobertura.xml
      junit: coverage/junit.xml
```

---

## Best Practices

### Test Organization

1. **One Assertion Per Test** - Keep tests focused
2. **Descriptive Test Names** - Use `test_it_can_do_something` pattern
3. **Arrange-Act-Assert** - Structure tests clearly
4. **Use Factories** - Generate test data consistently
5. **Mock External Services** - Don't hit real APIs

### Test Performance

1. **Use RefreshDatabase** - Faster than DatabaseMigrations
2. **Use PCOV** - Faster than Xdebug for coverage
3. **Run in Parallel** - `php artisan test --parallel`
4. **Cache Dependencies** - In CI/CD pipelines
5. **Skip Slow Tests** - Use groups to exclude slow tests

### Code Coverage Goals

- **Minimum 80%** - Good coverage
- **90%+ for critical paths** - Authentication, payments, etc.
- **Don't chase 100%** - Focus on valuable tests

### Test Data

```php
// Good - Use factories
$user = User::factory()->create();

// Bad - Manual creation
$user = new User();
$user->name = 'John';
$user->email = 'john@example.com';
$user->save();
```

---

## Troubleshooting

### Tests Failing Locally But Passing in CI

**Problem:** Database state or environment differences

**Solution:**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ensure .env.testing exists
cp .env .env.testing
```

### Code Coverage Not Working

**Problem:** Xdebug or PCOV not installed

**Solution:**
```bash
# Check if Xdebug is installed
php -v | grep Xdebug

# Install PCOV (faster)
pecl install pcov
echo "extension=pcov.so" >> php.ini
```

### Tests Running Slow

**Problem:** Running against real database or not using parallel execution

**Solution:**
```bash
# Use in-memory SQLite
# In phpunit.xml:
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>

# Run tests in parallel
php artisan test --parallel
```

### Database Migrations Failing

**Problem:** Missing migrations or wrong database

**Solution:**
```bash
# Fresh migration in testing environment
php artisan migrate:fresh --env=testing

# Check database connection
php artisan tinker --env=testing
>>> DB::connection()->getDatabaseName()
```

### Memory Limit Errors

**Problem:** PHPUnit running out of memory

**Solution:**
```bash
# Increase memory limit
php -d memory_limit=512M artisan test

# Or in php.ini
memory_limit = 512M
```

---

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/12.x/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Dusk Documentation](https://laravel.com/docs/12.x/dusk)
- [Pest Testing Framework](https://pestphp.com)
