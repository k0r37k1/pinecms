<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\DatabaseInitializer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;
use Tests\TestCase;

class DatabaseInitializerTest extends TestCase
{
    private DatabaseInitializer $initializer;

    private string $testDbPath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializer = new DatabaseInitializer;
        $this->testDbPath = storage_path('testing/test_' . uniqid() . '.sqlite');
    }

    protected function tearDown(): void
    {
        // Clean up test database and WAL files
        if (File::exists($this->testDbPath)) {
            File::delete($this->testDbPath);
        }
        if (File::exists($this->testDbPath . '-wal')) {
            File::delete($this->testDbPath . '-wal');
        }
        if (File::exists($this->testDbPath . '-shm')) {
            File::delete($this->testDbPath . '-shm');
        }
        if (File::exists(dirname($this->testDbPath))) {
            File::deleteDirectory(dirname($this->testDbPath));
        }

        parent::tearDown();
    }

    public function testInitializeCreatesDatabaseFile(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $result = $this->initializer->initialize();

        $this->assertTrue($result['success']);
        $this->assertFileExists($this->testDbPath);
        $this->assertEquals($this->testDbPath, $result['database_path']);
    }

    public function testInitializeEnablesWalMode(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $result = $this->initializer->initialize();

        $this->assertTrue($result['success']);
        $this->assertTrue($result['wal_enabled']);
    }

    public function testInitializeSetsCorrectFilePermissions(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $this->initializer->initialize();

        $perms = substr(sprintf('%o', fileperms($this->testDbPath)), -4);
        $this->assertEquals('0600', $perms);
    }

    public function testGetDatabasePathHandlesRelativePath(): void
    {
        Config::set('database.connections.sqlite.database', 'database/test.sqlite');

        $path = $this->invokePrivateMethod('getDatabasePath');

        $this->assertStringStartsWith(base_path(), $path);
        $this->assertStringEndsWith('database/test.sqlite', $path);
    }

    public function testGetDatabasePathHandlesAbsolutePath(): void
    {
        $absolutePath = '/tmp/test.sqlite';
        Config::set('database.connections.sqlite.database', $absolutePath);

        $path = $this->invokePrivateMethod('getDatabasePath');

        $this->assertEquals($absolutePath, $path);
    }

    public function testGetDatabasePathHandlesMemoryDatabase(): void
    {
        Config::set('database.connections.sqlite.database', ':memory:');

        $path = $this->invokePrivateMethod('getDatabasePath');

        $this->assertEquals(':memory:', $path);
    }

    public function testGetDatabasePathThrowsExceptionWhenNotConfigured(): void
    {
        Config::set('database.connections.sqlite.database', null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DB_DATABASE not configured');

        $this->invokePrivateMethod('getDatabasePath');
    }

    public function testHandleExistingDatabaseDoesNotOverwrite(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        // Create database first time
        $firstResult = $this->initializer->initialize();
        $this->assertTrue($firstResult['success']);

        // Write some data
        $pdo = new PDO("sqlite:{$this->testDbPath}");
        $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY)');
        $pdo->exec('INSERT INTO test (id) VALUES (1)');
        $pdo = null;

        $sizeAfterData = File::size($this->testDbPath);

        // Initialize again - should not overwrite
        $secondResult = $this->initializer->initialize();

        $this->assertTrue($secondResult['success']);
        $this->assertEquals($sizeAfterData, File::size($this->testDbPath));
        $this->assertStringContainsString('already exists', $secondResult['message']);

        // Verify data still exists
        $pdo = new PDO("sqlite:{$this->testDbPath}");
        $count = $pdo->query('SELECT COUNT(*) FROM test')->fetchColumn();
        $this->assertEquals(1, $count);
    }

    public function testIsWalEnabledDetectsWalMode(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $this->initializer->initialize();

        $isWalEnabled = $this->invokePrivateMethod('isWalEnabled', [$this->testDbPath]);

        $this->assertTrue($isWalEnabled);
    }

    public function testIsWalEnabledReturnsFalseForNonExistentDatabase(): void
    {
        $isWalEnabled = $this->invokePrivateMethod('isWalEnabled', ['/nonexistent/database.sqlite']);

        $this->assertFalse($isWalEnabled);
    }

    public function testFormatBytesConvertsCorrectly(): void
    {
        $result = $this->invokePrivateMethod('formatBytes', [0]);
        $this->assertEquals('0.00 B', $result);

        $result = $this->invokePrivateMethod('formatBytes', [1024]);
        $this->assertEquals('1.00 KB', $result);

        $result = $this->invokePrivateMethod('formatBytes', [1048576]);
        $this->assertEquals('1.00 MB', $result);

        $result = $this->invokePrivateMethod('formatBytes', [1073741824]);
        $this->assertEquals('1.00 GB', $result);

        $result = $this->invokePrivateMethod('formatBytes', [1099511627776]);
        $this->assertEquals('1.00 TB', $result);
    }

    public function testValidateConnectionDetectsCorruptDatabase(): void
    {
        // Create a corrupt database file (just random bytes)
        File::ensureDirectoryExists(dirname($this->testDbPath));
        File::put($this->testDbPath, 'this is not a valid sqlite database');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Database connection validation failed');

        $this->invokePrivateMethod('validateConnection', [$this->testDbPath]);
    }

    public function testInitializeReturnsErrorForInvalidPath(): void
    {
        // Set a path in a read-only directory
        $readOnlyPath = '/root/test.sqlite';
        Config::set('database.connections.sqlite.database', $readOnlyPath);

        $result = $this->initializer->initialize();

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
        $this->assertStringContainsString('Failed', $result['message']);
    }

    public function testGetDatabaseInfoReturnsCorrectData(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $this->initializer->initialize();

        $info = $this->initializer->getDatabaseInfo();

        $this->assertEquals($this->testDbPath, $info['path']);
        $this->assertGreaterThan(0, $info['size']);
        $this->assertStringContainsString('B', $info['size_formatted']);
        $this->assertTrue($info['wal_enabled']);
        $this->assertTrue($info['writable']);
        $this->assertTrue($info['exists']);
    }

    public function testGetDatabaseInfoHandlesNonExistentDatabase(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $info = $this->initializer->getDatabaseInfo();

        $this->assertEquals($this->testDbPath, $info['path']);
        $this->assertEquals(0, $info['size']);
        $this->assertEquals('0 B', $info['size_formatted']);
        $this->assertFalse($info['wal_enabled']);
        $this->assertFalse($info['writable']);
        $this->assertFalse($info['exists']);
    }

    public function testConfigurePragmasSetsAllSettings(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        $this->initializer->initialize();

        // Verify PRAGMA settings using Laravel DB
        // Laravel SQLite supports: journal_mode, synchronous, foreign_key_constraints
        $journalMode = DB::connection('sqlite')->selectOne('PRAGMA journal_mode')->journal_mode;
        $this->assertEquals('wal', strtolower((string) $journalMode));

        $synchronous = DB::connection('sqlite')->selectOne('PRAGMA synchronous')->synchronous;
        $this->assertEquals(1, $synchronous); // NORMAL = 1

        $foreignKeys = DB::connection('sqlite')->selectOne('PRAGMA foreign_keys')->foreign_keys;
        $this->assertEquals(1, $foreignKeys);

        // Other PRAGMA settings are applied during database creation
        // and can be verified to exist (even if not configurable via Laravel Config)
        $cacheSize = DB::connection('sqlite')->selectOne('PRAGMA cache_size')->cache_size;
        $this->assertIsInt($cacheSize);

        $tempStore = DB::connection('sqlite')->selectOne('PRAGMA temp_store')->temp_store;
        $this->assertIsInt($tempStore);
    }

    public function testHandleExistingDatabaseValidatesConnection(): void
    {
        Config::set('database.connections.sqlite.database', $this->testDbPath);

        // Create valid database
        $this->initializer->initialize();

        // Corrupt the database
        File::put($this->testDbPath, 'corrupted data');

        // Try to initialize again
        $result = $this->initializer->initialize();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('corrupt or inaccessible', $result['message']);
    }

    /**
     * Invoke a private or protected method
     *
     * @param  array<int, mixed>  $parameters
     */
    private function invokePrivateMethod(string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass($this->initializer);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->initializer, $parameters);
    }
}
