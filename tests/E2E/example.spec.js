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

    test('navigation is visible', async ({ page }) => {
        await page.goto('/');

        // Check if navigation elements exist
        // Adjust selectors based on your actual application structure
        const nav = page.locator('nav');
        await expect(nav).toBeVisible();
    });
});
