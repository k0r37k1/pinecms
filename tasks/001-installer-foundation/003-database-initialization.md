---
task_id: 003
epic: 001-installer-foundation
title: SQLite Database Initialization
status: pending
priority: critical
estimated_effort: 4 hours
actual_effort: null
assignee: backend-architect
dependencies: [002]
tags: [installer, database, migrations, week-1]
---

# Task 003: SQLite Database Initialization

## 📋 Overview

Build SQLite database initialization service that creates the database file, configures WAL mode for better concurrency, validates connection, and prepares for migration execution. This task focuses on database setup, not schema creation (migrations run separately).

**Why Critical:** SQLite is the default database for PineCMS. Proper initialization with WAL mode ensures concurrent read performance and prevents database locking issues on shared hosting.

## 🎯 Acceptance Criteria

- [ ] Create SQLite database file from .env path
- [ ] Enable WAL (Write-Ahead Logging) mode
- [ ] Configure optimal PRAGMA settings (cache_size, synchronous)
- [ ] Validate database connection before returning success
- [ ] Test write permission to database file
- [ ] Handle existing database gracefully (don't overwrite)
- [ ] Return detailed status and error messages
- [ ] API endpoint for database initialization
- [ ] Comprehensive unit and feature tests
- [ ] PHPStan Level 8 compliance

## 🏗️ Implementation Steps

### Step 1: Create Database Initializer Service

**File**: `app/Services/Installer/DatabaseInitializer.php`

**Description**: Service to create and configure SQLite database with production-ready settings.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;
use PDOException;
use RuntimeException;

class DatabaseInitializer
{
    /**
     * SQLite PRAGMA settings for optimal performance
     */
    private const PRAGMA_SETTINGS = [
        'journal_mode' => 'WAL',        // Write-Ahead Logging (better concurrency)
        'synchronous' => 'NORMAL',      // Balance safety/performance
        'cache_size' => 10000,          // 10,000 pages (~40MB cache)
        'temp_store' => 'MEMORY',       // Use memory for temp tables
        'mmap_size' => 30000000000,     // Memory-mapped I/O (30GB)
        'page_size' => 4096,            // 4KB page size
        'foreign_keys' => 'ON',         // Enforce foreign key constraints
    ];

    /**
     * Initialize SQLite database
     *
     * @return array{
     *     success: bool,
     *     database_path: string,
     *     database_size: int,
     *     wal_enabled: bool,
     *     message: string,
     *     errors?: array<string, string>
     * }
     */
    public function initialize(): array
    {
        $dbPath = $this->getDatabasePath();

        // Check if database already exists
        if (File::exists($dbPath)) {
            return $this->handleExistingDatabase($dbPath);
        }

        try {
            $this->createDatabase($dbPath);
            $this->configurePragmas($dbPath);
            $this->validateConnection($dbPath);

            return [
                'success' => true,
                'database_path' => $dbPath,
                'database_size' => File::size($dbPath),
                'wal_enabled' => $this->isWalEnabled($dbPath),
                'message' => 'SQLite database initialized successfully.',
            ];
        } catch (RuntimeException $e) {
            return [
                'success' => false,
                'database_path' => $dbPath,
                'database_size' => 0,
                'wal_enabled' => false,
                'message' => 'Database initialization failed: ' . $e->getMessage(),
                'errors' => ['database' => $e->getMessage()],
            ];
        }
    }

    /**
     * Get database path from configuration
     *
     * @return string Absolute path to SQLite database file
     * @throws RuntimeException If DB_DATABASE not configured
     */
    private function getDatabasePath(): string
    {
        $dbPath = config('database.connections.sqlite.database');

        if ($dbPath === null || $dbPath === '') {
            throw new RuntimeException('DB_DATABASE not configured in .env file');
        }

        // Handle :memory: database (testing)
        if ($dbPath === ':memory:') {
            return $dbPath;
        }

        // Ensure absolute path
        if (!str_starts_with($dbPath, '/')) {
            $dbPath = base_path($dbPath);
        }

        return $dbPath;
    }

    /**
     * Handle existing database
     *
     * @param string $dbPath Database file path
     * @return array{
     *     success: bool,
     *     database_path: string,
     *     database_size: int,
     *     wal_enabled: bool,
     *     message: string
     * }
     */
    private function handleExistingDatabase(string $dbPath): array
    {
        try {
            $this->validateConnection($dbPath);

            return [
                'success' => true,
                'database_path' => $dbPath,
                'database_size' => File::size($dbPath),
                'wal_enabled' => $this->isWalEnabled($dbPath),
                'message' => 'Database already exists and is accessible.',
            ];
        } catch (RuntimeException $e) {
            return [
                'success' => false,
                'database_path' => $dbPath,
                'database_size' => File::size($dbPath),
                'wal_enabled' => false,
                'message' => 'Existing database is corrupt or inaccessible: ' . $e->getMessage(),
                'errors' => ['database' => $e->getMessage()],
            ];
        }
    }

    /**
     * Create SQLite database file
     *
     * @param string $dbPath Database file path
     * @throws RuntimeException If creation fails
     */
    private function createDatabase(string $dbPath): void
    {
        // Skip for :memory: database
        if ($dbPath === ':memory:') {
            return;
        }

        // Ensure parent directory exists
        $directory = dirname($dbPath);
        if (!File::exists($directory)) {
            if (!File::makeDirectory($directory, 0755, true)) {
                throw new RuntimeException("Failed to create database directory: {$directory}");
            }
        }

        // Create empty database file
        if (!touch($dbPath)) {
            throw new RuntimeException("Failed to create database file: {$dbPath}");
        }

        // Set file permissions (owner read/write only)
        chmod($dbPath, 0600);
    }

    /**
     * Configure SQLite PRAGMA settings
     *
     * @param string $dbPath Database file path
     * @throws RuntimeException If PRAGMA configuration fails
     */
    private function configurePragmas(string $dbPath): void
    {
        try {
            $pdo = new PDO("sqlite:{$dbPath}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            foreach (self::PRAGMA_SETTINGS as $pragma => $value) {
                $pdo->exec("PRAGMA {$pragma} = {$value}");
            }

            $pdo = null; // Close connection
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to configure PRAGMA settings: {$e->getMessage()}");
        }
    }

    /**
     * Validate database connection
     *
     * @param string $dbPath Database file path
     * @throws RuntimeException If connection fails or write test fails
     */
    private function validateConnection(string $dbPath): void
    {
        try {
            $pdo = new PDO("sqlite:{$dbPath}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Test write permission
            $pdo->exec('CREATE TABLE IF NOT EXISTS _test_table (id INTEGER PRIMARY KEY)');
            $pdo->exec('DROP TABLE IF EXISTS _test_table');

            $pdo = null; // Close connection
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection validation failed: {$e->getMessage()}");
        }
    }

    /**
     * Check if WAL mode is enabled
     *
     * @param string $dbPath Database file path
     * @return bool True if WAL enabled
     */
    private function isWalEnabled(string $dbPath): bool
    {
        try {
            $pdo = new PDO("sqlite:{$dbPath}");
            $result = $pdo->query('PRAGMA journal_mode')->fetchColumn();
            $pdo = null;

            return strtoupper((string) $result) === 'WAL';
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Run database migrations
     *
     * @return array{
     *     success: bool,
     *     migrations_run: int,
     *     message: string,
     *     errors?: array<string, string>
     * }
     */
    public function runMigrations(): array
    {
        try {
            // Get pending migrations count
            $pendingMigrations = DB::table('migrations')
                ->whereNull('batch')
                ->count();

            // Run migrations
            \Artisan::call('migrate', [
                '--force' => true,  // Run in production
                '--step' => true,   // Run step-by-step
            ]);

            $output = \Artisan::output();

            return [
                'success' => true,
                'migrations_run' => $pendingMigrations,
                'message' => 'Database migrations completed successfully.',
                'output' => $output,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'migrations_run' => 0,
                'message' => 'Migration failed: ' . $e->getMessage(),
                'errors' => ['migrations' => $e->getMessage()],
            ];
        }
    }

    /**
     * Get database information
     *
     * @return array{
     *     path: string,
     *     size: int,
     *     size_formatted: string,
     *     wal_enabled: bool,
     *     writable: bool,
     *     exists: bool
     * }
     */
    public function getDatabaseInfo(): array
    {
        $dbPath = $this->getDatabasePath();
        $exists = File::exists($dbPath);

        return [
            'path' => $dbPath,
            'size' => $exists ? File::size($dbPath) : 0,
            'size_formatted' => $exists ? $this->formatBytes(File::size($dbPath)) : '0 B',
            'wal_enabled' => $exists ? $this->isWalEnabled($dbPath) : false,
            'writable' => $exists ? File::isWritable($dbPath) : false,
            'exists' => $exists,
        ];
    }

    /**
     * Format bytes to human-readable string
     *
     * @param int $bytes File size in bytes
     * @return string Formatted size (e.g., "1.5 MB")
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);

        return number_format($bytes / (1024 ** $power), 2) . ' ' . $units[$power];
    }
}
```

### Step 2: Create Database Controller

**File**: `app/Http/Controllers/Installer/DatabaseController.php`

**Description**: API endpoints for database initialization and migration.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\DatabaseInitializer;
use Illuminate\Http\JsonResponse;

class DatabaseController extends Controller
{
    public function __construct(
        private readonly DatabaseInitializer $initializer
    ) {}

    /**
     * Initialize SQLite database
     *
     * @return JsonResponse
     */
    public function initialize(): JsonResponse
    {
        $result = $this->initializer->initialize();

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    /**
     * Run database migrations
     *
     * @return JsonResponse
     */
    public function migrate(): JsonResponse
    {
        $result = $this->initializer->runMigrations();

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Get database information
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        $info = $this->initializer->getDatabaseInfo();

        return response()->json([
            'success' => true,
            'database' => $info,
        ]);
    }
}
```

### Step 3: Add Routes

**File**: `routes/installer.php`

**Description**: Add database initialization routes.

**Implementation**:

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')->middleware('web')->group(function () {
    Route::get('/requirements', [RequirementsController::class, 'check'])
        ->name('installer.requirements.check');

    Route::post('/environment', [EnvironmentController::class, 'generate'])
        ->name('installer.environment.generate');

    // Database initialization
    Route::post('/database/initialize', [DatabaseController::class, 'initialize'])
        ->name('installer.database.initialize');

    Route::post('/database/migrate', [DatabaseController::class, 'migrate'])
        ->name('installer.database.migrate');

    Route::get('/database/info', [DatabaseController::class, 'info'])
        ->name('installer.database.info');
});
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Services/Installer/DatabaseInitializerTest.php`
    - Test `getDatabasePath()` handles absolute and relative paths
    - Test `createDatabase()` creates file with correct permissions
    - Test `configurePragmas()` sets WAL mode and other settings
    - Test `validateConnection()` detects invalid database
    - Test `isWalEnabled()` correctly detects WAL mode
    - Test `handleExistingDatabase()` doesn't overwrite
    - Test `formatBytes()` with various sizes
    - Test error handling for read-only directories

**Feature Tests:**

- `tests/Feature/Installer/DatabaseInitializationTest.php`
    - Test POST `/installer/database/initialize` creates database
    - Test GET `/installer/database/info` returns correct data
    - Test POST `/installer/database/migrate` runs migrations
    - Test initialization fails gracefully with invalid path
    - Test existing database is not overwritten

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\DatabaseInitializer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DatabaseInitializerTest extends TestCase
{
    private DatabaseInitializer $initializer;
    private string $testDbPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializer = new DatabaseInitializer();
        $this->testDbPath = storage_path('testing/test_' . uniqid() . '.sqlite');
    }

    protected function tearDown(): void
    {
        // Clean up test database
        if (File::exists($this->testDbPath)) {
            File::delete($this->testDbPath);
            File::deleteDirectory(dirname($this->testDbPath));
        }

        parent::tearDown();
    }

    public function test_initialize_creates_database_file(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $result = $this->initializer->initialize();

        $this->assertTrue($result['success']);
        $this->assertFileExists($this->testDbPath);
        $this->assertEquals($this->testDbPath, $result['database_path']);
    }

    public function test_initialize_enables_wal_mode(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $result = $this->initializer->initialize();

        $this->assertTrue($result['success']);
        $this->assertTrue($result['wal_enabled']);
    }

    public function test_initialize_sets_correct_file_permissions(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $this->initializer->initialize();

        $perms = substr(sprintf('%o', fileperms($this->testDbPath)), -4);
        $this->assertEquals('0600', $perms);
    }

    public function test_format_bytes_converts_correctly(): void
    {
        $result = $this->invokeMethod($this->initializer, 'formatBytes', [1024]);
        $this->assertEquals('1.00 KB', $result);

        $result = $this->invokeMethod($this->initializer, 'formatBytes', [1048576]);
        $this->assertEquals('1.00 MB', $result);

        $result = $this->invokeMethod($this->initializer, 'formatBytes', [1073741824]);
        $this->assertEquals('1.00 GB', $result);
    }

    public function test_handle_existing_database_does_not_overwrite(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        // Create database first time
        $firstResult = $this->initializer->initialize();
        $firstSize = File::size($this->testDbPath);

        // Write some data
        $pdo = new \PDO("sqlite:{$this->testDbPath}");
        $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY)');
        $pdo = null;

        $newSize = File::size($this->testDbPath);
        $this->assertGreaterThan($firstSize, $newSize);

        // Initialize again - should not overwrite
        $secondResult = $this->initializer->initialize();

        $this->assertTrue($secondResult['success']);
        $this->assertEquals($newSize, File::size($this->testDbPath));
        $this->assertStringContainsString('already exists', $secondResult['message']);
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

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (SQLite Database Creation)
- **Architecture**: `docs/prd/04-ARCHITECTURE.md` Section 3 (Hybrid Storage Model)
- **Timeline**: Week 1-2 (v1.0.0)
- **Success Criteria**: Database created with WAL mode, < 1 second initialization

**Architecture:**

- **Database**: SQLite with WAL mode (`docs/prd/07-TECHNICAL-SPECIFICATIONS.md` Section 2)
- **Performance**: PRAGMA optimizations for shared hosting
- **Storage**: File-based database in `database/` directory

**Quality Requirements:**

- **Security**: File permissions 0600, directory traversal prevention
- **Performance**: WAL mode for concurrent reads, optimized PRAGMA settings
- **Testing**: Unit tests for all initialization logic, Feature tests for API endpoints

**Related Tasks:**

- **Next**: 004-admin-user-wizard
- **Blocks**: 004-admin-user-wizard (needs database for user creation)
- **Depends On**: 002-environment-generator (needs .env for DB path)

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes (`composer analyse`)
- [ ] Laravel Pint formatted (`vendor/bin/pint`)
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks with array shape types
- [ ] No hardcoded database paths

### Testing

- [ ] Unit tests written and passing (8+ test cases)
- [ ] Feature tests written and passing
- [ ] Test coverage > 80%
- [ ] Edge cases covered (existing DB, read-only directories, :memory: database)

### Security

- [ ] File permissions set to 0600 (owner read/write only)
- [ ] Directory traversal prevention (path validation)
- [ ] No SQL injection (PDO prepared statements)
- [ ] Error messages don't expose sensitive paths

### Performance

- [ ] WAL mode enabled for better concurrency
- [ ] PRAGMA settings optimized (cache_size, synchronous, mmap_size)
- [ ] Database initialization < 1 second

### Documentation

- [ ] PHPDoc comments added with detailed array shapes
- [ ] PRAGMA settings documented with rationale
- [ ] WAL mode benefits explained

## ✅ Verification Steps

```bash
# Backend quality check
composer quality

# Test database initialization endpoint
php artisan serve
curl -X POST http://localhost:8000/installer/database/initialize | jq

# Verify database created
ls -la database/*.sqlite

# Check file permissions
stat -c "%a %n" database/*.sqlite  # Should show 600

# Verify WAL mode enabled
sqlite3 database/pinecms.sqlite "PRAGMA journal_mode"  # Should return: wal

# Check PRAGMA settings
sqlite3 database/pinecms.sqlite "PRAGMA cache_size"    # Should return: 10000
sqlite3 database/pinecms.sqlite "PRAGMA synchronous"   # Should return: 1 (NORMAL)
sqlite3 database/pinecms.sqlite "PRAGMA foreign_keys"  # Should return: 1 (ON)

# Verify database info endpoint
curl http://localhost:8000/installer/database/info | jq
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-003-database-initialization

# Implement
git commit -m "feat(installer): add DatabaseInitializer service"
git commit -m "feat(installer): add database initialization endpoints"
git commit -m "test(installer): add DatabaseInitializer unit tests"
git commit -m "feat(installer): configure WAL mode and PRAGMA settings"

# Before completing
composer quality

# Complete task
git commit -m "feat(installer): complete task-003-database-initialization

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```
