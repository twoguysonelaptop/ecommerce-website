import { test, expect } from '@playwright/test'
import { MyAccountPage } from '../../pages/MyAccountPage'
import { TEST_USER, ADMIN_USER } from '../../fixtures/data'

test.describe('Login', () => {
  let accountPage: MyAccountPage

  test.beforeEach(async ({ page }) => {
    accountPage = new MyAccountPage(page)
    await accountPage.goto()
  })

  test('should display login form on My Account page', async () => {
    await expect(accountPage.usernameInput).toBeVisible()
    await expect(accountPage.passwordInput).toBeVisible()
    await expect(accountPage.loginButton).toBeVisible()
  })

  test('should login with valid admin credentials', async ({ page }) => {
    await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)

    await expect(accountPage.accountNav).toBeVisible()
    await expect(accountPage.accountContent).toBeVisible()
    // WooCommerce dashboard stays at /my-account/ — verify we're logged in via nav
    await expect(accountPage.dashboardLink).toBeVisible()
  })

  test('should show custom branded dashboard after login', async () => {
    await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)

    await expect(accountPage.welcomeEyebrow).toHaveText('YOUR ACCOUNT')
    await expect(accountPage.welcomeGreeting).toContainText('Hey,')
  })

  test('should show error for invalid credentials', async () => {
    await accountPage.login('wronguser', 'wrongpass')

    await expect(accountPage.errorMessage).toBeVisible()
    await expect(accountPage.accountNav).not.toBeVisible()
  })

  test('should show error for empty credentials', async () => {
    await accountPage.loginButton.click()
    await accountPage.page.waitForLoadState('networkidle')

    await expect(accountPage.errorMessage).toBeVisible()
  })

  test('should show account navigation tabs after login', async () => {
    await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)

    await expect(accountPage.dashboardLink).toBeVisible()
    await expect(accountPage.ordersLink).toBeVisible()
    await expect(accountPage.addressesLink).toBeVisible()
    await expect(accountPage.accountDetailsLink).toBeVisible()
    await expect(accountPage.logoutLink).toBeVisible()
  })

  test('should not show Downloads tab (physical products only)', async () => {
    await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)

    const downloadsLink = accountPage.page.locator('.woocommerce-MyAccount-navigation-link--downloads')
    await expect(downloadsLink).not.toBeVisible()
  })
})
