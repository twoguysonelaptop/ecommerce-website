import { test, expect } from '@playwright/test'
import { MyAccountPage } from '../../pages/MyAccountPage'
import { ADMIN_USER } from '../../fixtures/data'

test.describe('Logout', () => {
  test('should logout and redirect to login form', async ({ page }) => {
    const accountPage = new MyAccountPage(page)
    await accountPage.goto()
    await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)

    // Verify logged in
    await expect(accountPage.accountNav).toBeVisible()

    // Logout
    await accountPage.logout()

    // Should show login form again
    await expect(accountPage.usernameInput).toBeVisible()
    await expect(accountPage.loginButton).toBeVisible()
    await expect(accountPage.accountNav).not.toBeVisible()
  })

  test('should not access account pages after logout', async ({ page }) => {
    const accountPage = new MyAccountPage(page)
    await accountPage.goto()
    await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)
    await accountPage.logout()

    // Try accessing orders page directly
    await page.goto('/my-account/orders/')
    await page.waitForLoadState('networkidle')

    // Should redirect to login form
    await expect(accountPage.usernameInput).toBeVisible()
  })
})
