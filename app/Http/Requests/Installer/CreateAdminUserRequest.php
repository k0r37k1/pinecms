<?php

declare(strict_types=1);

namespace App\Http\Requests\Installer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Create Admin User Request
 *
 * Validates admin user creation data during PineCMS installation.
 * Enforces strong password requirements and email uniqueness.
 */
class CreateAdminUserRequest extends FormRequest
{
    /**
     * Skip DNS validation in tests (for localhost emails)
     */
    public static bool $skipDnsValidation = false;

    /**
     * Determine if the user is authorized to make this request
     *
     * Authorization is handled at the controller level by checking
     * if users exist via the AdminUserCreator service.
     *
     * @return bool Always returns true (authorization handled elsewhere)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\'-]+$/', // Letters, spaces, hyphens, apostrophes only
            ],
            'email' => array_merge(
                ['required'],
                [$this->getEmailValidation()],
                ['max:255', 'unique:users,email']
            ),
            'password' => [
                'required',
                'string',
                'confirmed', // Requires password_confirmation field
                Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), // Check against pwned passwords database
            ],
        ];
    }

    /**
     * Get email validation rule based on environment
     *
     * Returns 'email:rfc,dns' for production (validates DNS records)
     * or 'email:rfc' for tests (skips DNS validation for localhost).
     *
     * @return string Email validation rule
     */
    private function getEmailValidation(): string
    {
        // Skip DNS check in tests (localhost doesn't have valid DNS records)
        return self::$skipDnsValidation ? 'email:rfc' : 'email:rfc,dns';
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least :min characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
