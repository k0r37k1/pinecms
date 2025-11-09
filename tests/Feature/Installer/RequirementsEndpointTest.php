<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Tests\TestCase;

class RequirementsEndpointTest extends TestCase
{
    public function testRequirementsEndpointReturnsSuccess(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200);
    }

    public function testRequirementsEndpointReturnsJsonStructure(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertJsonStructure([
            'requirements' => [
                'php' => [
                    'version',
                    'meets_requirement',
                    'required_version',
                ],
                'extensions' => [
                    'required',
                    'optional',
                ],
                'permissions',
                'environment' => [
                    'memory_limit',
                    'memory_limit_bytes',
                    'memory_limit_ok',
                    'max_execution_time',
                    'max_execution_time_ok',
                    'disk_space_free',
                    'disk_space_ok',
                ],
                'overall_status',
            ],
        ]);
    }

    public function testRequirementsEndpointContainsPhpVersion(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertJsonPath('requirements.php.version', fn (string $version) => $version !== '');
        $response->assertJsonPath('requirements.php.required_version', '8.3.0');
    }

    public function testRequirementsEndpointChecksRequiredExtensions(): void
    {
        $response = $this->getJson('/installer/requirements');

        $data = $response->json();

        $this->assertIsArray($data['requirements']['extensions']['required']);
        $this->assertArrayHasKey('pdo', $data['requirements']['extensions']['required']);
        $this->assertArrayHasKey('pdo_sqlite', $data['requirements']['extensions']['required']);
        $this->assertArrayHasKey('mbstring', $data['requirements']['extensions']['required']);
    }

    public function testRequirementsEndpointChecksOptionalExtensions(): void
    {
        $response = $this->getJson('/installer/requirements');

        $data = $response->json();

        $this->assertIsArray($data['requirements']['extensions']['optional']);

        // Check structure of optional extensions
        foreach ($data['requirements']['extensions']['optional'] as $extension => $info) {
            $this->assertIsArray($info);
            $this->assertArrayHasKey('installed', $info);
            $this->assertArrayHasKey('benefit', $info);
            $this->assertIsBool($info['installed']);
            $this->assertIsString($info['benefit']);
        }
    }

    public function testRequirementsEndpointChecksPermissions(): void
    {
        $response = $this->getJson('/installer/requirements');

        $data = $response->json();

        $this->assertIsArray($data['requirements']['permissions']);

        // Check structure of permissions
        foreach ($data['requirements']['permissions'] as $directory => $permission) {
            $this->assertIsArray($permission);
            $this->assertArrayHasKey('path', $permission);
            $this->assertArrayHasKey('exists', $permission);
            $this->assertArrayHasKey('writable', $permission);
        }
    }

    public function testRequirementsEndpointChecksEnvironment(): void
    {
        $response = $this->getJson('/installer/requirements');

        $data = $response->json();

        $this->assertIsString($data['requirements']['environment']['memory_limit']);
        $this->assertIsInt($data['requirements']['environment']['memory_limit_bytes']);
        $this->assertIsBool($data['requirements']['environment']['memory_limit_ok']);
        $this->assertIsInt($data['requirements']['environment']['max_execution_time']);
        $this->assertIsBool($data['requirements']['environment']['max_execution_time_ok']);
        $this->assertIsInt($data['requirements']['environment']['disk_space_free']);
        $this->assertIsBool($data['requirements']['environment']['disk_space_ok']);
    }

    public function testRequirementsEndpointReturnsOverallStatus(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertJsonPath('requirements.overall_status', fn (bool $status) => is_bool($status));
    }

    public function testRequirementsEndpointReturnsResolutionInstructionsWhenAvailable(): void
    {
        $response = $this->getJson('/installer/requirements?include_instructions=1');

        $response->assertStatus(200);

        // Should have instructions key when requested
        $data = $response->json();
        $this->assertArrayHasKey('instructions', $data);
        $this->assertIsArray($data['instructions']);
    }
}
