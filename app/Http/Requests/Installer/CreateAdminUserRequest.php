<?php

declare(strict_types=1);

namespace App\Http\Requests\Installer;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateAdminUserRequest extends FormRequest
{
    /**
     * Skip DNS validation in tests (for localhost emails)
     */
    public static bool $skipDnsValidation = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * Only allow if no users exist (first installation)
     */
    public function authorize(): bool
    {
        return User::count() === 0;
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
