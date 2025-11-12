<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import ProgressBar from 'primevue/progressbar';
import Message from 'primevue/message';

// Component state
const currentStep = ref(1);
const totalSteps = 3;
const isCheckingPassword = ref(false);

// Form state
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Password strength state
const passwordStrength = ref({
    score: 0,
    label: 'None',
    color: 'gray',
});

// Computed properties
const progress = computed(() => (currentStep.value / totalSteps) * 100);

const canProceedStep1 = computed(() => {
    return form.name.length > 0 && form.email.length > 0 && isValidEmail(form.email);
});

const passwordsMatch = computed(() => {
    return (
        form.password.length > 0 &&
        form.password_confirmation.length > 0 &&
        form.password === form.password_confirmation
    );
});

const canProceedStep2 = computed(() => {
    return (
        form.password.length >= 12 &&
        form.password_confirmation.length >= 12 &&
        passwordsMatch.value &&
        passwordStrength.value.score >= 4
    );
});

// Helper functions
const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

// Navigation functions
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

// Password strength check with debouncing
let debounceTimeout = null;

const checkPasswordStrength = () => {
    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }

    if (form.password.length === 0) {
        passwordStrength.value = { score: 0, label: 'None', color: 'gray' };
        return;
    }

    debounceTimeout = setTimeout(async () => {
        isCheckingPassword.value = true;

        try {
            const response = await fetch('/installer/wizard/check-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ password: form.password }),
            });

            if (response.ok) {
                const data = await response.json();
                passwordStrength.value = data.strength;
            } else {
                passwordStrength.value = { score: 0, label: 'Error', color: 'red' };
            }
        } catch (error) {
            console.error('Password strength check failed:', error);
            passwordStrength.value = { score: 0, label: 'Unknown', color: 'gray' };
        } finally {
            isCheckingPassword.value = false;
        }
    }, 500); // 500ms debounce
};

// Watch password changes
watch(
    () => form.password,
    () => {
        checkPasswordStrength();
    },
);

// Submit handler
const submit = () => {
    form.post('/installer/wizard', {
        preserveScroll: true,
        onError: (errors) => {
            console.error('Admin user creation failed:', errors);
            // Stay on step 3 to show errors
        },
    });
};

// Get color class for password strength
const getStrengthColorClass = (color) => {
    const colorMap = {
        red: 'text-red-600 dark:text-red-400',
        orange: 'text-orange-600 dark:text-orange-400',
        yellow: 'text-yellow-600 dark:text-yellow-400',
        lime: 'text-lime-600 dark:text-lime-400',
        green: 'text-green-600 dark:text-green-400',
        gray: 'text-gray-600 dark:text-gray-400',
    };
    return colorMap[color] || 'text-gray-600 dark:text-gray-400';
};
</script>

<template>
    <div
        class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-gray-900"
    >
        <div class="w-full max-w-2xl space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Create Admin Account
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Step {{ currentStep }} of {{ totalSteps }}
                </p>
            </div>

            <!-- Progress Bar -->
            <ProgressBar :value="progress" :show-value="false" class="h-2" />

            <!-- Step 1: User Information -->
            <div
                v-if="currentStep === 1"
                class="space-y-6 rounded-lg bg-white p-8 shadow-md dark:bg-gray-800"
            >
                <div class="space-y-2">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Full Name
                    </label>
                    <InputText
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full"
                        placeholder="John Doe"
                        :class="{ 'p-invalid': form.errors.name }"
                        autofocus
                    />
                    <small v-if="form.errors.name" class="text-red-600 dark:text-red-400">
                        {{ form.errors.name }}
                    </small>
                </div>

                <div class="space-y-2">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Email Address
                    </label>
                    <InputText
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full"
                        placeholder="admin@example.com"
                        :class="{ 'p-invalid': form.errors.email }"
                    />
                    <small v-if="form.errors.email" class="text-red-600 dark:text-red-400">
                        {{ form.errors.email }}
                    </small>
                </div>

                <div class="flex justify-end">
                    <Button
                        label="Next"
                        icon="pi pi-arrow-right"
                        icon-pos="right"
                        :disabled="!canProceedStep1"
                        @click="nextStep"
                    />
                </div>
            </div>

            <!-- Step 2: Password -->
            <div
                v-if="currentStep === 2"
                class="space-y-6 rounded-lg bg-white p-8 shadow-md dark:bg-gray-800"
            >
                <div class="space-y-2">
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Password
                    </label>
                    <Password
                        id="password"
                        v-model="form.password"
                        input-id="password"
                        class="w-full"
                        toggle-mask
                        :feedback="false"
                        :class="{ 'p-invalid': form.errors.password }"
                        placeholder="Enter password (min 12 characters)"
                        input-class="w-full"
                    />
                    <small v-if="form.errors.password" class="text-red-600 dark:text-red-400">
                        {{ form.errors.password }}
                    </small>

                    <!-- Password Strength Indicator -->
                    <div v-if="form.password.length > 0" class="mt-3 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400"
                                >Password Strength:</span
                            >
                            <span
                                class="text-sm font-semibold"
                                :class="getStrengthColorClass(passwordStrength.color)"
                            >
                                {{ isCheckingPassword ? 'Checking...' : passwordStrength.label }}
                            </span>
                        </div>
                        <ProgressBar
                            :value="(passwordStrength.score / 5) * 100"
                            :show-value="false"
                            class="h-2"
                        />
                    </div>

                    <!-- Password Requirements -->
                    <ul class="mt-3 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                        <li>• At least 12 characters</li>
                        <li>• Uppercase and lowercase letters</li>
                        <li>• At least one number</li>
                        <li>• At least one special character (!@#$%^&*)</li>
                        <li>• Strength of "Good" or better required</li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Confirm Password
                    </label>
                    <Password
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        input-id="password_confirmation"
                        class="w-full"
                        toggle-mask
                        :feedback="false"
                        :class="{ 'p-invalid': form.errors.password_confirmation }"
                        placeholder="Re-enter password"
                        input-class="w-full"
                    />
                    <small
                        v-if="form.errors.password_confirmation"
                        class="text-red-600 dark:text-red-400"
                    >
                        {{ form.errors.password_confirmation }}
                    </small>

                    <!-- Password Match Indicator -->
                    <div v-if="form.password_confirmation.length > 0" class="mt-2">
                        <Message v-if="!passwordsMatch" severity="error" :closable="false">
                            Passwords do not match
                        </Message>
                        <Message v-else severity="success" :closable="false">
                            Passwords match
                        </Message>
                    </div>
                </div>

                <div class="flex justify-between">
                    <Button
                        label="Back"
                        icon="pi pi-arrow-left"
                        severity="secondary"
                        @click="prevStep"
                    />
                    <Button
                        label="Next"
                        icon="pi pi-arrow-right"
                        icon-pos="right"
                        :disabled="!canProceedStep2"
                        @click="nextStep"
                    />
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div
                v-if="currentStep === 3"
                class="space-y-6 rounded-lg bg-white p-8 shadow-md dark:bg-gray-800"
            >
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                        Review Your Information
                    </h3>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Full Name
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ form.name }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Email Address
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ form.email }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Role
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900 dark:text-green-200"
                                >
                                    Administrator
                                </span>
                            </dd>
                        </div>
                    </dl>

                    <Message severity="info" :closable="false" class="mt-4">
                        You will be redirected to the login page after creating your account.
                    </Message>
                </div>

                <!-- Error messages from server -->
                <div v-if="Object.keys(form.errors).length > 0" class="space-y-2">
                    <Message
                        v-for="(error, field) in form.errors"
                        :key="field"
                        severity="error"
                        :closable="false"
                    >
                        {{ error }}
                    </Message>
                </div>

                <div class="flex justify-between">
                    <Button
                        label="Back"
                        icon="pi pi-arrow-left"
                        severity="secondary"
                        @click="prevStep"
                    />
                    <Button
                        label="Create Admin Account"
                        icon="pi pi-check"
                        icon-pos="right"
                        severity="success"
                        :loading="form.processing"
                        @click="submit"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
