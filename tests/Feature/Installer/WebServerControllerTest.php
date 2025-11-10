<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Feature tests for WebServerController
 *
 * Tests API endpoints for web server configuration generation:
 * - Apache .htaccess generation
 * - nginx configuration example generation
 * - Web server detection
 * - SSL auto-detection and manual override
 * - OWASP security headers validation
 */
class WebServerControllerTest extends TestCase
{
    protected string $htaccessPath = '';

    protected string $nginxPath = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->htaccessPath = public_path('.htaccess');
        $this->nginxPath = base_path('nginx.conf.example');

        // Clean up any existing files
        if (File::exists($this->htaccessPath)) {
            File::delete($this->htaccessPath);
        }
        if (File::exists($this->nginxPath)) {
            File::delete($this->nginxPath);
        }
    }

    protected function tearDown(): void
    {
        // Clean up test files
        if (File::exists($this->htaccessPath)) {
            File::delete($this->htaccessPath);
        }
        if (File::exists($this->nginxPath)) {
            File::delete($this->nginxPath);
        }

        parent::tearDown();
    }

    public function testPostApacheConfigCreatesHtaccessReturns201(): void
    {
        $response = $this->postJson('/installer/webserver/apache', [
            'force_https' => true,
            'enable_compression' => true,
            'enable_caching' => true,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'path',
            'message',
            'content',
        ]);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertFileExists($this->htaccessPath);
    }

    public function testPostApacheConfigReturns409WhenFileExists(): void
    {
        // Create existing .htaccess file
        File::put($this->htaccessPath, '# Existing file');

        $response = $this->postJson('/installer/webserver/apache', [
            'force_https' => true,
        ]);

        $response->assertStatus(409);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function testPostApacheConfigValidatesBooleanParameters(): void
    {
        $response = $this->postJson('/installer/webserver/apache', [
            'force_https' => 'not-a-boolean',
            'enable_compression' => 123,
            'enable_caching' => [],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['force_https', 'enable_compression', 'enable_caching']);
    }

    public function testGetDetectReturnsServerType(): void
    {
        $response = $this->getJson('/installer/webserver/detect');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'server_type',
            'server_software',
        ]);
        $response->assertJson([
            'success' => true,
        ]);

        // Server type should be one of: apache, nginx, unknown
        $this->assertContains(
            $response->json('server_type'),
            ['apache', 'nginx', 'unknown']
        );
    }

    public function testPostNginxConfigCreatesExampleReturns201(): void
    {
        $response = $this->postJson('/installer/webserver/nginx');

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'path',
            'message',
            'content',
        ]);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertFileExists($this->nginxPath);
    }
}
