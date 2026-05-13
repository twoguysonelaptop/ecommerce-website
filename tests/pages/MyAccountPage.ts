import { Page, Locator } from '@playwright/test'

export class MyAccountPage {
  readonly page: Page

  // Login form
  readonly usernameInput: Locator
  readonly passwordInput: Locator
  readonly loginButton: Locator
  readonly rememberMeCheckbox: Locator

  // Registration form (WooCommerce auto-generates password — no password field)
  readonly regEmailInput: Locator
  readonly registerButton: Locator

  // Account navigation
  readonly accountNav: Locator
  readonly dashboardLink: Locator
  readonly ordersLink: Locator
  readonly addressesLink: Locator
  readonly accountDetailsLink: Locator
  readonly logoutLink: Locator
  readonly wishlistLink: Locator

  // Dashboard content
  readonly accountContent: Locator
  readonly welcomeGreeting: Locator
  readonly welcomeEyebrow: Locator

  // Error/success messages
  readonly errorMessage: Locator
  readonly successMessage: Locator

  // Orders table
  readonly ordersTable: Locator
  readonly noOrdersMessage: Locator

  constructor(page: Page) {
    this.page = page

    // Login
    this.usernameInput = page.locator('#username')
    this.passwordInput = page.locator('#password')
    this.loginButton = page.locator('[name="login"]')
    this.rememberMeCheckbox = page.locator('#rememberme')

    // Registration (no password field — WooCommerce auto-generates)
    this.regEmailInput = page.locator('#reg_email')
    this.registerButton = page.locator('[name="register"]')

    // Navigation
    this.accountNav = page.locator('.woocommerce-MyAccount-navigation')
    this.dashboardLink = page.locator('.woocommerce-MyAccount-navigation-link--dashboard a')
    this.ordersLink = page.locator('.woocommerce-MyAccount-navigation-link--orders a')
    this.addressesLink = page.locator('.woocommerce-MyAccount-navigation-link--edit-address a')
    this.accountDetailsLink = page.locator('.woocommerce-MyAccount-navigation-link--edit-account a')
    this.logoutLink = page.locator('.woocommerce-MyAccount-navigation-link--customer-logout a')
    this.wishlistLink = page.locator('.woocommerce-MyAccount-navigation-link--wishlist a')

    // Content
    this.accountContent = page.locator('.woocommerce-MyAccount-content')
    this.welcomeGreeting = page.locator('.odd-account-greeting')
    this.welcomeEyebrow = page.locator('.odd-account-eyebrow')

    // Messages
    this.errorMessage = page.locator('.woocommerce-error, .wc-block-components-notice-banner.is-error, .woocommerce-notices-wrapper .is-error')
    this.successMessage = page.locator('.woocommerce-message')

    // Orders
    this.ordersTable = page.locator('.woocommerce-orders-table')
    this.noOrdersMessage = page.locator('.woocommerce-info, .woocommerce-message')
  }

  async goto() {
    await this.page.goto('/my-account/')
    await this.page.waitForLoadState('networkidle')
  }

  async login(username: string, password: string) {
    // Ensure form is ready before filling
    await this.usernameInput.waitFor({ state: 'visible', timeout: 5000 })
    await this.usernameInput.fill(username)
    await this.passwordInput.fill(password)
    await this.loginButton.click()
    await this.page.waitForLoadState('networkidle')
  }

  async register(email: string) {
    await this.regEmailInput.fill(email)
    await this.registerButton.click()
    await this.page.waitForLoadState('networkidle')
  }

  async logout() {
    await this.logoutLink.click()
    await this.page.waitForLoadState('networkidle')
  }

  async navigateTo(section: 'dashboard' | 'orders' | 'addresses' | 'account-details' | 'wishlist') {
    const links = {
      dashboard: this.dashboardLink,
      orders: this.ordersLink,
      addresses: this.addressesLink,
      'account-details': this.accountDetailsLink,
      wishlist: this.wishlistLink,
    }
    await links[section].click()
    await this.page.waitForLoadState('networkidle')
  }

  async isLoggedIn(): Promise<boolean> {
    return this.accountNav.isVisible()
  }

  async hasLoginForm(): Promise<boolean> {
    return this.usernameInput.isVisible()
  }

  async getGreetingName(): Promise<string> {
    return (await this.welcomeGreeting.textContent()) ?? ''
  }
}
