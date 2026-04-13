import { defineConfig } from '@playwright/test';

const port = Number(process.env.PLAYWRIGHT_PORT || 8000);
const baseURL = process.env.PLAYWRIGHT_BASE_URL || `http://127.0.0.1:${port}`;

export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: true,
    reporter: [['list'], ['html', { open: 'never' }]],
    use: {
        baseURL,
        trace: 'retain-on-failure',
        screenshot: 'only-on-failure',
    },
    webServer: {
        command: `php artisan serve --host=127.0.0.1 --port=${port}`,
        url: `${baseURL}/login`,
        reuseExistingServer: !process.env.CI,
        timeout: 120_000,
    },
});
