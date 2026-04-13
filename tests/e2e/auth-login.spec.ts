import { expect, test } from '@playwright/test';

const adminEmail = process.env.PLAYWRIGHT_ADMIN_EMAIL || 'admin@fin.test';
const adminPassword = process.env.PLAYWRIGHT_ADMIN_PASSWORD || 'password';

test('admin can sign in and reach the dashboard', async ({ page }) => {
    await page.goto('/login');

    await expect(page.getByText('Welcome back')).toBeVisible();

    await page.getByLabel('Email').fill(adminEmail);
    await page.getByLabel('Password').fill(adminPassword);
    await page.getByRole('button', { name: 'Log in' }).click();

    await expect(page).toHaveURL(/dashboard/);
    await expect(page.getByText('Current Balance')).toBeVisible();
    await expect(page.getByText('Recent Transactions')).toBeVisible();
});
