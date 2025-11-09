<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DatabaseInitializationTest extends TestCase
{
    private string $testDbPath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->testDbPath = storage_path('testing/feature_test_' . uniqid() . '.sqlite');
        Config::set('database.connections.sqlite.database', $this->testDbPath);
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

    public function testDatabaseInitializationEndpointCreatesDatabase(): void
    {
        $response = $this->postJson('/installer/database/initialize');

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'database_path',
            'database_size',
            'wal_enabled',
            'message',
        ]);

        $this->assertFileExists($this->testDbPath);
    }

    public function testDatabaseInitializationEnablesWalMode(): void
    {
        $response = $this->postJson('/installer/database/initialize');

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'wal_enabled' => true,
        ]);
    }

    public function testDatabaseInitializationSetsCorrectPermissions(): void
    {
        $this->postJson('/installer/database/initialize');

        $this->assertFileExists($this->testDbPath);

        $perms = substr(sprintf('%o', fileperms($this->testDbPath)), -4);
        $this->assertEquals('0600', $perms);
    }

    public function testDatabaseInitializationPreventsOverwrite(): void
    {
        // Initialize database first time
        $firstResponse = $this->postJson('/installer/database/initialize');
        $firstResponse->assertStatus(201);

        // Write some data
        $pdo = new \PDO("sqlite:{$this->testDbPath}");
        $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY)');
        $pdo->exec('INSERT INTO test (id) VALUES (1)');
        $pdo = null;

        $sizeAfterData = File::size($this->testDbPath);

        // Try to initialize again
        $secondResponse = $this->postJson('/installer/database/initialize');

        $secondResponse->assertStatus(201);
        $secondResponse->assertJson([
            'success' => true,
        ]);
        $secondResponse->assertJsonFragment([
            'message' => 'Database already exists and is accessible.',
        ]);

        $this->assertEquals($sizeAfterData, File::size($this->testDbPath));

        // Verify data still exists
        $pdo = new \PDO("sqlite:{$this->testDbPath}");
        $count = $pdo->query('SELECT COUNT(*) FROM test')->fetchColumn();
        $this->assertEquals(1, $count);
    }

    public function testDatabaseInfoEndpointReturnsCorrectData(): void
    {
        // Initialize database
        $this->postJson('/installer/database/initialize');

        // Get database info
        $response = $this->getJson('/installer/database/info');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'database' => [
                'path',
                'size',
                'size_formatted',
                'wal_enabled',
                'writable',
                'exists',
            ],
        ]);

        $data = $response->json();
        $this->assertTrue($data['database']['exists']);
        $this->assertTrue($data['database']['wal_enabled']);
        $this->assertTrue($data['database']['writable']);
        $this->assertGreaterThan(0, $data['database']['size']);
    }

    public function testDatabaseInfoHandlesNonExistentDatabase(): void
    {
        $response = $this->getJson('/installer/database/info');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'database' => [
                'exists' => false,
                'wal_enabled' => false,
                'writable' => false,
                'size' => 0,
                'size_formatted' => '0 B',
            ],
        ]);
    }

    public function testDatabaseInitializationFailsGracefullyWithInvalidPath(): void
    {
        // Set invalid path
        Config::set('database.connections.sqlite.database', '/root/invalid/test.sqlite');

        $response = $this->postJson('/installer/database/initialize');

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors',
        ]);
    }

    public function testDatabaseMigrationEndpointRunsMigrations(): void
    {
        // Initialize database first
        $this->postJson('/installer/database/initialize');

        // Run migrations
        $response = $this->postJson('/installer/database/migrate');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'migrations_run',
            'message',
        ]);
    }

    public function testDatabaseMigrationFailsWhenDatabaseNotInitialized(): void
    {
        // Set non-existent database path
        Config::set('database.connections.sqlite.database', '/nonexistent/test.sqlite');

        $response = $this->postJson('/installer/database/migrate');

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors',
        ]);
    }

    public function testEndpointsUseCorrectRouteNames(): void
    {
        $this->assertTrue(route('installer.database.initialize', absolute: false) === '/installer/database/initialize');
        $this->assertTrue(route('installer.database.migrate', absolute: false) === '/installer/database/migrate');
        $this->assertTrue(route('installer.database.info', absolute: false) === '/installer/database/info');
    }
}
