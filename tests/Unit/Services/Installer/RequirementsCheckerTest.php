<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\RequirementsChecker;
use Tests\TestCase;

class RequirementsCheckerTest extends TestCase
{
    private RequirementsChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checker = new RequirementsChecker;
    }

    public function testConvertToBytesWithMegabytes(): void
    {
        $result = $this->invokePrivateMethod('convertToBytes', ['256M']);

        $this->assertEquals(256 * 1024 * 1024, $result);
    }

    public function testConvertToBytesWithGigabytes(): void
    {
        $result = $this->invokePrivateMethod('convertToBytes', ['2G']);

        $this->assertEquals(2 * 1024 * 1024 * 1024, $result);
    }

    public function testConvertToBytesWithKilobytes(): void
    {
        $result = $this->invokePrivateMethod('convertToBytes', ['512K']);

        $this->assertEquals(512 * 1024, $result);
    }

    public function testConvertToBytesWithUnlimited(): void
    {
        $result = $this->invokePrivateMethod('convertToBytes', ['-1']);

        $this->assertEquals(-1, $result);
    }

    public function testConvertToBytesWithPlainNumber(): void
    {
        $result = $this->invokePrivateMethod('convertToBytes', ['1024']);

        $this->assertEquals(1024, $result);
    }

    public function testCheckPhpVersionReturnsCorrectStructure(): void
    {
        $result = $this->invokePrivateMethod('checkPhpVersion');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('version', $result);
        $this->assertArrayHasKey('meets_requirement', $result);
        $this->assertArrayHasKey('required_version', $result);
        $this->assertIsString($result['version']);
        $this->assertIsBool($result['meets_requirement']);
        $this->assertEquals('8.3.0', $result['required_version']);
    }

    public function testCheckExtensionsReturnsCorrectStructure(): void
    {
        $result = $this->invokePrivateMethod('checkExtensions');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('required', $result);
        $this->assertArrayHasKey('optional', $result);
        $this->assertIsArray($result['required']);
        $this->assertIsArray($result['optional']);
    }

    public function testCheckPermissionsReturnsCorrectStructure(): void
    {
        $result = $this->invokePrivateMethod('checkPermissions');

        $this->assertIsArray($result);
        foreach ($result as $dir => $permission) {
            $this->assertArrayHasKey('path', $permission);
            $this->assertArrayHasKey('exists', $permission);
            $this->assertArrayHasKey('writable', $permission);
            $this->assertIsString($permission['path']);
            $this->assertIsBool($permission['exists']);
            $this->assertIsBool($permission['writable']);
        }
    }

    public function testCheckEnvironmentReturnsCorrectStructure(): void
    {
        $result = $this->invokePrivateMethod('checkEnvironment');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('memory_limit', $result);
        $this->assertArrayHasKey('memory_limit_bytes', $result);
        $this->assertArrayHasKey('memory_limit_ok', $result);
        $this->assertArrayHasKey('max_execution_time', $result);
        $this->assertArrayHasKey('max_execution_time_ok', $result);
        $this->assertArrayHasKey('disk_space_free', $result);
        $this->assertArrayHasKey('disk_space_ok', $result);
    }

    public function testCheckAllReturnsCompleteStructure(): void
    {
        $result = $this->checker->checkAll();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('php', $result);
        $this->assertArrayHasKey('extensions', $result);
        $this->assertArrayHasKey('permissions', $result);
        $this->assertArrayHasKey('environment', $result);
        $this->assertArrayHasKey('overall_status', $result);
        $this->assertIsBool($result['overall_status']);
    }

    public function testGetResolutionInstructionsReturnsArray(): void
    {
        $requirements = $this->checker->checkAll();
        $instructions = $this->checker->getResolutionInstructions($requirements);

        $this->assertIsArray($instructions);
    }

    public function testGetResolutionInstructionsProvidesPhpVersionMessageWhenInadequate(): void
    {
        // Simulate failed requirements
        $requirements = [
            'php' => [
                'version' => '7.4.0',
                'meets_requirement' => false,
                'required_version' => '8.3.0',
            ],
            'extensions' => ['required' => [], 'optional' => []],
            'permissions' => [],
            'environment' => [
                'memory_limit' => '128M',
                'memory_limit_bytes' => 134217728,
                'memory_limit_ok' => true,
                'max_execution_time' => 30,
                'max_execution_time_ok' => true,
                'disk_space_free' => 1073741824,
                'disk_space_ok' => true,
            ],
        ];

        $instructions = $this->checker->getResolutionInstructions($requirements);

        $this->assertArrayHasKey('php_version', $instructions);
        $this->assertStringContainsString('8.3.0', $instructions['php_version']);
        $this->assertStringContainsString('7.4.0', $instructions['php_version']);
    }

    /**
     * Helper to invoke private methods for testing
     *
     * @param  array<int, mixed>  $parameters
     */
    private function invokePrivateMethod(string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass($this->checker);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->checker, $parameters);
    }
}
