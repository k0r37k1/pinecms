import { defineConfig, devices } from '@playwright/test';

/**
 * Playwright E2E Test Configuration
 *
 * @see https://playwright.dev/docs/test-configuration
 */
export default defineConfig({
    // Test directory
    testDir: './tests/E2E',

    // Maximum time one test can run (30 seconds)
    timeout: 30 * 1000,

    // Run tests in files in parallel
    fullyParallel: true,

    // Fail the build on CI if you accidentally left test.only in the source code
    forbidOnly: !!process.env.CI,

    // Retry on CI only
    retries: process.env.CI ? 2 : 0,

    // Number of parallel workers
    workers: process.env.CI ? 4 : 4,

    // Reporter to use
    reporter: [
        ['list'],
        ['html', { outputFolder: 'storage/playwright/html-report', open: 'never' }],
        ['json', { outputFile: 'storage/playwright/test-results.json' }],
        ['junit', { outputFile: 'storage/playwright/junit.xml' }],
    ],

    // Shared settings for all the projects below
    use: {
        // Base URL to use in actions like `await page.goto('/')`
        baseURL: process.env.APP_URL || 'http://localhost:8000',

        // Collect trace when retrying the failed test
        trace: 'on-first-retry',

        // Take screenshot on failure
        screenshot: 'only-on-failure',

        // Record video on failure
        video: 'retain-on-failure',

        // Browser viewport
        viewport: { width: 1280, height: 720 },

        // Ignore HTTPS errors (for local development)
        ignoreHTTPSErrors: true,

        // Timeout for each action (10 seconds)
        actionTimeout: 10 * 1000,

        // Timeout for navigation (30 seconds)
        navigationTimeout: 30 * 1000,
    },

    // Configure projects - All browsers locally, Chromium only in CI
    projects: process.env.CI
        ? [
              // CI: Chromium only for faster execution
              {
                  name: 'chromium',
                  use: {
                      ...devices['Desktop Chrome'],
                      launchOptions: {
                          args: [
                              '--disable-dev-shm-usage',
                              '--disable-blink-features=AutomationControlled',
                          ],
                      },
                  },
              },
          ]
        : [
              // Local: All browsers for comprehensive testing
              {
                  name: 'chromium',
                  use: {
                      ...devices['Desktop Chrome'],
                      launchOptions: {
                          args: ['--disable-blink-features=AutomationControlled'],
                      },
                  },
              },
              {
                  name: 'firefox',
                  use: { ...devices['Desktop Firefox'] },
              },
              {
                  name: 'webkit',
                  use: { ...devices['Desktop Safari'] },
              },
          ],

    // Run your local dev server before starting the tests
    webServer: {
        command: 'php artisan serve',
        url: 'http://localhost:8000',
        reuseExistingServer: !process.env.CI,
        timeout: 120 * 1000,
        stdout: 'ignore',
        stderr: 'pipe',
    },

    // Output folder for test artifacts
    outputDir: 'storage/playwright/test-results',
});
