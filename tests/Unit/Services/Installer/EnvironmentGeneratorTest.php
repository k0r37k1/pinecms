<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\EnvironmentGenerator;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class EnvironmentGeneratorTest extends TestCase
{
    private EnvironmentGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new EnvironmentGenerator;
    }

    public function testGenerateAppKeyFormat(): void
    {
        $key = $this->invokePrivateMethod('generateAppKey');

        $this->assertStringStartsWith('base64:', $key);
        $this->assertEquals(51, strlen($key)); // 'base64:' + 44 chars
    }

    public function testGenerateAppKeyIsUnique(): void
    {
        $key1 = $this->invokePrivateMethod('generateAppKey');
        $key2 = $this->invokePrivateMethod('generateAppKey');

        $this->assertNotEquals($key1, $key2);
    }

    public function testValidateConfigRequiresAppName(): void
    {
        $result = $this->generator->validateConfig([
            'app_url' => 'https://example.com',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('app_name', $result['errors']);
    }

    public function testValidateConfigRequiresAppUrl(): void
    {
        $result = $this->generator->validateConfig([
            'app_name' => 'Test CMS',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('app_url', $result['errors']);
    }

    public function testValidateConfigRequiresValidUrl(): void
    {
        $result = $this->generator->validateConfig([
            'app_name' => 'Test',
            'app_url' => 'not-a-url',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('app_url', $result['errors']);
    }

    public function testValidateConfigRejectsInvalidEmail(): void
    {
        $result = $this->generator->validateConfig([
            'app_name' => 'Test',
            'app_url' => 'https://example.com',
            'admin_email' => 'not-an-email',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('admin_email', $result['errors']);
    }

    public function testValidateConfigAcceptsValidConfiguration(): void
    {
        $result = $this->generator->validateConfig([
            'app_name' => 'Test CMS',
            'app_url' => 'https://example.com',
            'admin_email' => 'admin@example.com',
        ]);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    public function testGetDatabasePathSanitizesFilename(): void
    {
        $path = $this->invokePrivateMethod('getDatabasePath', ['test@db#name']);

        $this->assertStringContainsString('test_db_name', $path);
        $this->assertStringEndsWith('.sqlite', $path);
    }

    public function testGetDatabasePathHandlesSpecialCharacters(): void
    {
        $path = $this->invokePrivateMethod('getDatabasePath', ['my!@#$%^&*()db']);

        $this->assertStringContainsString('my__________db', $path);
        $this->assertStringEndsWith('.sqlite', $path);
    }

    public function testExtractDomainFromUrl(): void
    {
        $domain = $this->invokePrivateMethod('extractDomain', ['https://example.com/path']);

        $this->assertEquals('example.com', $domain);
    }

    public function testExtractDomainFromUrlWithPort(): void
    {
        $domain = $this->invokePrivateMethod('extractDomain', ['http://localhost:8000']);

        $this->assertEquals('localhost', $domain);
    }

    public function testExtractDomainHandlesInvalidUrl(): void
    {
        $domain = $this->invokePrivateMethod('extractDomain', ['not-a-url']);

        $this->assertEquals('example.com', $domain);
    }

    public function testBuildEnvironmentContentIncludesRequiredKeys(): void
    {
        $config = [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
        ];

        $content = $this->invokePrivateMethod('buildEnvironmentContent', [$config]);

        $this->assertStringContainsString('APP_NAME="Test CMS"', $content);
        $this->assertStringContainsString('APP_URL=https://test.com', $content);
        $this->assertStringContainsString('APP_ENV=production', $content);
        $this->assertStringContainsString('APP_DEBUG=false', $content);
        $this->assertStringContainsString('APP_KEY=base64:', $content);
        $this->assertStringContainsString('DB_CONNECTION=sqlite', $content);
        $this->assertStringContainsString('SESSION_DRIVER=database', $content);
        $this->assertStringContainsString('CACHE_STORE=file', $content);
        $this->assertStringContainsString('QUEUE_CONNECTION=database', $content);
    }

    public function testBuildEnvironmentContentUsesCustomTimezone(): void
    {
        $config = [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'timezone' => 'America/New_York',
        ];

        $content = $this->invokePrivateMethod('buildEnvironmentContent', [$config]);

        $this->assertStringContainsString('APP_TIMEZONE=America/New_York', $content);
    }

    public function testBuildEnvironmentContentUsesCustomDatabaseName(): void
    {
        $config = [
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
            'db_name' => 'custom_db',
        ];

        $content = $this->invokePrivateMethod('buildEnvironmentContent', [$config]);

        $this->assertStringContainsString('custom_db.sqlite', $content);
    }

    public function testGeneratePreventsOverwritingExistingEnv(): void
    {
        $envPath = base_path('.env');
        File::shouldReceive('exists')
            ->once()
            ->with($envPath)
            ->andReturn(true);

        $result = $this->generator->generate([
            'app_name' => 'Test',
            'app_url' => 'https://test.com',
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already exists', $result['message']);
    }

    public function testGenerateCreatesEnvFileSuccessfully(): void
    {
        $envPath = base_path('.env');
        $tempPath = $envPath . '.tmp';

        File::shouldReceive('exists')
            ->once()
            ->with($envPath)
            ->andReturn(false);

        File::shouldReceive('put')
            ->once()
            ->with($tempPath, \Mockery::type('string'))
            ->andReturn(100);

        File::shouldReceive('move')
            ->once()
            ->with($tempPath, $envPath)
            ->andReturn(true);

        File::shouldReceive('delete')
            ->never();

        $result = $this->generator->generate([
            'app_name' => 'Test CMS',
            'app_url' => 'https://test.com',
        ]);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('created successfully', $result['message']);
    }

    public function testGenerateHandlesWriteFailure(): void
    {
        $envPath = base_path('.env');
        $tempPath = $envPath . '.tmp';

        File::shouldReceive('exists')
            ->once()
            ->with($envPath)
            ->andReturn(false);

        File::shouldReceive('put')
            ->once()
            ->with($tempPath, \Mockery::type('string'))
            ->andReturn(false);

        $result = $this->generator->generate([
            'app_name' => 'Test',
            'app_url' => 'https://test.com',
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Failed to write', $result['message']);
    }

    /**
     * Helper to invoke private methods for testing
     *
     * @param  array<int, mixed>  $parameters
     */
    private function invokePrivateMethod(string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass($this->generator);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->generator, $parameters);
    }
}
