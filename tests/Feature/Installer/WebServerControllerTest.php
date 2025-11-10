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

    public function testGeneratedHtaccessContainsValidApacheSyntax(): void
    {
        $response = $this->postJson('/installer/webserver/apache', [
            'force_https' => true,
            'enable_compression' => true,
            'enable_caching' => true,
        ]);

        $response->assertStatus(201);

        $content = File::get($this->htaccessPath);

        // Check for essential Apache directives
        $this->assertStringContainsString('<IfModule mod_rewrite.c>', $content);
        $this->assertStringContainsString('RewriteEngine On', $content);
        $this->assertStringContainsString('</IfModule>', $content);

        // Check for OWASP security headers
        $this->assertStringContainsString('X-Content-Type-Options', $content);
        $this->assertStringContainsString('X-Frame-Options', $content);

        // Check for file protection
        $this->assertStringContainsString('Deny from all', $content);
    }

    public function testSslAutoDetectionEnablesHttpsRedirect(): void
    {
        // Make an HTTPS request to trigger SSL detection
        $response = $this->postJson('https://localhost/installer/webserver/apache', [
            // No force_https parameter - rely on auto-detection
            'enable_compression' => true,
            'enable_caching' => true,
        ]);

        $response->assertStatus(201);

        $content = File::get($this->htaccessPath);

        // Check for HTTPS redirect
        $this->assertStringContainsString('RewriteCond %{HTTPS} !=on', $content);
        $this->assertStringContainsString('https://%{HTTP_HOST}', $content);
    }

    public function testManualOverrideDisablesHttpsDespiteSslPresent(): void
    {
        // Make an HTTPS request but explicitly disable force_https
        $response = $this->postJson('https://localhost/installer/webserver/apache', [
            'force_https' => false, // Explicit override
            'enable_compression' => true,
            'enable_caching' => true,
        ]);

        $response->assertStatus(201);

        $content = File::get($this->htaccessPath);

        // Check that HTTPS redirect is NOT present
        $this->assertStringNotContainsString('Force HTTPS', $content);
    }
}
