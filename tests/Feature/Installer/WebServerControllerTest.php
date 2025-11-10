<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
    use RefreshDatabase;

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

    // Tests will be added in next tasks
}
