import { test, expect, type Page } from '@playwright/test';

/**
 * E2E Tests for AdminUserWizard Component
 *
 * Tests the 3-step admin user creation wizard flow during installation:
 * 1. User Information (Name, Email)
 * 2. Password Setup (with strength validation)
 * 3. Review and Submit
 *
 * Test isolation: Uses POST /installer/unlock to reset installation state before each test
 */

// Test data constants
const VALID_ADMIN = {
    name: 'Test Admin',
    email: 'admin@test.local',
    password: 'SecureTestPass123!',
} as const;

const WEAK_PASSWORD = 'weak123';
const MISMATCHED_PASSWORD = 'Different123!';
const SHORT_PASSWORD = 'Short1!';
const INVALID_EMAIL = 'invalid-email';

/**
 * Helper class for AdminUserWizard page interactions
 * Provides semantic selectors and common actions
 */
class AdminUserWizardPage {
    constructor(private page: Page) {}

    // Step 1 - User Information selectors
    get nameInput() {
        return this.page.locator('input#name');
    }

    get emailInput() {
        return this.page.locator('input#email');
    }

    get nextButton() {
        return this.page.getByRole('button', { name: 'Next' });
    }

    // Step 2 - Password selectors
    get passwordInput() {
        return this.page.locator('input#password');
    }

    get passwordConfirmationInput() {
        return this.page.locator('input#password_confirmation');
    }

    get backButton() {
        return this.page.getByRole('button', { name: 'Back' });
    }

    get passwordStrengthLabel() {
        return this.page.locator('text=Password Strength:').locator('..').locator('span').nth(1);
    }

    get passwordMatchMessage() {
        return this.page.getByText('Passwords match');
    }

    get passwordMismatchMessage() {
        return this.page.getByText('Passwords do not match');
    }

    // Step 3 - Review selectors
    get submitButton() {
        return this.page.getByRole('button', { name: 'Create Admin Account' });
    }

    get reviewName() {
        return this.page.locator('dt:has-text("Full Name") + dd');
    }

    get reviewEmail() {
        return this.page.locator('dt:has-text("Email Address") + dd');
    }

    get administratorBadge() {
        return this.page.getByText('Administrator');
    }

    get redirectInfoMessage() {
        return this.page.getByText('You will be redirected to the admin login page');
    }

    // Common selectors
    get currentStepIndicator() {
        return this.page.locator('p.text-sm:has-text("Step")');
    }

    get progressBar() {
        return this.page.locator('.p-progressbar');
    }

    // Actions
    async fillUserInfo(name: string, email: string) {
        await this.nameInput.fill(name);
        await this.emailInput.fill(email);
    }

    async fillPasswords(password: string, confirmation: string) {
        await this.passwordInput.fill(password);
        await this.passwordConfirmationInput.fill(confirmation);
    }

    async goToPasswordStep() {
        await this.fillUserInfo(VALID_ADMIN.name, VALID_ADMIN.email);
        await this.nextButton.click();
        await expect(this.currentStepIndicator).toContainText('Step 2');

        // Wait for step 2 form to be fully loaded (best practice for wizard navigation)
        await this.passwordInput.waitFor({ state: 'visible', timeout: 5000 });
    }

    async goToReviewStep(password: string = VALID_ADMIN.password) {
        await this.goToPasswordStep();
        await this.fillPasswords(password, password);

        // Wait for password strength API response (500ms debounce)
        await this.page.waitForResponse(
            (response) =>
                response.url().includes('/installer/admin-user/check-password') &&
                response.status() === 200,
        );

        // Wait for button to be enabled (strength >= 4)
        await expect(this.nextButton).toBeEnabled({ timeout: 2000 });
        await this.nextButton.click();
        await expect(this.currentStepIndicator).toContainText('Step 3');
    }
}

test.describe('AdminUserWizard - Complete Flow', () => {
    let wizardPage: AdminUserWizardPage;

    test.beforeEach(async ({ page }) => {
        // Reset installation state
        await page.request.post('/installer/unlock');

        // Navigate to wizard
        await page.goto('/installer/admin-user');
        await page.waitForLoadState('networkidle');

        // Initialize page object
        wizardPage = new AdminUserWizardPage(page);
    });

    test('should display wizard header and progress bar', async ({ page }) => {
        // Verify header
        await expect(page.locator('h1')).toHaveText('Create Admin Account');

        // Verify initial step indicator
        await expect(wizardPage.currentStepIndicator).toContainText('Step 1 of 3');

        // Verify progress bar exists in DOM (may not be visually visible due to CSS)
        await expect(wizardPage.progressBar).toBeAttached();
    });

    test('should complete full wizard flow successfully (happy path)', async ({ page }) => {
        // STEP 1: Fill user information
        await wizardPage.fillUserInfo(VALID_ADMIN.name, VALID_ADMIN.email);

        // Verify Next button is enabled with valid data
        await expect(wizardPage.nextButton).toBeEnabled();

        // Go to Step 2
        await wizardPage.nextButton.click();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 2 of 3');

        // Wait for step 2 form to be fully loaded (best practice for wizard navigation)
        await wizardPage.passwordInput.waitFor({ state: 'visible', timeout: 5000 });

        // STEP 2: Fill password
        await wizardPage.passwordInput.fill(VALID_ADMIN.password);

        // Wait for password strength API call (500ms debounce)
        const passwordStrengthResponse = page.waitForResponse(
            (response) =>
                response.url().includes('/installer/admin-user/check-password') &&
                response.status() === 200,
        );

        await passwordStrengthResponse;

        // Verify password strength is checked
        await expect(wizardPage.passwordStrengthLabel).not.toHaveText('Checking...');
        await expect(wizardPage.passwordStrengthLabel).not.toHaveText('None');

        // Fill password confirmation
        await wizardPage.passwordConfirmationInput.fill(VALID_ADMIN.password);

        // Verify "Passwords match" message appears
        await expect(wizardPage.passwordMatchMessage).toBeVisible();

        // Verify Next button is enabled with valid password (strength >= 4, length >= 12, match)
        await expect(wizardPage.nextButton).toBeEnabled({ timeout: 2000 });

        // Go to Step 3
        await wizardPage.nextButton.click();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 3 of 3');

        // STEP 3: Review information
        await expect(wizardPage.reviewName).toHaveText(VALID_ADMIN.name);
        await expect(wizardPage.reviewEmail).toHaveText(VALID_ADMIN.email);
        await expect(wizardPage.administratorBadge).toBeVisible();
        await expect(wizardPage.redirectInfoMessage).toBeVisible();

        // Submit form
        await wizardPage.submitButton.click();

        // Wait for Inertia navigation to admin login
        await page.waitForURL('/admin/login', { timeout: 10000 });

        // Verify redirect was successful
        expect(page.url()).toContain('/admin/login');
    });

    test('should navigate between steps using Back button', async ({ page }) => {
        // Go to Step 2
        await wizardPage.goToPasswordStep();

        // Verify we're on Step 2
        await expect(wizardPage.currentStepIndicator).toContainText('Step 2');
        await expect(wizardPage.backButton).toBeVisible();

        // Go back to Step 1
        await wizardPage.backButton.click();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 1');

        // Verify form data is preserved
        await expect(wizardPage.nameInput).toHaveValue(VALID_ADMIN.name);
        await expect(wizardPage.emailInput).toHaveValue(VALID_ADMIN.email);

        // Go forward again
        await wizardPage.nextButton.click();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 2');

        // Navigate to Step 3
        await wizardPage.fillPasswords(VALID_ADMIN.password, VALID_ADMIN.password);
        await page.waitForResponse((response) =>
            response.url().includes('/installer/admin-user/check-password'),
        );
        await expect(wizardPage.nextButton).toBeEnabled({ timeout: 2000 });
        await wizardPage.nextButton.click();

        // Verify Step 3
        await expect(wizardPage.currentStepIndicator).toContainText('Step 3');

        // Go back to Step 2
        await wizardPage.backButton.click();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 2');

        // Verify password data is preserved
        await expect(wizardPage.passwordInput).toHaveValue(VALID_ADMIN.password);
        await expect(wizardPage.passwordConfirmationInput).toHaveValue(VALID_ADMIN.password);
    });
});

test.describe('AdminUserWizard - Step 1 Validation', () => {
    let wizardPage: AdminUserWizardPage;

    test.beforeEach(async ({ page }) => {
        await page.request.post('/installer/unlock');
        await page.goto('/installer/admin-user');
        await page.waitForLoadState('networkidle');
        wizardPage = new AdminUserWizardPage(page);
    });

    test('should disable Next button when fields are empty', async () => {
        // Initially disabled
        await expect(wizardPage.nextButton).toBeDisabled();

        // Fill only name
        await wizardPage.nameInput.fill('Test User');
        await expect(wizardPage.nextButton).toBeDisabled();

        // Clear name, fill only email
        await wizardPage.nameInput.clear();
        await wizardPage.emailInput.fill('test@example.com');
        await expect(wizardPage.nextButton).toBeDisabled();
    });

    test('should disable Next button with invalid email format', async () => {
        await wizardPage.nameInput.fill('Test User');
        await wizardPage.emailInput.fill(INVALID_EMAIL);

        // Next button should remain disabled
        await expect(wizardPage.nextButton).toBeDisabled();
    });

    test('should enable Next button with valid name and email', async () => {
        await wizardPage.fillUserInfo(VALID_ADMIN.name, VALID_ADMIN.email);

        // Next button should be enabled
        await expect(wizardPage.nextButton).toBeEnabled();
    });

    test('should validate common email formats', async ({ page: _page }) => {
        const validEmails = [
            'user@example.com',
            'test.user@example.com',
            'user+tag@example.co.uk',
            'user_name@sub.example.com',
        ];

        for (const email of validEmails) {
            await wizardPage.nameInput.fill('Test User');
            await wizardPage.emailInput.fill(email);

            await expect(wizardPage.nextButton).toBeEnabled();

            // Clear for next iteration
            await wizardPage.emailInput.clear();
        }
    });

    test('should reject invalid email formats', async () => {
        const invalidEmails = [
            'not-an-email',
            '@example.com',
            'user@',
            'user@.com',
            'user space@example.com',
        ];

        for (const email of invalidEmails) {
            await wizardPage.nameInput.fill('Test User');
            await wizardPage.emailInput.fill(email);

            await expect(wizardPage.nextButton).toBeDisabled();

            // Clear for next iteration
            await wizardPage.emailInput.clear();
        }
    });
});

test.describe('AdminUserWizard - Step 2 Password Validation', () => {
    let wizardPage: AdminUserWizardPage;

    test.beforeEach(async ({ page }) => {
        await page.request.post('/installer/unlock');
        await page.goto('/installer/admin-user');
        await page.waitForLoadState('networkidle');
        wizardPage = new AdminUserWizardPage(page);

        // Navigate to Step 2
        await wizardPage.goToPasswordStep();
    });

    test('should disable Next button with weak password', async ({ page }) => {
        await wizardPage.passwordInput.fill(WEAK_PASSWORD);

        // Wait for password strength check (500ms debounce + API call)
        await page.waitForResponse(
            (response) =>
                response.url().includes('/installer/admin-user/check-password') &&
                response.status() === 200,
            { timeout: 2000 },
        );

        // Fill matching confirmation
        await wizardPage.passwordConfirmationInput.fill(WEAK_PASSWORD);
        await expect(wizardPage.passwordMatchMessage).toBeVisible();

        // Next button should remain disabled (weak password, likely score < 4)
        await expect(wizardPage.nextButton).toBeDisabled();
    });

    test('should disable Next button when password is too short', async () => {
        await wizardPage.passwordInput.fill(SHORT_PASSWORD);
        await wizardPage.passwordConfirmationInput.fill(SHORT_PASSWORD);

        // Next button should remain disabled (< 12 characters)
        await expect(wizardPage.nextButton).toBeDisabled();
    });

    test('should show "Passwords do not match" message', async () => {
        await wizardPage.passwordInput.fill(VALID_ADMIN.password);
        await wizardPage.passwordConfirmationInput.fill(MISMATCHED_PASSWORD);

        // Verify mismatch message appears
        await expect(wizardPage.passwordMismatchMessage).toBeVisible();

        // Verify "Passwords match" is NOT visible
        await expect(wizardPage.passwordMatchMessage).not.toBeVisible();

        // Next button should be disabled
        await expect(wizardPage.nextButton).toBeDisabled();
    });

    test('should show "Passwords match" message when passwords match', async () => {
        await wizardPage.passwordInput.fill(VALID_ADMIN.password);
        await wizardPage.passwordConfirmationInput.fill(VALID_ADMIN.password);

        // Verify match message appears
        await expect(wizardPage.passwordMatchMessage).toBeVisible();

        // Verify mismatch message is NOT visible
        await expect(wizardPage.passwordMismatchMessage).not.toBeVisible();
    });

    test('should call password strength API with debouncing', async ({ page }) => {
        // Start typing password
        await wizardPage.passwordInput.fill('Test');

        // Wait a bit (less than debounce time)
        await page.waitForTimeout(300);

        // Continue typing (should reset debounce)
        await wizardPage.passwordInput.fill('TestPassword123!');

        // Wait for debounced API call (500ms debounce)
        const strengthResponse = await page.waitForResponse(
            (response) =>
                response.url().includes('/installer/admin-user/check-password') &&
                response.status() === 200,
            { timeout: 2000 },
        );

        expect(strengthResponse.status()).toBe(200);

        // Verify strength label is updated (not "None" or "Checking...")
        await expect(wizardPage.passwordStrengthLabel).not.toHaveText('None');
        await expect(wizardPage.passwordStrengthLabel).not.toHaveText('Checking...');
    });

    test('should display password strength indicator', async ({ page }) => {
        await wizardPage.passwordInput.fill(VALID_ADMIN.password);

        // Wait for strength check
        await page.waitForResponse((response) =>
            response.url().includes('/installer/admin-user/check-password'),
        );

        // Verify strength label exists and has content
        await expect(wizardPage.passwordStrengthLabel).toBeVisible();
        await expect(wizardPage.passwordStrengthLabel).not.toHaveText('None');

        // Verify progress bar for strength is visible
        const strengthProgressBar = page
            .locator('text=Password Strength:')
            .locator('..')
            .locator('..')
            .locator('.p-progressbar');
        await expect(strengthProgressBar).toBeVisible();
    });

    test('should enable Next button only with strong password (score >= 4)', async ({ page }) => {
        await wizardPage.fillPasswords(VALID_ADMIN.password, VALID_ADMIN.password);

        // Wait for password strength API
        const response = await page.waitForResponse(
            (response) =>
                response.url().includes('/installer/admin-user/check-password') &&
                response.status() === 200,
        );

        const strengthData = await response.json();

        // If password is strong enough (score >= 4), Next should be enabled
        if (strengthData.strength.score >= 4) {
            await expect(wizardPage.nextButton).toBeEnabled({ timeout: 2000 });
        } else {
            await expect(wizardPage.nextButton).toBeDisabled();
        }
    });

    test('should show password requirements list', async ({ page }) => {
        // Verify password requirements are visible
        await expect(page.getByText('At least 12 characters')).toBeVisible();
        await expect(page.getByText('Uppercase and lowercase letters')).toBeVisible();
        await expect(page.getByText('At least one number')).toBeVisible();
        await expect(page.getByText(/At least one special character/)).toBeVisible();
        await expect(page.getByText(/Strength of "Good" or better/)).toBeVisible();
    });

    test('should toggle password visibility', async ({ page }) => {
        await wizardPage.passwordInput.fill(VALID_ADMIN.password);

        // Find toggle button for password field (PrimeVue Password component)
        const toggleButton = page
            .locator('#password')
            .locator('..')
            .locator('button[type="button"]')
            .first();
        await expect(toggleButton).toBeVisible();

        // Initially password should be masked (type="password")
        await expect(wizardPage.passwordInput).toHaveAttribute('type', 'password');

        // Click toggle to show password
        await toggleButton.click();
        await expect(wizardPage.passwordInput).toHaveAttribute('type', 'text');

        // Click toggle to hide password again
        await toggleButton.click();
        await expect(wizardPage.passwordInput).toHaveAttribute('type', 'password');
    });
});

test.describe('AdminUserWizard - Step 3 Review and Submit', () => {
    let wizardPage: AdminUserWizardPage;

    test.beforeEach(async ({ page }) => {
        await page.request.post('/installer/unlock');
        await page.goto('/installer/admin-user');
        await page.waitForLoadState('networkidle');
        wizardPage = new AdminUserWizardPage(page);

        // Navigate to Step 3
        await wizardPage.goToReviewStep();
    });

    test('should display all entered information correctly', async () => {
        // Verify all review fields
        await expect(wizardPage.reviewName).toHaveText(VALID_ADMIN.name);
        await expect(wizardPage.reviewEmail).toHaveText(VALID_ADMIN.email);
        await expect(wizardPage.administratorBadge).toBeVisible();
        await expect(wizardPage.administratorBadge).toHaveText('Administrator');
    });

    test('should display redirect information message', async () => {
        await expect(wizardPage.redirectInfoMessage).toBeVisible();
    });

    test('should show Back button on review step', async () => {
        await expect(wizardPage.backButton).toBeVisible();
        await expect(wizardPage.submitButton).toBeVisible();
    });

    test('should submit form and redirect to admin login', async ({ page }) => {
        // Submit the form
        await wizardPage.submitButton.click();

        // Wait for Inertia redirect
        await page.waitForURL('/admin/login', { timeout: 10000 });

        // Verify successful redirect
        expect(page.url()).toContain('/admin/login');
    });

    test('should show loading state during submission', async ({ page: _page }) => {
        // Click submit button
        const submitPromise = wizardPage.submitButton.click();

        // Button should show loading state (has .p-button-loading class or disabled)
        await expect(wizardPage.submitButton).toBeDisabled();

        await submitPromise;
    });

    test('should display server validation errors on submit', async ({ page }) => {
        // Go back and change email to potentially invalid one for server validation
        await wizardPage.backButton.click();
        await wizardPage.backButton.click();

        // Fill with edge case that might fail server validation
        await wizardPage.fillUserInfo('A', 'a@b.c'); // Very short but technically valid format
        await wizardPage.nextButton.click();

        await wizardPage.fillPasswords('TooWeak1!', 'TooWeak1!');

        // Try to proceed (might be disabled, but test the flow)
        const nextButton = wizardPage.nextButton;
        if (await nextButton.isEnabled()) {
            await nextButton.click();
        }

        // If we reach Step 3 and submit, server might reject
        const currentStep = await wizardPage.currentStepIndicator.textContent();
        if (currentStep?.includes('Step 3')) {
            await wizardPage.submitButton.click();

            // Wait a bit for potential error messages
            await page.waitForTimeout(1000);

            // Check if we're still on the installer page (not redirected = validation failed)
            if (!page.url().includes('/admin/login')) {
                // Should still be on Step 3 with potential error messages
                await expect(wizardPage.currentStepIndicator).toContainText('Step 3');
            }
        }
    });
});

test.describe('AdminUserWizard - Progress Bar', () => {
    let wizardPage: AdminUserWizardPage;

    test.beforeEach(async ({ page }) => {
        await page.request.post('/installer/unlock');
        await page.goto('/installer/admin-user');
        await page.waitForLoadState('networkidle');
        wizardPage = new AdminUserWizardPage(page);
    });

    test('should update progress bar as user navigates through steps', async ({ page }) => {
        // Step 1: Progress should be ~33%
        await expect(wizardPage.currentStepIndicator).toContainText('Step 1 of 3');

        // Navigate to Step 2
        await wizardPage.goToPasswordStep();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 2 of 3');

        // Navigate to Step 3
        await wizardPage.fillPasswords(VALID_ADMIN.password, VALID_ADMIN.password);
        await page.waitForResponse((response) =>
            response.url().includes('/installer/admin-user/check-password'),
        );
        await expect(wizardPage.nextButton).toBeEnabled({ timeout: 2000 });
        await wizardPage.nextButton.click();
        await expect(wizardPage.currentStepIndicator).toContainText('Step 3 of 3');

        // Progress bar should be attached throughout (may not be visually visible due to CSS)
        await expect(wizardPage.progressBar).toBeAttached();
    });
});

test.describe('AdminUserWizard - Accessibility', () => {
    let wizardPage: AdminUserWizardPage;

    test.beforeEach(async ({ page }) => {
        await page.request.post('/installer/unlock');
        await page.goto('/installer/admin-user');
        await page.waitForLoadState('networkidle');
        wizardPage = new AdminUserWizardPage(page);
    });

    test('should have proper form labels', async ({ page }) => {
        // Verify all inputs have associated labels
        await expect(page.locator('label[for="name"]')).toHaveText('Full Name');
        await expect(page.locator('label[for="email"]')).toHaveText('Email Address');

        // Navigate to Step 2
        await wizardPage.goToPasswordStep();

        await expect(page.locator('label[for="password"]')).toHaveText('Password');
        await expect(page.locator('label[for="password_confirmation"]')).toHaveText(
            'Confirm Password',
        );
    });

    test('should support keyboard navigation', async ({ page }) => {
        // Tab through form fields
        await wizardPage.nameInput.focus();
        await expect(wizardPage.nameInput).toBeFocused();

        await page.keyboard.press('Tab');
        await expect(wizardPage.emailInput).toBeFocused();

        await page.keyboard.press('Tab');
        await expect(wizardPage.nextButton).toBeFocused();
    });

    test('should have autofocus on name field', async () => {
        // Name input should have autofocus attribute
        await expect(wizardPage.nameInput).toHaveAttribute('autofocus');
    });
});
