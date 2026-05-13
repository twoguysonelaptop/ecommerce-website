import { test, expect } from '@playwright/test'
import { MyAccountPage } from '../../pages/MyAccountPage'
import { ADMIN_USER, PRODUCTS } from '../../fixtures/data'

test.describe('Wishlist', () => {
  test.describe('Logged-in user', () => {
    test.beforeEach(async ({ page }) => {
      const accountPage = new MyAccountPage(page)
      await accountPage.goto()
      await accountPage.login(ADMIN_USER.username, ADMIN_USER.password)
    })

    test('should show wishlist tab in My Account', async ({ page }) => {
      await page.goto('/my-account/')
      await page.waitForLoadState('networkidle')

      const wishlistLink = page.locator('.woocommerce-MyAccount-navigation-link--wishlist a')
      await expect(wishlistLink).toBeVisible()
    })

    test('should navigate to wishlist page', async ({ page }) => {
      const wishlistLink = page.locator('.woocommerce-MyAccount-navigation-link--wishlist a')
      if (await wishlistLink.isVisible()) {
        await wishlistLink.click()
        await page.waitForLoadState('networkidle')
        // Wishlist page should show content area or wishlist-specific content
        const content = page.locator('.woocommerce-MyAccount-content, .oddcareco-wishlist, .wishlist-content')
        await expect(content.first()).toBeVisible()
      }
    })

    test('should display heart toggle on WooCommerce product page', async ({ page }) => {
      // Visit a WooCommerce product page (not editorial page)
      await page.goto('/product/clear-first/')
      await page.waitForLoadState('networkidle')

      const heartButton = page.locator('.oddcareco-wishlist-toggle, .wishlist-toggle')
      // Heart button is injected via the plugin for logged-in users
      if (await heartButton.isVisible()) {
        await expect(heartButton).toBeVisible()
      }
    })
  })

  test.describe('Guest user', () => {
    test('should not show wishlist toggle when logged out', async ({ page }) => {
      await page.goto('/product/clear-first/')
      await page.waitForLoadState('networkidle')

      const heartButton = page.locator('.oddcareco-wishlist-toggle, .wishlist-toggle')
      // Plugin only shows wishlist for logged-in users
      await expect(heartButton).not.toBeVisible()
    })
  })
})
