<?php

declare(strict_types=1);

namespace Tests\Feature\Installer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    // ========================================
    // Happy Path Tests
    // ========================================

    public function testCreateAdminUserWithValidDataReturns201(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'user' => ['id', 'name', 'email', 'role'],
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Admin user created successfully.',
            'user' => [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'Administrator',
            ],
        ]);
    }

    public function testCreatedUserExistsInDatabase(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'status' => 'active',
        ]);
    }

    public function testCreatedUserHasAdministratorRole(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $user = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Administrator'));
    }

    public function testCreatedUserEmailIsVerified(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $user = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->email_verified_at);
    }

    public function testPasswordHashedWithBcryptCost12(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $user = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertStringStartsWith('$2y$12$', $user->password);
    }

    // ========================================
    // Validation Error Tests (422 Unprocessable Entity)
    // ========================================

    public function testValidationRequiresName(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testValidationRequiresEmail(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function testValidationRequiresPassword(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRequiresPasswordConfirmation(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsInvalidNameWithNumbers(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin123',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testValidationRejectsInvalidNameWithSpecialChars(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin@User#',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testValidationAcceptsValidNameWithSpacesAndHyphens(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => "John O'Brien-Smith",
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(201);
    }

    public function testValidationRejectsInvalidEmailFormat(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'invalid-email',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function testValidationRejectsDuplicateEmail(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function testValidationRejectsShortPassword(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'Short1!',
            'password_confirmation' => 'Short1!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsPasswordWithoutUppercase(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'lowercase123!@#',
            'password_confirmation' => 'lowercase123!@#',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsPasswordWithoutLowercase(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'UPPERCASE123!@#',
            'password_confirmation' => 'UPPERCASE123!@#',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsPasswordWithoutNumbers(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'NoNumbersHere!@#',
            'password_confirmation' => 'NoNumbersHere!@#',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsPasswordWithoutSymbols(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'NoSpecialChar123',
            'password_confirmation' => 'NoSpecialChar123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsPasswordConfirmationMismatch(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    // ========================================
    // Authorization Error Tests (403 Forbidden)
    // ========================================

    public function testAuthorizationPreventsDuplicateAdminCreation(): void
    {
        User::factory()->create();

        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123',
            'password_confirmation' => 'SecureP@ssw0rd123',
        ]);

        $response->assertStatus(403);
    }

    // ========================================
    // Password Strength API Endpoint Tests
    // ========================================

    public function testCheckPasswordEndpointReturnsStrongPassword(): void
    {
        $response = $this->postJson('/installer/admin-user/check-password', [
            'password' => 'SecureP@ssw0rd123!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'errors' => [],
            'strength' => [
                'score' => 5,
                'label' => 'Strong',
                'color' => 'green',
            ],
        ]);
    }

    public function testCheckPasswordEndpointReturnsWeakPassword(): void
    {
        $response = $this->postJson('/installer/admin-user/check-password', [
            'password' => 'weak',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'valid',
            'errors',
            'strength' => ['score', 'label', 'color'],
        ]);
        $this->assertFalse($response->json('valid'));
        $this->assertGreaterThan(0, count($response->json('errors')));
    }

    public function testCheckPasswordEndpointRequiresPasswordField(): void
    {
        $response = $this->postJson('/installer/admin-user/check-password', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testCheckPasswordEndpointReturnsMediumStrength(): void
    {
        $response = $this->postJson('/installer/admin-user/check-password', [
            'password' => 'Medium@Pass1',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'valid',
            'errors',
            'strength' => ['score', 'label', 'color'],
        ]);
    }
}
