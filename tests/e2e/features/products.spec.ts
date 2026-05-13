import { test, expect } from '@playwright/test'
import { ProductPage } from '../../pages/ProductPage'
import { PRODUCTS } from '../../fixtures/data'

const individualProducts = [
  PRODUCTS.clearFirst,
  PRODUCTS.foamRinse,
  PRODUCTS.dawnShield,
  PRODUCTS.deepDusk,
]

test.describe('Product Pages', () => {
  let productPage: ProductPage

  test.beforeEach(async ({ page }) => {
    productPage = new ProductPage(page)
  })

  for (const product of individualProducts) {
    test.describe(product.name, () => {
      test.beforeEach(async () => {
        await productPage.goto(product.slug)
      })

      test('should display hero band with product info', async () => {
        await expect(productPage.heroBand).toBeVisible()
        await expect(productPage.productTitle).toContainText(product.name)
      })

      test('should display correct price', async () => {
        await expect(productPage.productPrice).toContainText(
          product.priceNum.toString()
        )
      })

      test('should display breadcrumb navigation', async () => {
        await expect(productPage.breadcrumb).toBeVisible()
      })

      test('should display star rating', async () => {
        await expect(productPage.ratingStars).toBeVisible()
      })

      test('should display active ingredient tags', async () => {
        const tags = await productPage.getActiveTagTexts()
        expect(tags.length).toBeGreaterThan(0)
      })

      test('should display add-to-cart button', async ({ page }) => {
        // Editorial pages use .btn-add; WooCommerce product pages use .single_add_to_cart_button
        const addBtn = page.locator('.btn-add, .single_add_to_cart_button, button:has-text("Add to cart")')
        await expect(addBtn.first()).toBeVisible()
      })

      test('should have tabbed navigation', async () => {
        const tabCount = await productPage.tabs.count()
        expect(tabCount).toBeGreaterThanOrEqual(3)
      })

      test('should switch between tabs', async () => {
        const tabCount = await productPage.tabs.count()
        if (tabCount > 1) {
          // Click second tab
          await productPage.tabs.nth(1).click()
          await productPage.page.waitForTimeout(300)

          // Verify tab is active
          await expect(productPage.tabs.nth(1)).toHaveClass(/active/)
        }
      })

      test('should display does/doesn\'t grid', async ({ page }) => {
        // Navigate to the "does" tab if needed
        const doesTab = productPage.tabs.filter({ hasText: /does/i })
        if (await doesTab.count() > 0) {
          await doesTab.click()
          await page.waitForTimeout(300)
        }

        await expect(productPage.doesGrid).toBeVisible()
        const yesCount = await productPage.doesYesItems.count()
        const noCount = await productPage.doesNoItems.count()
        expect(yesCount).toBeGreaterThan(0)
        expect(noCount).toBeGreaterThan(0)
      })

      test('should have expandable ingredients', async () => {
        // Navigate to ingredients tab
        const ingTab = productPage.tabs.filter({ hasText: /ingredient/i })
        if (await ingTab.count() > 0) {
          await ingTab.click()
          await productPage.page.waitForTimeout(300)
        }

        const count = await productPage.getIngredientCount()
        expect(count).toBeGreaterThan(0)
      })

      test('should expand ingredient on click', async ({ page }) => {
        const ingTab = productPage.tabs.filter({ hasText: /ingredient/i })
        if (await ingTab.count() > 0) {
          await ingTab.click()
          await page.waitForTimeout(300)
        }

        const count = await productPage.getIngredientCount()
        if (count > 0) {
          await productPage.expandIngredient(0)
          // Check if the detail section becomes visible after click
          const detail = productPage.ingredientRows.nth(0).locator('.ing-detail')
          if (await detail.count() > 0) {
            await expect(detail).toBeVisible()
          }
        }
      })

      test('should display honest disclosure section', async ({ page }) => {
        // Call switchTab('honest') directly in page context
        await page.evaluate('switchTab("honest")')
        await page.waitForTimeout(500)

        // Check for honest content inside the active tab
        // Note: .honest-strip exists in multiple tab-content divs, so scope to #tab-honest
        // Deep Dusk uses .honest-strip-dark variant
        const honestTab = page.locator('#tab-honest')
        await expect(honestTab).toHaveClass(/active/)
        const honestContent = honestTab.locator('.honest-strip, .honest-strip-dark, .honest-card, .honest-cards, .dark-card')
        await expect(honestContent.first()).toBeVisible()
      })

      test('should display footer CTA with bundle cross-reference', async () => {
        await expect(productPage.footerCta).toBeVisible()
        const ctaText = await productPage.footerCta.textContent()
        expect(ctaText).toContain('1,499')
      })
    })
  }

  test.describe('The Whole Routine (Bundle)', () => {
    test.beforeEach(async () => {
      await productPage.goto(PRODUCTS.bundle.slug)
    })

    test('should display bundle page', async () => {
      await expect(productPage.heroBand).toBeVisible()
    })

    test('should show bundle pricing with savings', async ({ page }) => {
      const content = await page.textContent('body')
      expect(content).toContain('1,499')
    })

    test('should reference all 4 products', async ({ page }) => {
      const content = await page.textContent('body')
      expect(content).toContain('Clear First')
      expect(content).toContain('Foam Rinse')
      expect(content).toContain('Dawn Shield')
      expect(content).toContain('Deep Dusk')
    })
  })
})
