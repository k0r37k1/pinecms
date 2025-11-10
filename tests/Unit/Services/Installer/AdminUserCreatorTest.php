<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Models\User;
use App\Services\Installer\AdminUserCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserCreatorTest extends TestCase
{
    use RefreshDatabase;

    /** @phpstan-ignore-next-line property.uninitialized (initialized in setUp) */
    private AdminUserCreator $creator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creator = new AdminUserCreator;
    }

    public function testValidatePasswordStrengthRejectsShortPassword(): void
    {
        $result = $this->creator->validatePasswordStrength('Short1!');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_length', $result['errors']);
    }

    public function testValidatePasswordStrengthRequiresUppercase(): void
    {
        $result = $this->creator->validatePasswordStrength('lowercase123!@#');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_uppercase', $result['errors']);
    }

    public function testValidatePasswordStrengthRequiresLowercase(): void
    {
        $result = $this->creator->validatePasswordStrength('UPPERCASE123!@#');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_lowercase', $result['errors']);
    }

    public function testValidatePasswordStrengthRequiresNumber(): void
    {
        $result = $this->creator->validatePasswordStrength('NoNumbersHere!@#');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_number', $result['errors']);
    }

    public function testValidatePasswordStrengthRequiresSpecialChar(): void
    {
        $result = $this->creator->validatePasswordStrength('NoSpecialChar123');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_special', $result['errors']);
    }

    public function testValidatePasswordStrengthAcceptsStrongPassword(): void
    {
        $result = $this->creator->validatePasswordStrength('StrongP@ssw0rd123!');

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
        $this->assertEquals(5, $result['strength_score']);
    }

    public function testValidatePasswordStrengthRejectsCommonPasswords(): void
    {
        $result = $this->creator->validatePasswordStrength('password123!');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_common', $result['errors']);
        $this->assertEquals(0, $result['strength_score']);
    }

    public function testGetPasswordStrengthLabelReturnsCorrectLabels(): void
    {
        $this->assertEquals('Very Weak', $this->creator->getPasswordStrengthLabel(0)['label']);
        $this->assertEquals('Very Weak', $this->creator->getPasswordStrengthLabel(1)['label']);
        $this->assertEquals('Weak', $this->creator->getPasswordStrengthLabel(2)['label']);
        $this->assertEquals('Fair', $this->creator->getPasswordStrengthLabel(3)['label']);
        $this->assertEquals('Good', $this->creator->getPasswordStrengthLabel(4)['label']);
        $this->assertEquals('Strong', $this->creator->getPasswordStrengthLabel(5)['label']);
    }

    public function testGetPasswordStrengthLabelReturnsCorrectColors(): void
    {
        $this->assertEquals('red', $this->creator->getPasswordStrengthLabel(0)['color']);
        $this->assertEquals('orange', $this->creator->getPasswordStrengthLabel(2)['color']);
        $this->assertEquals('yellow', $this->creator->getPasswordStrengthLabel(3)['color']);
        $this->assertEquals('lime', $this->creator->getPasswordStrengthLabel(4)['color']);
        $this->assertEquals('green', $this->creator->getPasswordStrengthLabel(5)['color']);
    }

    public function testCreatePreventsDuplicateUsers(): void
    {
        // Create a user first
        User::factory()->create();

        $result = $this->creator->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123!',
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already exists', $result['message']);
    }

    public function testCreateValidatesPasswordStrength(): void
    {
        $result = $this->creator->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'weak',
        ]);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('errors', $result);
    }

    public function testCreateSuccessfullyCreatesAdminUser(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $result = $this->creator->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123!',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user', $result);

        // PHPStan Level 8: Assert type and key existence
        $this->assertInstanceOf(User::class, $result['user'] ?? null);
        /** @var User $user */
        $user = $result['user'];

        $this->assertEquals('Admin User', $user->name);
        $this->assertEquals('admin@example.com', $user->email);
        $this->assertTrue($user->hasRole('Administrator'));
        $this->assertNotNull($user->email_verified_at);
    }

    public function testCreateHashesPasswordWithBcrypt(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $result = $this->creator->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'SecureP@ssw0rd123!',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user', $result);

        // PHPStan Level 8: Assert type and key existence
        $this->assertInstanceOf(User::class, $result['user'] ?? null);
        /** @var User $user */
        $user = $result['user'];

        $this->assertStringStartsWith('$2y$12$', $user->password);
    }
}
