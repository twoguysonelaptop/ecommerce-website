import { Page, Locator } from '@playwright/test'

export class HomePage {
  readonly page: Page

  // Hero
  readonly heroSection: Locator
  readonly heroTitle: Locator
  readonly heroCtaButton: Locator

  // Product grid
  readonly productGrid: Locator
  readonly productCards: Locator
  readonly bundleRow: Locator

  // Why 4
  readonly why4Section: Locator
  readonly why4Items: Locator

  // Routine
  readonly routineSection: Locator
  readonly amRoutineSteps: Locator
  readonly pmRoutineSteps: Locator

  // Honest cards
  readonly honestSection: Locator
  readonly honestCards: Locator

  // FAQ
  readonly faqSection: Locator
  readonly faqItems: Locator

  // Reviews
  readonly reviewCards: Locator

  // Footer CTA
  readonly footerCta: Locator
  readonly footerCtaButton: Locator

  // Header
  readonly accountIcon: Locator
  readonly cartIcon: Locator

  constructor(page: Page) {
    this.page = page

    this.heroSection = page.locator('.hero')
    this.heroTitle = page.locator('.hero-title')
    this.heroCtaButton = page.locator('.hero-btns .btn-dark')

    this.productGrid = page.locator('.product-grid')
    this.productCards = page.locator('.p-card')
    this.bundleRow = page.locator('.bundle-row')

    this.why4Section = page.locator('.why4')
    this.why4Items = page.locator('.why4-item')

    this.routineSection = page.locator('.routine')
    this.amRoutineSteps = page.locator('.routine-col.am .routine-step')
    this.pmRoutineSteps = page.locator('.routine-col.pm .routine-step')

    this.honestSection = page.locator('.honest')
    this.honestCards = page.locator('.honest-card')

    this.faqSection = page.locator('.faq')
    this.faqItems = page.locator('.faq-item')

    this.reviewCards = page.locator('.r-card')

    this.footerCta = page.locator('.footer-cta')
    this.footerCtaButton = page.locator('.footer-cta .btn-sage')

    this.accountIcon = page.locator('a[aria-label="Account"]')
    this.cartIcon = page.locator('a[aria-label="Cart"]')
  }

  async goto() {
    await this.page.goto('/')
    await this.page.waitForLoadState('networkidle')
  }

  async getProductCardNames(): Promise<string[]> {
    return this.productCards.locator('.p-card-name').allTextContents()
  }

  async getProductCardPrices(): Promise<string[]> {
    return this.productCards.locator('.p-card-price').allTextContents()
  }

  async clickProduct(name: string) {
    await this.productCards.filter({ hasText: name }).click()
    await this.page.waitForLoadState('networkidle')
  }

  async clickBundle() {
    await this.bundleRow.click()
    await this.page.waitForLoadState('networkidle')
  }

  async toggleFaq(index: number) {
    const item = this.faqItems.nth(index)
    // Try clicking the item directly — WordPress may attach onclick to the parent
    await item.click()
    await this.page.waitForTimeout(500)

    // If that didn't toggle, try dispatching click via JS on the element
    const cls = await item.getAttribute('class') ?? ''
    if (!cls.includes('open')) {
      await item.evaluate((el: HTMLElement) => el.click())
      await this.page.waitForTimeout(300)
    }
  }

  async isFaqOpen(index: number): Promise<boolean> {
    const item = this.faqItems.nth(index)
    const cls = await item.getAttribute('class') ?? ''
    // Check for 'open' class or expanded state via style/display
    if (cls.includes('open')) return true
    // Fallback: check if the answer element is visible
    const answer = item.locator('.faq-a')
    if (await answer.count() > 0) {
      return answer.isVisible()
    }
    return false
  }
}
