import { test, expect } from '@playwright/test'
import { CartPage } from '../../pages/CartPage'
import { CheckoutPage } from '../../pages/CheckoutPage'
import { PRODUCTS } from '../../fixtures/data'

test.describe('Checkout', () => {
  let cartPage: CartPage
  let checkoutPage: CheckoutPage

  test.beforeEach(async ({ page }) => {
    cartPage = new CartPage(page)
    checkoutPage = new CheckoutPage(page)

    // Add a product to cart before each checkout test
    await cartPage.addProductViaUrl(PRODUCTS.clearFirst.productId)
  })

  test('should display checkout form after adding product', async () => {
    await checkoutPage.goto()

    // Checkout should show form (not redirect to empty cart)
    const hasCheckout = await checkoutPage.checkoutBlock.isVisible()
    const hasFields = await checkoutPage.firstNameInput.first().isVisible()
    expect(hasCheckout || hasFields).toBe(true)
  })

  test('should display billing fields', async () => {
    await checkoutPage.goto()

    await expect(checkoutPage.firstNameInput.first()).toBeVisible()
    await expect(checkoutPage.lastNameInput.first()).toBeVisible()
    await expect(checkoutPage.addressInput.first()).toBeVisible()
    await expect(checkoutPage.cityInput.first()).toBeVisible()
    await expect(checkoutPage.postcodeInput.first()).toBeVisible()
  })

  test('should display place order button', async () => {
    await checkoutPage.goto()

    await expect(checkoutPage.placeOrderButton).toBeVisible()
  })

  test('should show errors when placing order with empty fields', async ({ page }) => {
    await checkoutPage.goto()

    await checkoutPage.placeOrder()

    // Should show validation errors
    const hasErrors = await checkoutPage.hasErrors()
    const hasInlineErrors = await page
      .locator('.wc-block-components-validation-error, .has-error, [aria-invalid="true"]')
      .count()
    expect(hasErrors || hasInlineErrors > 0).toBe(true)
  })

  test('should accept filled billing details', async () => {
    await checkoutPage.goto()

    await checkoutPage.fillBillingDetails({
      email: 'test@oddcareco.test',
      firstName: 'Test',
      lastName: 'Customer',
      address: '123 Test Street',
      city: 'Mumbai',
      state: 'Maharashtra',
      postcode: '400001',
      phone: '9876543210',
    })

    // Verify fields are filled (not submitting — no payment gateway configured)
    await expect(checkoutPage.firstNameInput.first()).toHaveValue('Test')
    await expect(checkoutPage.lastNameInput.first()).toHaveValue('Customer')
    await expect(checkoutPage.addressInput.first()).toHaveValue('123 Test Street')
  })

  test('should display currency as INR', async ({ page }) => {
    await checkoutPage.goto()

    const content = await page.textContent('body')
    expect(content).toMatch(/₹/)
  })

  test('should redirect to checkout from cart', async ({ page }) => {
    await cartPage.goto()
    const isEmpty = await cartPage.isEmpty()

    if (!isEmpty) {
      await cartPage.proceedToCheckout()
      await expect(page).toHaveURL(/checkout/)
    }
  })
})
