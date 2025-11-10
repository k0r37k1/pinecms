<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\WebServerConfigGenerator;
use Illuminate\Http\Request;
use Tests\TestCase;

class WebServerConfigGeneratorTest extends TestCase
{
    private WebServerConfigGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new WebServerConfigGenerator;
    }

    public function testDetectSslEnabledReturnsTrueWhenHttps(): void
    {
        $request = Request::create('https://example.com', 'GET');
        $this->app->instance('request', $request);

        $result = $this->invokeMethod($this->generator, 'detectSslEnabled');

        $this->assertTrue($result);
    }

    public function testDetectSslEnabledReturnsFalseWhenHttp(): void
    {
        $request = Request::create('http://example.com', 'GET');
        $this->app->instance('request', $request);

        $result = $this->invokeMethod($this->generator, 'detectSslEnabled');

        $this->assertFalse($result);
    }

    public function testForceHttpsManualOverrideRespected(): void
    {
        $request = Request::create('http://example.com', 'GET');
        $this->app->instance('request', $request);

        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['force_https' => true],
        ]);

        $this->assertStringContainsString('RewriteCond %{HTTPS} !=on', $content);
        $this->assertStringContainsString('https://%{HTTP_HOST}', $content);
    }

    public function testBuildApacheHtaccessIncludesSecurityHeaders(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [[]]);

        $this->assertStringContainsString('X-Frame-Options', $content);
        $this->assertStringContainsString('X-Content-Type-Options', $content);
        $this->assertStringContainsString('X-XSS-Protection', $content);
        $this->assertStringContainsString('Referrer-Policy', $content);
        $this->assertStringContainsString('Permissions-Policy', $content);
    }

    public function testBuildApacheHtaccessIncludesFileProtection(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [[]]);

        $this->assertStringContainsString('<Files .env>', $content);
        $this->assertStringContainsString('RedirectMatch 403 ^/database/', $content);
        $this->assertStringContainsString('Options -Indexes', $content);
        $this->assertStringContainsString('<FilesMatch "^\\.">', $content);
    }

    public function testBuildApacheHtaccessIncludesHttpsRedirectWhenSslDetected(): void
    {
        $request = Request::create('https://example.com', 'GET');
        $this->app->instance('request', $request);

        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [[]]);

        $this->assertStringContainsString('RewriteCond %{HTTPS} !=on', $content);
        $this->assertStringContainsString('https://%{HTTP_HOST}', $content);
    }

    public function testBuildApacheHtaccessExcludesHttpsRedirectWhenNoSsl(): void
    {
        $request = Request::create('http://example.com', 'GET');
        $this->app->instance('request', $request);

        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [[]]);

        $this->assertStringNotContainsString('Force HTTPS', $content);
        $this->assertStringNotContainsString('RewriteCond %{HTTPS} !=on', $content);
    }

    public function testBuildApacheHtaccessIncludesCompressionWhenEnabled(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['enable_compression' => true],
        ]);

        $this->assertStringContainsString('mod_deflate', $content);
        $this->assertStringContainsString('AddOutputFilterByType DEFLATE', $content);
    }

    public function testBuildApacheHtaccessIncludesCachingWhenEnabled(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['enable_caching' => true],
        ]);

        $this->assertStringContainsString('mod_expires', $content);
        $this->assertStringContainsString('ExpiresActive On', $content);
        $this->assertStringContainsString('ExpiresByType', $content);
    }

    public function testBuildNginxConfigIncludesSecurityHeaders(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildNginxConfig');

        $this->assertStringContainsString('add_header X-Frame-Options', $content);
        $this->assertStringContainsString('add_header X-Content-Type-Options', $content);
        $this->assertStringContainsString('add_header X-XSS-Protection', $content);
        $this->assertStringContainsString('add_header Referrer-Policy', $content);
    }

    public function testBuildNginxConfigIncludesFileProtection(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildNginxConfig');

        $this->assertStringContainsString('location ~ /\.env', $content);
        $this->assertStringContainsString('deny all', $content);
        $this->assertStringContainsString('location ~ ^/database/', $content);
        $this->assertStringContainsString('location ~ /\.', $content);
    }

    public function testDetectWebServerReturnsApache(): void
    {
        $_SERVER['SERVER_SOFTWARE'] = 'Apache/2.4.41 (Ubuntu)';

        $result = $this->generator->detectWebServer();

        $this->assertEquals('apache', $result);
    }

    public function testDetectWebServerReturnsNginx(): void
    {
        $_SERVER['SERVER_SOFTWARE'] = 'nginx/1.18.0';

        $result = $this->generator->detectWebServer();

        $this->assertEquals('nginx', $result);
    }

    /**
     * Invoke a private or protected method
     */
    private function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
