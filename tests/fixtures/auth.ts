import { test as base, expect, Page } from '@playwright/test'
import { TEST_USER, ADMIN_USER, PAGES } from './data'

/** Login via WooCommerce My Account page */
async function loginAs(page: Page, username: string, password: string) {
  await page.goto(PAGES.myAccount)
  await page.waitForLoadState('networkidle')

  await page.locator('#username').fill(username)
  await page.locator('#password').fill(password)
  await page.locator('[name="login"]').click()

  await page.waitForLoadState('networkidle')
  await expect(page.locator('.woocommerce-MyAccount-content')).toBeVisible()
}

/** Extended test fixture with authentication helpers */
export const test = base.extend<{
  authenticatedPage: Page
  adminPage: Page
}>({
  authenticatedPage: async ({ page }, use) => {
    await loginAs(page, TEST_USER.username, TEST_USER.password)
    await use(page)
  },
  adminPage: async ({ page }, use) => {
    await loginAs(page, ADMIN_USER.username, ADMIN_USER.password)
    await use(page)
  },
})

export { expect, loginAs }
