import { Page, Locator } from '@playwright/test'

export class CheckoutPage {
  readonly page: Page

  // WooCommerce block checkout selectors
  readonly checkoutBlock: Locator
  readonly emailInput: Locator
  readonly firstNameInput: Locator
  readonly lastNameInput: Locator
  readonly addressInput: Locator
  readonly cityInput: Locator
  readonly stateSelect: Locator
  readonly postcodeInput: Locator
  readonly phoneInput: Locator
  readonly placeOrderButton: Locator
  readonly orderConfirmation: Locator
  readonly orderNumber: Locator
  readonly errorNotice: Locator
  readonly paymentMethods: Locator

  constructor(page: Page) {
    this.page = page

    this.checkoutBlock = page.locator('.wp-block-woocommerce-checkout')
    // Block checkout shows shipping address by default (billing uses same address checkbox)
    this.emailInput = page.locator('#email')
    this.firstNameInput = page.locator('#shipping-first_name, #billing-first_name')
    this.lastNameInput = page.locator('#shipping-last_name, #billing-last_name')
    this.addressInput = page.locator('#shipping-address_1, #billing-address_1')
    this.cityInput = page.locator('#shipping-city, #billing-city')
    this.stateSelect = page.locator('#shipping-state, #billing-state')
    this.postcodeInput = page.locator('#shipping-postcode, #billing-postcode')
    this.phoneInput = page.locator('#shipping-phone, #billing-phone')
    this.placeOrderButton = page.locator('.wc-block-components-checkout-place-order-button, #place_order')
    this.orderConfirmation = page.locator('.woocommerce-order-received, .wc-block-order-confirmation-status')
    this.orderNumber = page.locator('.woocommerce-order-overview__order strong, .wc-block-order-confirmation-summary__order-number')
    this.errorNotice = page.locator('.wc-block-components-notice-banner.is-error, .woocommerce-error')
    this.paymentMethods = page.locator('.wc-block-components-radio-control__option, .wc_payment_method')
  }

  async goto() {
    await this.page.goto('/checkout/')
    await this.page.waitForLoadState('networkidle')
    // Wait for block checkout to finish React hydration (remove is-loading)
    await this.page.waitForFunction(
      () => !document.querySelector('.wc-block-checkout.is-loading'),
      { timeout: 10000 }
    ).catch(() => {})
    await this.page.waitForTimeout(1000)
  }

  async fillBillingDetails(details: {
    email: string
    firstName: string
    lastName: string
    address: string
    city: string
    state: string
    postcode: string
    phone: string
  }) {
    await this.emailInput.fill(details.email)
    await this.firstNameInput.fill(details.firstName)
    await this.lastNameInput.fill(details.lastName)
    await this.addressInput.fill(details.address)
    await this.cityInput.fill(details.city)
    await this.postcodeInput.fill(details.postcode)
    await this.phoneInput.fill(details.phone)

    // State is typically a dropdown in Indian checkout
    if (await this.stateSelect.first().isVisible()) {
      await this.stateSelect.first().selectOption({ label: details.state }).catch(() => {
        // Block checkout may use a combobox instead of select
      })
    }
  }

  async placeOrder() {
    await this.placeOrderButton.click()
    await this.page.waitForLoadState('networkidle')
  }

  async isOrderConfirmed(): Promise<boolean> {
    return this.orderConfirmation.isVisible()
  }

  async getOrderNumber(): Promise<string> {
    return (await this.orderNumber.textContent()) ?? ''
  }

  async hasErrors(): Promise<boolean> {
    return this.errorNotice.first().isVisible()
  }
}
