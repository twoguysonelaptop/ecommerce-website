import { Page, Locator } from '@playwright/test'

export class ProductPage {
  readonly page: Page

  // Hero band
  readonly heroBand: Locator
  readonly breadcrumb: Locator
  readonly productTitle: Locator
  readonly productPrice: Locator
  readonly addToCartButton: Locator
  readonly ratingStars: Locator
  readonly activeTags: Locator

  // Tabs
  readonly tabs: Locator
  readonly tabContents: Locator

  // Does / Doesn't
  readonly doesGrid: Locator
  readonly doesYesItems: Locator
  readonly doesNoItems: Locator

  // Ingredients
  readonly ingredientRows: Locator

  // Honest strip
  readonly honestStrip: Locator

  // Review cards
  readonly reviewCards: Locator

  // Footer CTA
  readonly footerCta: Locator

  constructor(page: Page) {
    this.page = page

    this.heroBand = page.locator('.hero-band')
    this.breadcrumb = page.locator('.breadcrumb')
    this.productTitle = page.locator('.hero-info h1, .hero-band h1')
    this.productPrice = page.locator('.price')
    this.addToCartButton = page.locator('.btn-add')
    this.ratingStars = page.locator('.stars')
    this.activeTags = page.locator('.active-tag')

    this.tabs = page.locator('.tab')
    this.tabContents = page.locator('.tab-content')

    this.doesGrid = page.locator('.does-grid')
    this.doesYesItems = page.locator('.does-yes .does-item')
    this.doesNoItems = page.locator('.does-no .does-item')

    this.ingredientRows = page.locator('.ing-row')

    this.honestStrip = page.locator('.honest-strip')

    this.reviewCards = page.locator('.review-card, .r-card')

    this.footerCta = page.locator('.footer-cta')
  }

  async goto(slug: string) {
    await this.page.goto(`/${slug}/`)
    await this.page.waitForLoadState('networkidle')
  }

  async switchTab(tabName: string) {
    await this.tabs.filter({ hasText: tabName }).click()
    // Wait for tab content to become visible
    await this.page.waitForTimeout(300)
  }

  async getActiveTabName(): Promise<string> {
    return this.tabs.filter({ has: this.page.locator('.active') }).textContent() ?? ''
  }

  async expandIngredient(index: number) {
    await this.ingredientRows.nth(index).click()
  }

  async isIngredientExpanded(index: number): Promise<boolean> {
    const cls = await this.ingredientRows.nth(index).getAttribute('class') ?? ''
    return cls.includes('open')
  }

  async getIngredientCount(): Promise<number> {
    return this.ingredientRows.count()
  }

  async getActiveTagTexts(): Promise<string[]> {
    return this.activeTags.allTextContents()
  }
}
