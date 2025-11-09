<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Tests\TestCase;

class RequirementsControllerTest extends TestCase
{
    public function testRequirementsCheckReturnsSuccessfulResponse(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'requirements' => [
                    'php' => ['version', 'meets_requirement', 'required_version'],
                    'extensions' => ['required', 'optional'],
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

    public function testRequirementsCheckIncludesInstructionsWhenRequested(): void
    {
        $response = $this->getJson('/installer/requirements?include_instructions=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'requirements',
                'instructions',
            ]);
    }

    public function testRequirementsCheckExcludesInstructionsByDefault(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200)
            ->assertJsonMissing(['instructions' => []]);
    }

    public function testRequirementsCheckReturnsValidPhpVersion(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200);

        $data = $response->json();

        $this->assertArrayHasKey('requirements', $data);
        $this->assertArrayHasKey('php', $data['requirements']);
        $this->assertNotEmpty($data['requirements']['php']['version']);
        $this->assertIsString($data['requirements']['php']['version']);
    }

    public function testRequirementsCheckValidatesRequiredExtensions(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200);

        $data = $response->json();
        $extensions = $data['requirements']['extensions']['required'];

        $this->assertIsArray($extensions);
        $this->assertArrayHasKey('pdo', $extensions);
        $this->assertArrayHasKey('pdo_sqlite', $extensions);
        $this->assertArrayHasKey('mbstring', $extensions);
        $this->assertIsBool($extensions['pdo']);
    }

    public function testRequirementsCheckValidatesOptionalExtensions(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200);

        $data = $response->json();
        $optionalExtensions = $data['requirements']['extensions']['optional'];

        $this->assertIsArray($optionalExtensions);

        foreach ($optionalExtensions as $extension => $info) {
            $this->assertIsArray($info);
            $this->assertArrayHasKey('installed', $info);
            $this->assertArrayHasKey('benefit', $info);
            $this->assertIsBool($info['installed']);
            $this->assertIsString($info['benefit']);
        }
    }

    public function testRequirementsCheckValidatesPermissions(): void
    {
        $response = $this->getJson('/installer/requirements');

        $response->assertStatus(200);

        $data = $response->json();
        $permissions = $data['requirements']['permissions'];

        $this->assertIsArray($permissions);

        foreach ($permissions as $permission) {
            $this->assertArrayHasKey('path', $permission);
            $this->assertArrayHasKey('exists', $permission);
            $this->assertArrayHasKey('writable', $permission);
            $this->assertIsBool($permission['exists']);
            $this->assertIsBool($permission['writable']);
        }
    }
}
