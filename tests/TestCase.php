<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Boot the testing helper traits.
     *
     * Forces in-memory SQLite database to prevent file-based database
     * locking issues with parallel test execution.
     */
    protected function setUp(): void
    {
        // Ensure database file exists (required for putenv() override to work)
        // Use hardcoded path since database_path() helper isn't available yet
        $dbPath = __DIR__ . '/../database/pinecms.sqlite';
        if (! file_exists($dbPath)) {
            touch($dbPath);
            chmod($dbPath, 0664);
        }

        // Set environment variables BEFORE parent::setUp() boots Laravel
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');

        parent::setUp();
    }
}
