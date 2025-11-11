<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Installer;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CronControllerTest extends TestCase
{
    /**
     * Test detect endpoint returns cron information
     */
    public function testDetectEndpointReturnsCronInformation(): void
    {
        $response = $this->getJson('/installer/cron/detect');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'available',
                'cron_binary',
                'php_binary',
                'base_path',
                'cron_command',
            ],
        ]);
    }

    /**
     * Test set-mode endpoint updates scheduler mode
     */
    public function testSetModeEndpointUpdatesSchedulerMode(): void
    {
        $envPath = base_path('.env');
        $backupPath = base_path('.env.backup-test-cron-controller');

        // Backup existing .env
        if (File::exists($envPath)) {
            File::copy($envPath, $backupPath);
        }

        File::put($envPath, "APP_NAME=PineCMS\n");

        try {
            $response = $this->postJson('/installer/cron/set-mode', [
                'mode' => 'visit-triggered',
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'success' => true,
            ]);

            // Verify .env was updated
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
     * Test set-mode endpoint validates mode parameter
     */
    public function testSetModeEndpointValidatesModeParameter(): void
    {
        $response = $this->postJson('/installer/cron/set-mode', [
            'mode' => 'invalid-mode',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['mode']);
    }

    /**
     * Test set-mode endpoint requires mode parameter
     */
    public function testSetModeEndpointRequiresModeParameter(): void
    {
        $response = $this->postJson('/installer/cron/set-mode', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['mode']);
    }

    /**
     * Test test endpoint returns test result
     */
    public function testTestEndpointReturnsTestResult(): void
    {
        $response = $this->getJson('/installer/cron/test');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
    }
}
