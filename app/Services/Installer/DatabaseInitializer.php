<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use PDO;
use PDOException;
use RuntimeException;

class DatabaseInitializer
{
    /**
     * PRAGMA settings for SQLite optimization
     *
     * @var array<string, string|int>
     */
    private const PRAGMA_SETTINGS = [
        'journal_mode' => 'WAL',        // Write-Ahead Logging
        'synchronous' => 1,             // NORMAL mode (1 = NORMAL)
        'cache_size' => 10000,          // 10,000 pages (~40MB)
        'temp_store' => 2,              // MEMORY mode (2 = MEMORY)
        'mmap_size' => 30000000000,     // Memory-mapped I/O (30GB)
        'page_size' => 4096,            // 4KB page size
        'foreign_keys' => 1,            // ON (1 = enabled)
    ];

    /**
     * Secure file permissions for SQLite database (owner read/write only)
     */
    private const DB_FILE_PERMISSIONS = 0600;

    /**
     * Initialize the SQLite database
     *
     * Creates database file, enables WAL mode, configures PRAGMA settings,
     * and validates the connection. Handles existing databases gracefully.
     *
     * @return array{
     *     success: bool,
     *     database_path: string,
     *     database_size: int,
     *     wal_enabled: bool,
     *     message: string,
     *     errors?: array<int, string>
     * }
     */
    public function initialize(): array
    {
        try {
            $dbPath = $this->getDatabasePath();

            // Handle memory database
            if ($dbPath === ':memory:') {
                return [
                    'success' => true,
                    'database_path' => ':memory:',
                    'database_size' => 0,
                    'wal_enabled' => false,
                    'message' => 'Using in-memory database.',
                ];
            }

            // Handle existing database
            if (File::exists($dbPath)) {
                return $this->handleExistingDatabase($dbPath);
            }

            // Create new database with optimized settings
            $this->createDatabase($dbPath);
            $this->validateConnection($dbPath);

            return [
                'success' => true,
                'database_path' => $dbPath,
                'database_size' => File::size($dbPath),
                'wal_enabled' => $this->isWalEnabled($dbPath),
                'message' => 'Database initialized successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'database_path' => $dbPath ?? '',
                'database_size' => 0,
                'wal_enabled' => false,
                'message' => 'Failed to initialize database: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
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
        try {
            $dbPath = $this->getDatabasePath();

            if ($dbPath === ':memory:') {
                return [
                    'path' => ':memory:',
                    'size' => 0,
                    'size_formatted' => '0 B',
                    'wal_enabled' => false,
                    'writable' => true,
                    'exists' => true,
                ];
            }

            $exists = File::exists($dbPath);
            $size = $exists ? File::size($dbPath) : 0;
            $writable = $exists && is_writable($dbPath);

            return [
                'path' => $dbPath,
                'size' => $size,
                'size_formatted' => $size > 0 ? $this->formatBytes($size) : '0 B',
                'wal_enabled' => $this->isWalEnabled($dbPath),
                'writable' => $writable,
                'exists' => $exists,
            ];
        } catch (\Exception $e) {
            return [
                'path' => '',
                'size' => 0,
                'size_formatted' => '0 B',
                'wal_enabled' => false,
                'writable' => false,
                'exists' => false,
            ];
        }
    }

    /**
     * Run database migrations
     *
     * @return array{
     *     success: bool,
     *     migrations_run: int,
     *     message: string,
     *     errors?: array<int, string>
     * }
     */
    public function runMigrations(): array
    {
        try {
            $dbPath = $this->getDatabasePath();

            if ($dbPath !== ':memory:' && ! File::exists($dbPath)) {
                throw new RuntimeException('Database file does not exist. Please initialize the database first.');
            }

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Get number of migrations run (this is approximate)
            $output = Artisan::output();
            $migrationsRun = substr_count($output, 'Migrated:');

            return [
                'success' => true,
                'migrations_run' => $migrationsRun,
                'message' => 'Migrations completed successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'migrations_run' => 0,
                'message' => 'Failed to run migrations: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Get the database path from configuration
     *
     * Handles absolute paths, relative paths, and :memory: databases
     *
     * @throws RuntimeException
     */
    private function getDatabasePath(): string
    {
        $dbPath = Config::get('database.connections.sqlite.database');

        if ($dbPath === null || $dbPath === '') {
            throw new RuntimeException('DB_DATABASE not configured in .env file');
        }

        // Handle memory database
        if ($dbPath === ':memory:') {
            return ':memory:';
        }

        // Handle absolute paths
        if (str_starts_with($dbPath, '/')) {
            return $dbPath;
        }

        // Handle relative paths - prepend base_path()
        return base_path($dbPath);
    }

    /**
     * Create the SQLite database file with secure permissions
     *
     * Creates the database and configures all PRAGMA settings in a single connection
     * to ensure proper initialization.
     *
     * @throws RuntimeException
     */
    private function createDatabase(string $path): void
    {
        // Ensure parent directory exists
        $directory = dirname($path);
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Create database and configure pragmas in a single connection
        try {
            $pdo = new PDO("sqlite:{$path}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Apply pragmas in specific order:
            // 1. page_size MUST be set before any tables are created
            // 2. journal_mode sets the WAL mode (also configured in config/database.php)
            // 3. synchronous MUST be set AFTER journal_mode (WAL resets it to FULL)
            //    (also configured in config/database.php for Laravel connections)
            // 4. Other performance settings (cache_size, temp_store, mmap_size)
            //    are set here but only persist for this connection - Laravel config
            //    does not support these settings
            $pdo->exec('PRAGMA page_size = ' . self::PRAGMA_SETTINGS['page_size']);
            $pdo->exec("PRAGMA journal_mode = '" . self::PRAGMA_SETTINGS['journal_mode'] . "'");
            $pdo->exec('PRAGMA synchronous = ' . self::PRAGMA_SETTINGS['synchronous']);
            $pdo->exec('PRAGMA cache_size = ' . self::PRAGMA_SETTINGS['cache_size']);
            $pdo->exec('PRAGMA temp_store = ' . self::PRAGMA_SETTINGS['temp_store']);
            $pdo->exec('PRAGMA mmap_size = ' . self::PRAGMA_SETTINGS['mmap_size']);
            $pdo->exec('PRAGMA foreign_keys = ' . self::PRAGMA_SETTINGS['foreign_keys']);

            // Create a dummy table to finalize database initialization
            $pdo->exec('CREATE TABLE IF NOT EXISTS __init__ (id INTEGER)');
            $pdo->exec('DROP TABLE __init__');

            $pdo = null; // Close connection
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to create database file: {$e->getMessage()}");
        }

        // Set secure permissions (owner read/write only)
        if (! chmod($path, self::DB_FILE_PERMISSIONS)) {
            throw new RuntimeException("Failed to set database file permissions: {$path}");
        }
    }

    /**
     * Validate database connection by performing a test write
     *
     * @throws RuntimeException
     */
    private function validateConnection(string $path): void
    {
        try {
            $pdo = new PDO("sqlite:{$path}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Test write operation
            $pdo->exec('CREATE TABLE IF NOT EXISTS __test__ (id INTEGER)');
            $pdo->exec('DROP TABLE __test__');
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection validation failed: {$e->getMessage()}");
        }
    }

    /**
     * Check if WAL mode is enabled
     */
    private function isWalEnabled(string $path): bool
    {
        if (! File::exists($path)) {
            return false;
        }

        try {
            $pdo = new PDO("sqlite:{$path}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $statement = $pdo->query('PRAGMA journal_mode');
            if ($statement === false) {
                return false;
            }

            $journalMode = $statement->fetchColumn();

            return strtolower((string) $journalMode) === 'wal';
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Handle existing database file
     *
     * Validates the database is accessible and returns info without overwriting
     *
     * @return array{
     *     success: bool,
     *     database_path: string,
     *     database_size: int,
     *     wal_enabled: bool,
     *     message: string,
     *     errors?: array<int, string>
     * }
     */
    private function handleExistingDatabase(string $path): array
    {
        try {
            // Validate existing database
            $this->validateConnection($path);

            return [
                'success' => true,
                'database_path' => $path,
                'database_size' => File::size($path),
                'wal_enabled' => $this->isWalEnabled($path),
                'message' => 'Database already exists and is accessible.',
            ];
        } catch (RuntimeException $e) {
            return [
                'success' => false,
                'database_path' => $path,
                'database_size' => File::size($path),
                'wal_enabled' => false,
                'message' => 'Database file exists but is corrupt or inaccessible: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Format bytes to human-readable string
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes > 0 ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return sprintf('%.2f %s', $bytes, $units[(int) $pow]);
    }
}
