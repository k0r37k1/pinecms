import { test, expect } from '@playwright/test';

test.describe('Example E2E Tests', () => {
    test('homepage loads successfully', async ({ page }) => {
        // Navigate to the homepage
        await page.goto('/');

        // Wait for the page to be fully loaded
        await page.waitForLoadState('networkidle');

        // Check if the page title is set
        await expect(page).toHaveTitle(/PineCMS/i);

        // Take a screenshot for reference
        await page.screenshot({ path: 'storage/playwright/homepage.png' });
    });

    test('page content is visible', async ({ page }) => {
        await page.goto('/');

        // Check if the main body content exists
        const body = page.locator('body');
        await expect(body).toBeVisible();

        // Verify the page has loaded by checking for Laravel default classes
        await expect(page.locator('body')).toHaveClass(/font-sans/);
    });
});
