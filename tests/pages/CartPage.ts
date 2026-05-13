import { Page, Locator } from '@playwright/test'

export class CartPage {
  readonly page: Page

  // WooCommerce block cart selectors
  readonly cartBlock: Locator
  readonly cartItems: Locator
  readonly cartItemNames: Locator
  readonly cartTotal: Locator
  readonly emptyCartMessage: Locator
  readonly checkoutButton: Locator
  readonly quantityInputs: Locator
  readonly removeButtons: Locator
  readonly couponInput: Locator
  readonly applyCouponButton: Locator

  constructor(page: Page) {
    this.page = page

    this.cartBlock = page.locator('.wc-block-cart, .woocommerce-cart')
    this.cartItems = page.locator('.wc-block-cart-items__row, .woocommerce-cart-form .cart_item')
    this.cartItemNames = page.locator('.wc-block-components-product-name, .product-name a')
    this.cartTotal = page.locator('.wc-block-components-totals-footer-item .wc-block-components-totals-item__value, .order-total .amount')
    this.emptyCartMessage = page.locator('.wc-block-cart__empty-cart__title, .cart-empty')
    this.checkoutButton = page.locator('.wc-block-cart__submit-button, .checkout-button')
    this.quantityInputs = page.locator('.wc-block-components-quantity-selector__input, .qty')
    this.removeButtons = page.locator('.wc-block-cart-item__remove-link, .remove')
    this.couponInput = page.locator('.wc-block-components-totals-coupon__input input, #coupon_code')
    this.applyCouponButton = page.locator('.wc-block-components-totals-coupon__button, [name="apply_coupon"]')
  }

  async goto() {
    await this.page.goto('/cart/')
    await this.page.waitForLoadState('networkidle')
    // Wait for WooCommerce block cart to finish React hydration
    await this.page.waitForTimeout(1000)
    // If cart has items, wait for product names to render (not just skeleton)
    const hasItems = await this.cartItems.count() > 0
    if (hasItems) {
      await this.cartItemNames.first().waitFor({ state: 'visible', timeout: 5000 }).catch(() => {})
    }
  }

  async getItemCount(): Promise<number> {
    return this.cartItems.count()
  }

  async getItemNames(): Promise<string[]> {
    return this.cartItemNames.allTextContents()
  }

  async removeItem(index: number) {
    const countBefore = await this.cartItems.count()
    await this.removeButtons.nth(index).click()
    await this.page.waitForLoadState('networkidle')
    // Wait for WooCommerce block cart to update (React re-render)
    await this.page.waitForTimeout(2000)
    // Wait for item count to change or empty cart message
    if (countBefore > 1) {
      await this.page.waitForFunction(
        (expected) => document.querySelectorAll('.wc-block-cart-items__row, .cart_item').length < expected,
        countBefore,
        { timeout: 5000 }
      ).catch(() => {})
    } else {
      await this.emptyCartMessage.waitFor({ state: 'visible', timeout: 5000 }).catch(() => {})
    }
  }

  async updateQuantity(index: number, quantity: number) {
    const input = this.quantityInputs.nth(index)
    await input.fill(String(quantity))
    await input.press('Tab')
    await this.page.waitForLoadState('networkidle')
  }

  async applyCoupon(code: string) {
    await this.couponInput.fill(code)
    await this.applyCouponButton.click()
    await this.page.waitForLoadState('networkidle')
  }

  async proceedToCheckout() {
    await this.checkoutButton.click()
    await this.page.waitForLoadState('networkidle')
  }

  async addProductViaUrl(productId: number) {
    await this.page.goto(`/shop/?add-to-cart=${productId}`)
    await this.page.waitForLoadState('networkidle')
    // Wait for WooCommerce to process add-to-cart and show notice
    await this.page.waitForTimeout(1000)
    // Verify the "has been added to your cart" notice or cart badge updated
    await this.page.locator('.woocommerce-message, .wc-block-mini-cart__badge').first()
      .waitFor({ state: 'visible', timeout: 5000 }).catch(() => {})
  }

  async isEmpty(): Promise<boolean> {
    return this.emptyCartMessage.isVisible()
  }
}
