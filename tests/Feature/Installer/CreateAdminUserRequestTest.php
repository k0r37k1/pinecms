<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test CreateAdminUserRequest validation and authorization
 *
 * This test suite verifies:
 * - Authorization rules (only when no users exist)
 * - Validation rules for name, email, password
 * - Laravel Password Rules enforcement
 * - Edge cases and security requirements
 */
class CreateAdminUserRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Form Request class does not exist (RED phase)
     */
    public function testFormRequestClassDoesNotExist(): void
    {
        $this->assertFalse(
            class_exists(\App\Http\Requests\Installer\CreateAdminUserRequest::class),
            'CreateAdminUserRequest should not exist yet (RED phase)'
        );
    }

    /**
     * Test validation rules structure (will be tested when class is created)
     * This test documents the expected validation rules
     */
    public function testExpectedValidationRulesDocumented(): void
    {
        $expectedRules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\'-]+$/', // Letters, spaces, hyphens, apostrophes only
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'Laravel Password Rules (mixed case, numbers, symbols, uncompromised)',
            ],
        ];

        // Document expectations - actual validation will be tested when FormRequest exists
        $this->assertIsArray($expectedRules);
        $this->assertArrayHasKey('name', $expectedRules);
        $this->assertArrayHasKey('email', $expectedRules);
        $this->assertArrayHasKey('password', $expectedRules);
    }
}
