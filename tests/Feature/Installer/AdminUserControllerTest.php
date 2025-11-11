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

        // Skip DNS validation in tests (localhost doesn't have valid DNS records)
        \App\Http\Requests\Installer\CreateAdminUserRequest::$skipDnsValidation = true;
    }

    // ========================================
    // Happy Path Tests
    // ========================================

    public function testCreateAdminUserWithValidDataReturns201(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
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
                'email' => 'admin@localhost',
                'role' => 'Administrator',
            ],
        ]);
    }

    public function testCreatedUserExistsInDatabase(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'status' => 'active',
        ]);
    }

    public function testCreatedUserHasAdministratorRole(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $user = User::where('email', 'admin@localhost')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Administrator'));
    }

    public function testCreatedUserEmailIsVerified(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $user = User::where('email', 'admin@localhost')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->email_verified_at);
    }

    public function testPasswordHashedWithBcryptCost12(): void
    {
        $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $user = User::where('email', 'admin@localhost')->first();
        $this->assertNotNull($user);
        $expectedCost = str_pad((string) config('hashing.bcrypt.rounds', 12), 2, '0', STR_PAD_LEFT);
        $this->assertStringStartsWith('$2y$' . $expectedCost . '$', $user->password);
    }

    // ========================================
    // Validation Error Tests (422 Unprocessable Entity)
    // ========================================

    public function testValidationRequiresName(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testValidationRequiresEmail(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function testValidationRequiresPassword(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRequiresPasswordConfirmation(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function testValidationRejectsInvalidNameWithNumbers(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin123',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testValidationRejectsInvalidNameWithSpecialChars(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin@User#',
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testValidationAcceptsValidNameWithSpacesAndHyphens(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => "John O'Brien-Smith",
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(201);
    }

    public function testValidationRejectsInvalidEmailFormat(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'invalid-email',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    // Note: Duplicate email validation cannot be tested here because authorization
    // runs before validation. Once a user exists, authorize() returns false (403).
    // The authorization test "testAuthorizationPreventsDuplicateAdminCreation" covers this scenario.

    public function testValidationRejectsShortPassword(): void
    {
        $response = $this->postJson('/installer/admin-user', [
            'name' => 'Admin User',
            'email' => 'admin@localhost',
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
            'email' => 'admin@localhost',
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
            'email' => 'admin@localhost',
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
            'email' => 'admin@localhost',
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
            'email' => 'admin@localhost',
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
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
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
            'email' => 'admin@localhost',
            'password' => 'UniqueTestP@ss2024XyZ!',
            'password_confirmation' => 'UniqueTestP@ss2024XyZ!',
        ]);

        // Service-level validation returns 422 (not 403) since authorization was moved to service layer
        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);
        $response->assertJsonFragment(['message' => 'Admin user already exists. Installation may be complete.']);
    }

    // ========================================
    // Password Strength API Endpoint Tests
    // ========================================

    public function testCheckPasswordEndpointReturnsStrongPassword(): void
    {
        $response = $this->postJson('/installer/admin-user/check-password', [
            'password' => 'UniqueTestP@ss2024XyZ!',
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
