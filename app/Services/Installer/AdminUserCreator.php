<?php

declare(strict_types=1);

namespace App\Services\Installer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Admin User Creator Service
 *
 * Handles the creation of the initial administrator user during PineCMS installation.
 * Enforces strong password requirements and assigns the Administrator role via Spatie Permission.
 */
class AdminUserCreator
{
    /**
     * Password requirements
     */
    private const MIN_PASSWORD_LENGTH = 12;

    /**
     * Create admin user
     *
     * @param  array{name: string, email: string, password: string}  $userData
     * @return array{success: bool, user?: User, message: string, errors?: array<string, string>}
     */
    public function create(array $userData): array
    {
        // Check if any users exist
        if (User::count() > 0) {
            return [
                'success' => false,
                'message' => 'Admin user already exists. Installation may be complete.',
                'errors' => ['user' => 'Cannot create admin user - users already exist'],
            ];
        }

        // Validate password strength
        $passwordValidation = $this->validatePasswordStrength($userData['password']);
        if (! $passwordValidation['valid']) {
            return [
                'success' => false,
                'message' => 'Password does not meet security requirements.',
                'errors' => $passwordValidation['errors'],
            ];
        }

        try {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password'], [
                    'rounds' => 12, // Bcrypt cost factor
                ]),
                'status' => 'active',
            ]);

            // Assign Administrator role via Spatie Permission
            $user->assignRole('Administrator');

            // Auto-verify first admin email
            $user->markEmailAsVerified();

            // Refresh the model and eager load roles to prevent N+1
            $user->fresh();
            $user->load('roles');

            return [
                'success' => true,
                'user' => $user,
                'message' => 'Admin user created successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create admin user: ' . $e->getMessage(),
                'errors' => ['database' => $e->getMessage()],
            ];
        }
    }

    /**
     * Validate password strength
     *
     * @return array{valid: bool, errors: array<string, string>, strength_score: int}
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];
        $score = 0;

        // Length check
        if (strlen($password) >= self::MIN_PASSWORD_LENGTH) {
            $score++;
        }
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $errors['password_length'] = sprintf(
                'Password must be at least %d characters long',
                self::MIN_PASSWORD_LENGTH
            );
        }

        // Uppercase check
        if (preg_match('/[A-Z]/', $password) === 1) {
            $score++;
        }
        if (preg_match('/[A-Z]/', $password) !== 1) {
            $errors['password_uppercase'] = 'Password must contain at least one uppercase letter';
        }

        // Lowercase check
        if (preg_match('/[a-z]/', $password) === 1) {
            $score++;
        }
        if (preg_match('/[a-z]/', $password) !== 1) {
            $errors['password_lowercase'] = 'Password must contain at least one lowercase letter';
        }

        // Number check
        if (preg_match('/[0-9]/', $password) === 1) {
            $score++;
        }
        if (preg_match('/[0-9]/', $password) !== 1) {
            $errors['password_number'] = 'Password must contain at least one number';
        }

        // Special character check
        if (preg_match('/[^A-Za-z0-9]/', $password) === 1) {
            $score++;
        }
        if (preg_match('/[^A-Za-z0-9]/', $password) !== 1) {
            $errors['password_special'] = 'Password must contain at least one special character (!@#$%^&*)';
        }

        // Common password check
        $commonPasswords = [
            'password123!',
            'Admin123!@#',
            'Welcome123!',
            'Password1!',
        ];

        if (in_array($password, $commonPasswords, true)) {
            $errors['password_common'] = 'This password is too common. Please choose a unique password.';
            $score = 0;
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors,
            'strength_score' => $score,
        ];
    }

    /**
     * Get password strength label
     *
     * @return array{label: string, color: string}
     */
    public function getPasswordStrengthLabel(int $score): array
    {
        return match ($score) {
            0, 1 => ['label' => 'Very Weak', 'color' => 'red'],
            2 => ['label' => 'Weak', 'color' => 'orange'],
            3 => ['label' => 'Fair', 'color' => 'yellow'],
            4 => ['label' => 'Good', 'color' => 'lime'],
            5 => ['label' => 'Strong', 'color' => 'green'],
            default => ['label' => 'Unknown', 'color' => 'gray'],
        };
    }
}
