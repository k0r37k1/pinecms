<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class EnvironmentGenerationTest extends TestCase
{
    private string $envPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->envPath = base_path('.env');

        // Backup existing .env if it exists
        if (File::exists($this->envPath)) {
            File::move($this->envPath, $this->envPath . '.backup');
        }
    }

    protected function tearDown(): void
    {
        // Clean up test .env
        if (File::exists($this->envPath)) {
            File::delete($this->envPath);
        }

        // Restore original .env
        if (File::exists($this->envPath . '.backup')) {
            File::move($this->envPath . '.backup', $this->envPath);
        }

        parent::tearDown();
    }

    public function testEnvironmentEndpointCreatesEnvFile(): void
    {
        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'timezone' => 'UTC',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'env_path',
        ]);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertTrue(File::exists($this->envPath));
    }

    public function testEnvironmentEndpointValidatesRequiredFields(): void
    {
        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            // Missing app_url
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['app_url']);
    }

    public function testEnvironmentEndpointValidatesAppUrl(): void
    {
        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'not-a-valid-url',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['app_url']);
    }

    public function testEnvironmentEndpointValidatesEmailFormat(): void
    {
        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'admin_email' => 'invalid-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['admin_email']);
    }

    public function testEnvironmentEndpointValidatesDatabaseName(): void
    {
        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'db_name' => 'invalid@name#here',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['db_name']);
    }

    public function testEnvironmentEndpointValidatesTimezone(): void
    {
        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'timezone' => 'Invalid/Timezone',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['timezone']);
    }

    public function testEnvironmentEndpointPreventsOverwrite(): void
    {
        // Create existing .env
        File::put($this->envPath, 'APP_NAME=Existing');

        $response = $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
        ]);

        $response->assertStatus(409);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonPath('message', fn (string $message) => str_contains($message, 'already exists'));
    }

    public function testGeneratedEnvHasCorrectStructure(): void
    {
        $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'timezone' => 'America/New_York',
            'db_name' => 'custom_db',
        ]);

        $this->assertTrue(File::exists($this->envPath));

        $content = File::get($this->envPath);

        // Check required keys
        $this->assertStringContainsString('APP_NAME="Test CMS"', $content);
        $this->assertStringContainsString('APP_URL=https://test.com', $content);
        $this->assertStringContainsString('APP_ENV=production', $content);
        $this->assertStringContainsString('APP_DEBUG=false', $content);
        $this->assertStringContainsString('APP_TIMEZONE=America/New_York', $content);

        // Check APP_KEY format
        $this->assertMatchesRegularExpression('/APP_KEY=base64:[A-Za-z0-9+\/]{43}=/', $content);

        // Check database config
        $this->assertStringContainsString('DB_CONNECTION=sqlite', $content);
        $this->assertStringContainsString('custom_db.sqlite', $content);

        // Check session config
        $this->assertStringContainsString('SESSION_DRIVER=database', $content);
        $this->assertStringContainsString('SESSION_SECURE_COOKIE=true', $content);
        $this->assertStringContainsString('SESSION_HTTP_ONLY=true', $content);

        // Check cache config
        $this->assertStringContainsString('CACHE_STORE=file', $content);

        // Check queue config
        $this->assertStringContainsString('QUEUE_CONNECTION=database', $content);
    }

    public function testGeneratedEnvHasSecureDefaults(): void
    {
        $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
        ]);

        $content = File::get($this->envPath);

        // Production settings
        $this->assertStringContainsString('APP_ENV=production', $content);
        $this->assertStringContainsString('APP_DEBUG=false', $content);

        // Security settings
        $this->assertStringContainsString('SESSION_SECURE_COOKIE=true', $content);
        $this->assertStringContainsString('SESSION_HTTP_ONLY=true', $content);
        $this->assertStringContainsString('SESSION_SAME_SITE=lax', $content);
        $this->assertStringContainsString('BCRYPT_ROUNDS=12', $content);
    }

    public function testGeneratedEnvFileHasRestrictivePermissions(): void
    {
        $this->postJson('/installer/environment', [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
        ]);

        $this->assertTrue(File::exists($this->envPath));

        // Check file permissions (0600 = owner read/write only)
        $permissions = fileperms($this->envPath) & 0777;
        $this->assertEquals(0600, $permissions, 'File permissions should be 0600 (owner read/write only)');
    }
}
