---
task_id: 004
epic: 001-installer-foundation
title: Admin User Creation Wizard
status: pending
priority: critical
estimated_effort: 6 hours
actual_effort: null
assignee: fullstack-developer
dependencies: [003]
tags: [installer, authentication, vue, week-2]
---

# Task 004: Admin User Creation Wizard

## 📋 Overview

Build multi-step admin user creation wizard with Vue 3.5 + Inertia.js frontend and Laravel backend. Implement secure password validation, email verification, role assignment, and bcrypt hashing (cost 12). First user created during installation automatically gets Administrator role.

**Why Critical:** Installation is incomplete without an admin user. This wizard ensures secure credential creation with proper validation and provides excellent UX for non-technical users.

## 🎯 Acceptance Criteria

- [ ] Multi-step Vue wizard component (Step 1: User Info, Step 2: Password, Step 3: Confirm)
- [ ] Email validation (valid format, unique)
- [ ] Password strength validation (min 12 chars, uppercase, lowercase, number, special char)
- [ ] Password confirmation match validation
- [ ] Real-time validation feedback (Vue reactive)
- [ ] Bcrypt hashing with cost 12
- [ ] Automatic Administrator role assignment for first user
- [ ] Success redirect to admin panel login
- [ ] API endpoint for user creation
- [ ] Comprehensive unit, feature, and browser tests
- [ ] PHPStan Level 8 compliance

## 🏗️ Implementation Steps

### Step 1: Create Admin User Service

**File**: `app/Services/Installer/AdminUserCreator.php`

**Description**: Service to create first admin user with secure password hashing.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserCreator
{
    /**
     * Password requirements
     */
    private const MIN_PASSWORD_LENGTH = 12;

    /**
     * Create admin user
     *
     * @param array{
     *     name: string,
     *     email: string,
     *     password: string
     * } $userData
     * @return array{
     *     success: bool,
     *     user?: User,
     *     message: string,
     *     errors?: array<string, string>
     * }
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
        if (!$passwordValidation['valid']) {
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
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(), // Auto-verify first admin
            ]);

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
     * @param string $password Password to validate
     * @return array{
     *     valid: bool,
     *     errors: array<string, string>,
     *     strength_score: int
     * }
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];
        $score = 0;

        // Length check
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $errors['password_length'] = sprintf(
                'Password must be at least %d characters long',
                self::MIN_PASSWORD_LENGTH
            );
        } else {
            $score++;
        }

        // Uppercase check
        if (!preg_match('/[A-Z]/', $password)) {
            $errors['password_uppercase'] = 'Password must contain at least one uppercase letter';
        } else {
            $score++;
        }

        // Lowercase check
        if (!preg_match('/[a-z]/', $password)) {
            $errors['password_lowercase'] = 'Password must contain at least one lowercase letter';
        } else {
            $score++;
        }

        // Number check
        if (!preg_match('/[0-9]/', $password)) {
            $errors['password_number'] = 'Password must contain at least one number';
        } else {
            $score++;
        }

        // Special character check
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors['password_special'] = 'Password must contain at least one special character (!@#$%^&*)';
        } else {
            $score++;
        }

        // Common password check (basic list)
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
            'valid' => empty($errors),
            'errors' => $errors,
            'strength_score' => $score,
        ];
    }

    /**
     * Get password strength label
     *
     * @param int $score Password strength score (0-5)
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
```

### Step 2: Create Form Request Validator

**File**: `app/Http/Requests/Installer/CreateAdminUserRequest.php`

**Description**: Form request for admin user validation.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests\Installer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateAdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow if no users exist (first installation)
        return \App\Models\User::count() === 0;
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
                'regex:/^[a-zA-Z\s\'-]+$/', // Letters, spaces, hyphens, apostrophes
            ],
            'email' => [
                'required',
                'email:rfc,dns', // Validate email format and DNS
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:12',
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
     * Get custom error messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already registered',
            'password.required' => 'Please enter a password',
            'password.min' => 'Password must be at least 12 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
```

### Step 3: Create Admin User Controller

**File**: `app/Http/Controllers/Installer/AdminUserController.php`

**Description**: API endpoint for admin user creation.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Installer\CreateAdminUserRequest;
use App\Services\Installer\AdminUserCreator;
use Illuminate\Http\JsonResponse;

class AdminUserController extends Controller
{
    public function __construct(
        private readonly AdminUserCreator $creator
    ) {}

    /**
     * Create admin user
     *
     * @param CreateAdminUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateAdminUserRequest $request): JsonResponse
    {
        $result = $this->creator->create($request->validated());

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'errors' => $result['errors'] ?? [],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
                'role' => $result['user']->role,
            ],
        ], 201);
    }

    /**
     * Check password strength (for real-time validation)
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function checkPasswordStrength(\Illuminate\Http\Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $validation = $this->creator->validatePasswordStrength($request->input('password'));
        $strengthLabel = $this->creator->getPasswordStrengthLabel($validation['strength_score']);

        return response()->json([
            'valid' => $validation['valid'],
            'errors' => $validation['errors'],
            'strength' => [
                'score' => $validation['strength_score'],
                'label' => $strengthLabel['label'],
                'color' => $strengthLabel['color'],
            ],
        ]);
    }
}
```

### Step 4: Create Vue Admin User Wizard Component

**File**: `resources/js/Pages/Installer/AdminUserWizard.vue`

**Description**: Multi-step wizard for admin user creation.

**Implementation**:

```vue
<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from 'primevue/button';
import { InputText } from 'primevue/inputtext';
import { Password } from 'primevue/password';
import { ProgressBar } from 'primevue/progressbar';
import { Message } from 'primevue/message';

const currentStep = ref(1);
const totalSteps = 3;

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const passwordStrength = ref({
    score: 0,
    label: 'None',
    color: 'gray',
});

const progress = computed(() => (currentStep.value / totalSteps) * 100);

const canProceedStep1 = computed(() => {
    return form.name.length > 0 && form.email.length > 0;
});

const canProceedStep2 = computed(() => {
    return (
        form.password.length >= 12 &&
        form.password_confirmation.length >= 12 &&
        form.password === form.password_confirmation &&
        passwordStrength.value.score >= 4
    );
});

const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const checkPasswordStrength = async () => {
    if (form.password.length === 0) {
        passwordStrength.value = { score: 0, label: 'None', color: 'gray' };
        return;
    }

    try {
        const response = await fetch('/installer/admin-user/check-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ password: form.password }),
        });

        const data = await response.json();
        passwordStrength.value = data.strength;
    } catch (error) {
        console.error('Password strength check failed:', error);
    }
};

const submit = () => {
    form.post('/installer/admin-user', {
        onSuccess: () => {
            // Redirect to login page
            window.location.href = '/admin/login';
        },
        onError: (errors) => {
            console.error('Admin user creation failed:', errors);
        },
    });
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-gray-900">
        <div class="w-full max-w-2xl space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Admin Account</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Step {{ currentStep }} of {{ totalSteps }}</p>
            </div>

            <!-- Progress Bar -->
            <ProgressBar :value="progress" :showValue="false" class="h-2" />

            <!-- Step 1: User Information -->
            <div v-if="currentStep === 1" class="space-y-6 rounded-lg bg-white p-8 shadow-md dark:bg-gray-800">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Full Name
                    </label>
                    <InputText
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="John Doe"
                        :class="{ 'p-invalid': form.errors.name }"
                    />
                    <small v-if="form.errors.name" class="p-error">{{ form.errors.name }}</small>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address
                    </label>
                    <InputText
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        placeholder="admin@example.com"
                        :class="{ 'p-invalid': form.errors.email }"
                    />
                    <small v-if="form.errors.email" class="p-error">{{ form.errors.email }}</small>
                </div>

                <div class="flex justify-end">
                    <Button
                        label="Next"
                        icon="pi pi-arrow-right"
                        iconPos="right"
                        @click="nextStep"
                        :disabled="!canProceedStep1"
                    />
                </div>
            </div>

            <!-- Step 2: Password -->
            <div v-if="currentStep === 2" class="space-y-6 rounded-lg bg-white p-8 shadow-md dark:bg-gray-800">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <Password
                        id="password"
                        v-model="form.password"
                        class="mt-1 block w-full"
                        toggleMask
                        :feedback="false"
                        @input="checkPasswordStrength"
                        :class="{ 'p-invalid': form.errors.password }"
                    />
                    <small v-if="form.errors.password" class="p-error">{{ form.errors.password }}</small>

                    <!-- Password Strength Indicator -->
                    <div v-if="form.password.length > 0" class="mt-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Password Strength:</span>
                            <span
                                class="text-sm font-semibold"
                                :class="{
                                    'text-red-600': passwordStrength.color === 'red',
                                    'text-orange-600': passwordStrength.color === 'orange',
                                    'text-yellow-600': passwordStrength.color === 'yellow',
                                    'text-lime-600': passwordStrength.color === 'lime',
                                    'text-green-600': passwordStrength.color === 'green',
                                }"
                            >
                                {{ passwordStrength.label }}
                            </span>
                        </div>
                        <ProgressBar
                            :value="(passwordStrength.score / 5) * 100"
                            :showValue="false"
                            class="mt-1 h-2"
                            :class="{
                                'bg-red-200': passwordStrength.color === 'red',
                                'bg-orange-200': passwordStrength.color === 'orange',
                                'bg-yellow-200': passwordStrength.color === 'yellow',
                                'bg-lime-200': passwordStrength.color === 'lime',
                                'bg-green-200': passwordStrength.color === 'green',
                            }"
                        />
                    </div>

                    <ul class="mt-2 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                        <li>• At least 12 characters</li>
                        <li>• Uppercase and lowercase letters</li>
                        <li>• At least one number</li>
                        <li>• At least one special character (!@#$%^&*)</li>
                    </ul>
                </div>

                <div>
                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Confirm Password
                    </label>
                    <Password
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        class="mt-1 block w-full"
                        toggleMask
                        :feedback="false"
                        :class="{ 'p-invalid': form.errors.password_confirmation }"
                    />
                    <small v-if="form.errors.password_confirmation" class="p-error">
                        {{ form.errors.password_confirmation }}
                    </small>

                    <!-- Password Match Indicator -->
                    <Message
                        v-if="form.password_confirmation.length > 0 && form.password !== form.password_confirmation"
                        severity="error"
                        class="mt-2"
                    >
                        Passwords do not match
                    </Message>
                    <Message
                        v-if="form.password_confirmation.length > 0 && form.password === form.password_confirmation"
                        severity="success"
                        class="mt-2"
                    >
                        Passwords match
                    </Message>
                </div>

                <div class="flex justify-between">
                    <Button label="Back" icon="pi pi-arrow-left" severity="secondary" @click="prevStep" />
                    <Button
                        label="Next"
                        icon="pi pi-arrow-right"
                        iconPos="right"
                        @click="nextStep"
                        :disabled="!canProceedStep2"
                    />
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div v-if="currentStep === 3" class="space-y-6 rounded-lg bg-white p-8 shadow-md dark:bg-gray-800">
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Review Your Information</h3>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ form.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ form.email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">Administrator</dd>
                        </div>
                    </dl>

                    <Message severity="info" class="mt-4">
                        You will be automatically logged in after creating your admin account.
                    </Message>
                </div>

                <div class="flex justify-between">
                    <Button label="Back" icon="pi pi-arrow-left" severity="secondary" @click="prevStep" />
                    <Button
                        label="Create Admin Account"
                        icon="pi pi-check"
                        iconPos="right"
                        severity="success"
                        @click="submit"
                        :loading="form.processing"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
```

### Step 5: Add Routes

**File**: `routes/installer.php`

**Description**: Add admin user creation routes.

**Implementation**:

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\AdminUserController;
use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')->middleware('web')->group(function () {
    Route::get('/requirements', [RequirementsController::class, 'check'])
        ->name('installer.requirements.check');

    Route::post('/environment', [EnvironmentController::class, 'generate'])
        ->name('installer.environment.generate');

    Route::post('/database/initialize', [DatabaseController::class, 'initialize'])
        ->name('installer.database.initialize');

    Route::post('/database/migrate', [DatabaseController::class, 'migrate'])
        ->name('installer.database.migrate');

    Route::get('/database/info', [DatabaseController::class, 'info'])
        ->name('installer.database.info');

    // Admin user creation
    Route::post('/admin-user', [AdminUserController::class, 'create'])
        ->name('installer.admin-user.create');

    Route::post('/admin-user/check-password', [AdminUserController::class, 'checkPasswordStrength'])
        ->name('installer.admin-user.check-password');
});
```

### Step 6: Create User Migration

**File**: `database/migrations/2025_01_01_000001_create_users_table.php`

**Description**: Users table migration with role column.

**Implementation**:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'editor', 'author', 'contributor'])->default('author');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('role');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Services/Installer/AdminUserCreatorTest.php`
    - Test `validatePasswordStrength()` with various passwords
    - Test password strength scoring (0-5)
    - Test `getPasswordStrengthLabel()` returns correct labels
    - Test bcrypt hashing with cost 12
    - Test admin role assignment
    - Test automatic email verification
    - Test duplicate user prevention

**Feature Tests:**

- `tests/Feature/Installer/AdminUserCreationTest.php`
    - Test POST `/installer/admin-user` creates user
    - Test validation errors for weak passwords
    - Test unique email validation
    - Test password confirmation mismatch
    - Test 422 status for validation errors
    - Test user cannot be created if users already exist

**Browser Tests (Playwright):**

- `tests/Browser/Installer/AdminUserWizardTest.php`
    - Test multi-step wizard navigation
    - Test real-time password strength indicator
    - Test password match validation
    - Test form submission creates user
    - Test redirect to admin login

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\AdminUserCreator;
use Tests\TestCase;

class AdminUserCreatorTest extends TestCase
{
    private AdminUserCreator $creator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creator = new AdminUserCreator();
    }

    public function test_validate_password_strength_rejects_short_password(): void
    {
        $result = $this->creator->validatePasswordStrength('Short1!');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_length', $result['errors']);
    }

    public function test_validate_password_strength_requires_uppercase(): void
    {
        $result = $this->creator->validatePasswordStrength('lowercase123!@#');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_uppercase', $result['errors']);
    }

    public function test_validate_password_strength_requires_lowercase(): void
    {
        $result = $this->creator->validatePasswordStrength('UPPERCASE123!@#');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_lowercase', $result['errors']);
    }

    public function test_validate_password_strength_requires_number(): void
    {
        $result = $this->creator->validatePasswordStrength('NoNumbersHere!@#');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_number', $result['errors']);
    }

    public function test_validate_password_strength_requires_special_char(): void
    {
        $result = $this->creator->validatePasswordStrength('NoSpecialChar123');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_special', $result['errors']);
    }

    public function test_validate_password_strength_accepts_strong_password(): void
    {
        $result = $this->creator->validatePasswordStrength('StrongP@ssw0rd123!');

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
        $this->assertEquals(5, $result['strength_score']);
    }

    public function test_validate_password_strength_rejects_common_passwords(): void
    {
        $result = $this->creator->validatePasswordStrength('password123!');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('password_common', $result['errors']);
    }

    public function test_get_password_strength_label_returns_correct_labels(): void
    {
        $this->assertEquals('Very Weak', $this->creator->getPasswordStrengthLabel(0)['label']);
        $this->assertEquals('Weak', $this->creator->getPasswordStrengthLabel(2)['label']);
        $this->assertEquals('Fair', $this->creator->getPasswordStrengthLabel(3)['label']);
        $this->assertEquals('Good', $this->creator->getPasswordStrengthLabel(4)['label']);
        $this->assertEquals('Strong', $this->creator->getPasswordStrengthLabel(5)['label']);
    }
}
```

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (Admin User Setup)
- **Security**: `docs/prd/07-TECHNICAL-SPECIFICATIONS.md` Section 6.2 (Password Hashing)
- **Timeline**: Week 2 (v1.0.0)
- **Success Criteria**: Secure password validation, bcrypt cost 12

**Architecture:**

- **Authentication**: Laravel Fortify (`docs/prd/04-ARCHITECTURE.md`)
- **Password Hashing**: Bcrypt with cost 12
- **RBAC**: Administrator role auto-assigned

**Quality Requirements:**

- **Security**: Strong password validation, bcrypt hashing, OWASP compliance
- **UX**: Multi-step wizard, real-time validation, clear error messages
- **Testing**: Unit, Feature, and Browser tests

**Related Tasks:**

- **Next**: 005-web-server-config
- **Blocks**: Installation completion
- **Depends On**: 003-database-initialization (needs users table)

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks with array shapes
- [ ] ESLint passes for Vue component
- [ ] Prettier formatted (Vue)

### Testing

- [ ] Unit tests written and passing (10+ test cases)
- [ ] Feature tests written and passing
- [ ] Browser tests for wizard flow
- [ ] Test coverage > 80%
- [ ] Edge cases covered (weak passwords, duplicate emails)

### Security

- [ ] Bcrypt hashing with cost 12
- [ ] Password strength validation (12 chars, mixed case, numbers, symbols)
- [ ] Email uniqueness validated
- [ ] CSRF protection enabled
- [ ] No plaintext passwords in logs

### UX

- [ ] Real-time password strength indicator
- [ ] Clear validation error messages
- [ ] Multi-step wizard navigation works
- [ ] Responsive design (mobile-friendly)
- [ ] Dark mode support

## ✅ Verification Steps

```bash
# Backend quality check
composer quality

# Frontend quality check
npm run quality

# Test admin user creation endpoint
curl -X POST http://localhost:8000/installer/admin-user \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Admin User",
    "email": "admin@example.com",
    "password": "SecureP@ssw0rd123!",
    "password_confirmation": "SecureP@ssw0rd123!"
  }' | jq

# Verify user created
php artisan tinker
>>> App\Models\User::first()

# Check password hashed with bcrypt
>>> App\Models\User::first()->password  // Should start with $2y$12$

# Test password strength endpoint
curl -X POST http://localhost:8000/installer/admin-user/check-password \
  -H "Content-Type: application/json" \
  -d '{"password": "WeakPass"}' | jq
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-004-admin-user-wizard

# Implement
git commit -m "feat(installer): add AdminUserCreator service"
git commit -m "feat(installer): add CreateAdminUserRequest validator"
git commit -m "feat(installer): add admin user creation endpoint"
git commit -m "feat(installer): add Vue AdminUserWizard component"
git commit -m "test(installer): add AdminUserCreator unit tests"
git commit -m "feat(database): add users table migration"

# Before completing
composer quality
npm run quality

# Complete task
git commit -m "feat(installer): complete task-004-admin-user-wizard

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```
