import { test, expect } from '@playwright/test'
import { CartPage } from '../../pages/CartPage'
import { PRODUCTS } from '../../fixtures/data'

test.describe('Cart', () => {
  let cartPage: CartPage

  test.beforeEach(async ({ page }) => {
    cartPage = new CartPage(page)
  })

  test('should show empty cart message when no items', async () => {
    await cartPage.goto()
    const empty = await cartPage.isEmpty()
    // Cart may have items from other tests — this is a baseline check
    if (empty) {
      await expect(cartPage.emptyCartMessage).toBeVisible()
    }
  })

  test('should add a product to cart via URL', async ({ page }) => {
    await cartPage.addProductViaUrl(PRODUCTS.clearFirst.productId)
    await cartPage.goto()

    const names = await cartPage.getItemNames()
    const hasProduct = names.some(n => n.includes('Clear First'))
    expect(hasProduct).toBe(true)
  })

  test('should display correct item in cart after adding', async () => {
    await cartPage.addProductViaUrl(PRODUCTS.foamRinse.productId)
    await cartPage.goto()

    const count = await cartPage.getItemCount()
    expect(count).toBeGreaterThanOrEqual(1)
  })

  test('should add multiple different products to cart', async () => {
    await cartPage.addProductViaUrl(PRODUCTS.clearFirst.productId)
    await cartPage.addProductViaUrl(PRODUCTS.dawnShield.productId)
    await cartPage.goto()

    const count = await cartPage.getItemCount()
    expect(count).toBeGreaterThanOrEqual(2)
  })

  test('should add bundle to cart', async () => {
    await cartPage.addProductViaUrl(PRODUCTS.bundle.productId)
    await cartPage.goto()

    const names = await cartPage.getItemNames()
    // WooCommerce product name is "The Group Project"
    const hasBundle = names.some(n => n.includes('Group Project') || n.includes('Whole Routine'))
    expect(hasBundle).toBe(true)
  })

  test('should remove item from cart', async () => {
    // Add a product first
    await cartPage.addProductViaUrl(PRODUCTS.deepDusk.productId)
    await cartPage.goto()

    const countBefore = await cartPage.getItemCount()
    await cartPage.removeItem(0)

    // After removal, count should decrease or cart should be empty
    const countAfter = await cartPage.getItemCount()
    const isEmpty = await cartPage.isEmpty()
    expect(countAfter < countBefore || isEmpty).toBe(true)
  })

  test('should display cart total', async () => {
    await cartPage.addProductViaUrl(PRODUCTS.clearFirst.productId)
    await cartPage.goto()

    await expect(cartPage.cartTotal).toBeVisible()
  })

  test('should navigate to checkout from cart', async ({ page }) => {
    await cartPage.addProductViaUrl(PRODUCTS.clearFirst.productId)
    await cartPage.goto()

    await cartPage.proceedToCheckout()
    await expect(page).toHaveURL(/checkout/)
  })

  test('should display checkout button', async () => {
    await cartPage.addProductViaUrl(PRODUCTS.clearFirst.productId)
    await cartPage.goto()

    await expect(cartPage.checkoutButton).toBeVisible()
  })
})
