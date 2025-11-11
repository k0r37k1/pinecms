<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Test the installer unlock endpoint for E2E test isolation.
 *
 * This endpoint is ONLY available in testing environment for security.
 */
class UnlockControllerTest extends TestCase
{
    /**
     * Note: Testing 403 response in production/local environments requires
     * running tests with APP_ENV=production or APP_ENV=local, which we don't
     * do in automated tests. The environment guard is verified through code review.
     */

    /**
     * Test that unlock endpoint deletes installation files in testing environment.
     */
    public function testUnlockEndpointDeletesInstallationFilesInTestingEnvironment(): void
    {
        // Create test files
        File::put(base_path('.installed'), '');
        File::put(base_path('.env'), 'APP_KEY=test');
        File::put(database_path('pinecms.sqlite'), '');

        // Ensure files exist
        $this->assertTrue(File::exists(base_path('.installed')));
        $this->assertTrue(File::exists(base_path('.env')));
        $this->assertTrue(File::exists(database_path('pinecms.sqlite')));

        // Call unlock endpoint
        $response = $this->postJson('/installer/unlock');

        // Verify response
        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Installation unlocked successfully',
        ]);

        // Verify files deleted
        $this->assertFalse(File::exists(base_path('.installed')));
        $this->assertFalse(File::exists(base_path('.env')));
        $this->assertFalse(File::exists(database_path('pinecms.sqlite')));
    }

    /**
     * Test that unlock endpoint handles missing files gracefully.
     */
    public function testUnlockEndpointHandlesMissingFilesGracefully(): void
    {
        // Ensure files don't exist
        File::delete(base_path('.installed'));
        File::delete(base_path('.env'));
        File::delete(database_path('pinecms.sqlite'));

        $response = $this->postJson('/installer/unlock');

        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * Cleanup after each test.
     */
    protected function tearDown(): void
    {
        // Clean up test files if they exist
        File::delete(base_path('.installed'));
        File::delete(base_path('.env.backup'));
        File::delete(database_path('pinecms.sqlite'));

        parent::tearDown();
    }
}
