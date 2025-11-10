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
