<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\CronDetector;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CronDetectorTest extends TestCase
{
    private CronDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new CronDetector;
    }

    /**
     * Test detect returns cron availability information
     */
    public function testDetectReturnsAvailabilityInfo(): void
    {
        $result = $this->detector->detect();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('available', $result);
        $this->assertArrayHasKey('cron_binary', $result);
        $this->assertArrayHasKey('php_binary', $result);
        $this->assertArrayHasKey('base_path', $result);
        $this->assertArrayHasKey('cron_command', $result);
    }

    /**
     * Test detect returns correct data types
     */
    public function testDetectReturnsCorrectDataTypes(): void
    {
        $result = $this->detector->detect();

        $this->assertIsBool($result['available']);
        $this->assertTrue(
            is_string($result['cron_binary']) || is_null($result['cron_binary'])
        );
        $this->assertIsString($result['php_binary']);
        $this->assertIsString($result['base_path']);
        $this->assertIsString($result['cron_command']);
    }

    /**
     * Test detect returns PHP binary from PHP_BINARY constant
     */
    public function testDetectReturnsPhpBinary(): void
    {
        $result = $this->detector->detect();

        $this->assertEquals(PHP_BINARY, $result['php_binary']);
    }

    /**
     * Test detect returns correct base path
     */
    public function testDetectReturnsBasePath(): void
    {
        $result = $this->detector->detect();

        $this->assertEquals(base_path(), $result['base_path']);
    }

    /**
     * Test detect generates correct cron command format
     */
    public function testDetectGeneratesCorrectCronCommand(): void
    {
        $result = $this->detector->detect();

        $expectedCommand = sprintf(
            '* * * * * cd %s && %s artisan schedule:run >> /dev/null 2>&1',
            base_path(),
            PHP_BINARY
        );

        $this->assertEquals($expectedCommand, $result['cron_command']);
    }

    /**
     * Test setSchedulerMode updates .env file
     */
    public function testSetSchedulerModeUpdatesEnvFile(): void
    {
        $envPath = base_path('.env');
        $backupPath = base_path('.env.backup-test-cron');

        // Backup existing .env
        if (File::exists($envPath)) {
            File::copy($envPath, $backupPath);
        }

        // Create test .env
        File::put($envPath, "APP_NAME=PineCMS\n");

        try {
            $this->detector->setSchedulerMode('visit-triggered');

            $envContent = File::get($envPath);
            $this->assertStringContainsString('SCHEDULER_MODE=visit-triggered', $envContent);
        } finally {
            // Restore backup
            if (File::exists($backupPath)) {
                File::move($backupPath, $envPath);
            }
        }
    }

    /**
     * Test setSchedulerMode replaces existing scheduler mode
     */
    public function testSetSchedulerModeReplacesExistingMode(): void
    {
        $envPath = base_path('.env');
        $backupPath = base_path('.env.backup-test-cron-replace');

        // Backup existing .env
        if (File::exists($envPath)) {
            File::copy($envPath, $backupPath);
        }

        // Create test .env with existing mode
        File::put($envPath, "APP_NAME=PineCMS\nSCHEDULER_MODE=cron\n");

        try {
            $this->detector->setSchedulerMode('visit-triggered');

            $envContent = File::get($envPath);
            $this->assertStringContainsString('SCHEDULER_MODE=visit-triggered', $envContent);
            $this->assertStringNotContainsString('SCHEDULER_MODE=cron', $envContent);
        } finally {
            // Restore backup
            if (File::exists($backupPath)) {
                File::move($backupPath, $envPath);
            }
        }
    }

    /**
     * Test setSchedulerMode validates allowed modes
     */
    public function testSetSchedulerModeValidatesAllowedModes(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid scheduler mode');

        $this->detector->setSchedulerMode('invalid-mode');
    }

    /**
     * Test testCronExecution returns result array
     */
    public function testTestCronExecutionReturnsResultArray(): void
    {
        $result = $this->detector->testCronExecution();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
    }

    /**
     * Test testCronExecution result has correct data types
     */
    public function testTestCronExecutionResultHasCorrectDataTypes(): void
    {
        $result = $this->detector->testCronExecution();

        $this->assertIsBool($result['success']);
        $this->assertIsString($result['message']);
    }
}
