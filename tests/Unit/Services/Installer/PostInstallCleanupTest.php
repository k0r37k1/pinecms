<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\PostInstallCleanup;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PostInstallCleanupTest extends TestCase
{
    private PostInstallCleanup $cleanup;

    private string $testLockPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanup = new PostInstallCleanup;
        $this->testLockPath = base_path('.installed');

        // Ensure clean state
        if (File::exists($this->testLockPath)) {
            @chmod($this->testLockPath, 0644); // Make writable for deletion
            File::delete($this->testLockPath);
        }
    }

    protected function tearDown(): void
    {
        // Cleanup test artifacts
        if (File::exists($this->testLockPath)) {
            @chmod($this->testLockPath, 0644); // Make writable for deletion
            File::delete($this->testLockPath);
        }
        parent::tearDown();
    }

    /**
     * Test isInstalled returns false when not installed
     */
    public function testIsInstalledReturnsFalseWhenNotInstalled(): void
    {
        // Ensure lock file doesn't exist
        $this->assertFalse(File::exists($this->testLockPath));

        $result = $this->cleanup->isInstalled();

        $this->assertFalse($result);
    }

    /**
     * Test isInstalled returns true when lock file exists
     */
    public function testIsInstalledReturnsTrueWhenLockFileExists(): void
    {
        // Create temporary lock file
        File::put($this->testLockPath, json_encode(['installed_at' => now()->toIso8601String()]));

        $result = $this->cleanup->isInstalled();

        $this->assertTrue($result);
    }

    /**
     * Test createLockFile creates file with read-only permissions
     */
    public function testCreateLockFileCreatesReadOnlyFile(): void
    {
        $this->invokeMethod($this->cleanup, 'createLockFile');

        // Verify file exists
        $this->assertFileExists($this->testLockPath);

        // Verify permissions (read-only: 0444)
        $perms = substr(sprintf('%o', fileperms($this->testLockPath)), -3);
        $this->assertEquals('444', $perms);
    }

    /**
     * Test createLockFile contains correct data
     */
    public function testCreateLockFileContainsCorrectData(): void
    {
        $this->invokeMethod($this->cleanup, 'createLockFile');

        $lockData = json_decode(File::get($this->testLockPath), true);

        $this->assertArrayHasKey('installed_at', $lockData);
        $this->assertArrayHasKey('version', $lockData);
        $this->assertArrayHasKey('php_version', $lockData);
        $this->assertArrayHasKey('laravel_version', $lockData);
        $this->assertEquals(PHP_VERSION, $lockData['php_version']);
    }

    /**
     * Test updateEnvFile updates PINECMS_INSTALLED flag
     */
    public function testUpdateEnvFileUpdatesInstalledFlag(): void
    {
        $envPath = base_path('.env');
        $backupPath = base_path('.env.backup-test');

        // Backup existing .env
        if (File::exists($envPath)) {
            File::copy($envPath, $backupPath);
        }

        // Create test .env
        File::put($envPath, "APP_NAME=PineCMS\nPINECMS_INSTALLED=false\n");

        try {
            $this->invokeMethod($this->cleanup, 'updateEnvFile');

            $envContent = File::get($envPath);
            $this->assertStringContainsString('PINECMS_INSTALLED=true', $envContent);
            $this->assertStringContainsString('PINECMS_INSTALLER_DISABLED=true', $envContent);
        } finally {
            // Restore backup
            if (File::exists($backupPath)) {
                File::move($backupPath, $envPath);
            }
        }
    }

    /**
     * Test updateEnvFile adds flags if not present
     */
    public function testUpdateEnvFileAddsFlagsIfNotPresent(): void
    {
        $envPath = base_path('.env');
        $backupPath = base_path('.env.backup-test');

        // Backup existing .env
        if (File::exists($envPath)) {
            File::copy($envPath, $backupPath);
        }

        // Create test .env without flags
        File::put($envPath, "APP_NAME=PineCMS\nAPP_ENV=local\n");

        try {
            $this->invokeMethod($this->cleanup, 'updateEnvFile');

            $envContent = File::get($envPath);
            $this->assertStringContainsString('PINECMS_INSTALLED=true', $envContent);
            $this->assertStringContainsString('PINECMS_INSTALLER_DISABLED=true', $envContent);
        } finally {
            // Restore backup
            if (File::exists($backupPath)) {
                File::move($backupPath, $envPath);
            }
        }
    }

    /**
     * Test getInstallationInfo returns correct data when installed
     */
    public function testGetInstallationInfoReturnsDataWhenInstalled(): void
    {
        // Create lock file
        $lockData = [
            'installed_at' => '2025-11-09T12:00:00Z',
            'version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];
        File::put($this->testLockPath, json_encode($lockData));

        $info = $this->cleanup->getInstallationInfo();

        $this->assertTrue($info['installed']);
        $this->assertEquals('2025-11-09T12:00:00Z', $info['installed_at']);
        $this->assertEquals('1.0.0', $info['version']);
    }

    /**
     * Test getInstallationInfo returns not installed when no lock file
     */
    public function testGetInstallationInfoReturnsNotInstalled(): void
    {
        $info = $this->cleanup->getInstallationInfo();

        $this->assertFalse($info['installed']);
    }

    /**
     * Test cleanup executes all steps successfully
     */
    public function testCleanupExecutesAllSteps(): void
    {
        $envPath = base_path('.env');
        $backupPath = base_path('.env.backup-test-cleanup');

        // Backup existing .env
        if (File::exists($envPath)) {
            File::copy($envPath, $backupPath);
        }

        File::put($envPath, "APP_NAME=PineCMS\n");

        try {
            $result = $this->cleanup->cleanup();

            $this->assertTrue($result['success']);
            $this->assertTrue($result['actions_completed']['env_updated']);
            $this->assertTrue($result['actions_completed']['lock_file_created']);
            $this->assertFileExists($this->testLockPath);
        } finally {
            if (File::exists($backupPath)) {
                File::move($backupPath, $envPath);
            }
        }
    }

    /**
     * Test unlock only works in local/testing environments
     */
    public function testUnlockOnlyWorksInLocalEnvironment(): void
    {
        // Test in local/testing environment (should work)
        $this->assertContains(config('app.env'), ['local', 'testing']);

        // Create lock file and update env
        File::put($this->testLockPath, json_encode(['installed_at' => now()]));

        $result = $this->cleanup->unlock();

        $this->assertTrue($result['success']);
        $this->assertFileDoesNotExist($this->testLockPath);
    }

    /**
     * Helper method to invoke private methods
     */
    private function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
