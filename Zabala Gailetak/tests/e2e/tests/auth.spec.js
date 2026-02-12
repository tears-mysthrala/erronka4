const { test, expect } = require('@playwright/test');

/**
 * Authentication Tests
 * Tests: Login, MFA, Session Management, RBAC
 */

test.describe('Authentication & Authorization', () => {
  
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('should display login page correctly', async ({ page }) => {
    await expect(page).toHaveTitle(/Login|Hasi saioa/);
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('should reject invalid credentials', async ({ page }) => {
    await page.fill('input[name="email"]', 'invalid@example.com');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');
    
    await expect(page.locator('.alert-danger, .error-message')).toBeVisible();
    await expect(page.locator('.alert-danger, .error-message')).toContainText(/invalid|incorrect|error/);
  });

  test('should login with valid credentials', async ({ page }) => {
    await page.fill('input[name="email"]', process.env.TEST_USER || 'test@zabala.com');
    await page.fill('input[name="password"]', process.env.TEST_PASS || 'TestPass123!');
    await page.click('button[type="submit"]');
    
    await expect(page).toHaveURL(/mfa|dashboard/);
  });

  test('should enforce rate limiting', async ({ page }) => {
    for (let i = 0; i < 6; i++) {
      await page.fill('input[name="email"]', 'test@zabala.com');
      await page.fill('input[name="password"]', `wrong${i}`);
      await page.click('button[type="submit"]');
      await page.waitForTimeout(500);
    }
    
    await expect(page.locator('text=/rate limit|too many|blokeado/i')).toBeVisible();
  });
});

test.describe('RBAC - Role Based Access Control', () => {
  
  test('admin should access admin panel', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@zabala.com');
    await page.fill('input[name="password"]', 'AdminPass123!');
    await page.click('button[type="submit"]');
    
    await page.goto('/admin');
    await expect(page.locator('h1, h2')).toContainText(/admin|kudeaketa/);
  });

  test('employee should NOT access admin panel', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'employee@zabala.com');
    await page.fill('input[name="password"]', 'EmployeePass123!');
    await page.click('button[type="submit"]');
    
    await page.goto('/admin');
    await expect(page).toHaveURL(/403|unauthorized/);
  });
});
