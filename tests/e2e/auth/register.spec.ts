import { test, expect } from '@playwright/test'
import { MyAccountPage } from '../../pages/MyAccountPage'

test.describe('Registration', () => {
  let accountPage: MyAccountPage

  test.beforeEach(async ({ page }) => {
    accountPage = new MyAccountPage(page)
    await accountPage.goto()
  })

  test('should display registration form on My Account page', async () => {
    await expect(accountPage.regEmailInput).toBeVisible()
    // WooCommerce auto-generates password — no password field
    await expect(accountPage.registerButton).toBeVisible()
  })

  test('should register a new account with valid email', async () => {
    const uniqueEmail = `test_${Date.now()}@oddcareco.test`
    await accountPage.register(uniqueEmail)

    // After successful registration, user should be logged in
    await expect(accountPage.accountNav).toBeVisible()
    await expect(accountPage.accountContent).toBeVisible()
  })

  test('should show error for duplicate email', async () => {
    // admin email already exists
    await accountPage.register('2guysonelaptop@gmail.com')

    await expect(accountPage.errorMessage).toBeVisible()
  })

  test('should show error for invalid email format', async ({ page }) => {
    await accountPage.register('notanemail')

    // WooCommerce may show server-side error or browser HTML5 validation blocks submission
    const hasServerError = await accountPage.errorMessage.isVisible()
    const hasValidationError = await page.locator('#reg_email:invalid').count() > 0
    expect(hasServerError || hasValidationError).toBe(true)
  })

  test('should show error for empty fields', async ({ page }) => {
    await accountPage.registerButton.click()
    await accountPage.page.waitForLoadState('networkidle')

    // Browser HTML5 validation may prevent submission, or WooCommerce shows error
    const hasServerError = await accountPage.errorMessage.isVisible()
    const hasValidationError = await page.locator('#reg_email:invalid').count() > 0
    expect(hasServerError || hasValidationError).toBe(true)
  })
})
