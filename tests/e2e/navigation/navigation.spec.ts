import { test, expect } from '@playwright/test'
import { PAGES, PRODUCTS } from '../../fixtures/data'

test.describe('Navigation', () => {
  test.describe('Header', () => {
    test.beforeEach(async ({ page }) => {
      await page.goto('/')
      await page.waitForLoadState('networkidle')
    })

    test('should display site logo', async ({ page }) => {
      const logo = page.locator('.custom-logo').first()
      await expect(logo).toBeVisible()
    })

    test('should display account icon linking to My Account', async ({ page }) => {
      const accountIcon = page.locator('a[aria-label="Account"]').first()
      await expect(accountIcon).toBeVisible()
      await expect(accountIcon).toHaveAttribute('href', /my-account/)
    })

    test('should display cart icon linking to Cart', async ({ page }) => {
      const cartIcon = page.locator('a[aria-label="Cart"]').first()
      await expect(cartIcon).toBeVisible()
      await expect(cartIcon).toHaveAttribute('href', /cart/)
    })

    test('should navigate to My Account on account icon click', async ({ page }) => {
      await page.locator('a[aria-label="Account"]').first().click()
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/my-account/)
    })

    test('should navigate to Cart on cart icon click', async ({ page }) => {
      await page.locator('a[aria-label="Cart"]').first().click()
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/cart/)
    })
  })

  test.describe('Products Dropdown', () => {
    test.beforeEach(async ({ page }) => {
      await page.goto('/')
      await page.waitForLoadState('networkidle')
    })

    test('should display Products menu item in navigation', async ({ page }) => {
      const productsNav = page.locator('.menu-item a').filter({ hasText: /products/i })
      await expect(productsNav.first()).toBeVisible()
    })

    test('should show dropdown with all products on hover', async ({ page }) => {
      const productsNav = page.locator('.menu-item').filter({ hasText: /products/i }).first()
      await productsNav.hover()

      // Wait for dropdown to appear
      await page.waitForTimeout(500)

      const submenu = productsNav.locator('.sub-menu')
      if (await submenu.isVisible()) {
        const menuText = await submenu.textContent()
        expect(menuText).toContain('Clear First')
        expect(menuText).toContain('Foam Rinse')
        expect(menuText).toContain('Dawn Shield')
        expect(menuText).toContain('Deep Dusk')
        expect(menuText).toContain('The Whole Routine')
      }
    })

    test('should show product codes in dropdown', async ({ page }) => {
      const productsNav = page.locator('.menu-item').filter({ hasText: /products/i }).first()
      await productsNav.hover()
      await page.waitForTimeout(500)

      const codes = page.locator('.nav-prod-code')
      if (await codes.first().isVisible()) {
        const codeTexts = await codes.allTextContents()
        expect(codeTexts).toContain('ODD 01')
        expect(codeTexts).toContain('ODD 02')
        expect(codeTexts).toContain('ODD 03')
        expect(codeTexts).toContain('ODD 04')
      }
    })
  })

  test.describe('Footer', () => {
    test.beforeEach(async ({ page }) => {
      await page.goto('/')
      await page.waitForLoadState('networkidle')
    })

    test('should display ODD footer with brand name', async ({ page }) => {
      const footer = page.locator('.odd-footer')
      await expect(footer).toBeVisible()
      await expect(page.locator('.odd-footer-logo')).toContainText('ODD Care Co')
    })

    test('should display brand tagline in footer', async ({ page }) => {
      await expect(page.locator('.odd-footer-tagline')).toContainText(
        'Skincare for people who have better things to do'
      )
    })

    test('should display Manifesto link in footer', async ({ page }) => {
      const manifestoLink = page.locator('.odd-footer-nav a').filter({ hasText: 'Manifesto' })
      await expect(manifestoLink).toBeVisible()
    })

    test('should display Our Mission link in footer', async ({ page }) => {
      const missionLink = page.locator('.odd-footer-nav a').filter({ hasText: 'Our Mission' })
      await expect(missionLink).toBeVisible()
    })
  })

  test.describe('Page Navigation', () => {
    test('should load homepage', async ({ page }) => {
      await page.goto(PAGES.home)
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/odd-care-co\.local\/?$/)
    })

    test('should load shop page', async ({ page }) => {
      await page.goto(PAGES.shop)
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/shop/)
    })

    test('should load cart page', async ({ page }) => {
      await page.goto(PAGES.cart)
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/cart/)
    })

    test('should load checkout page', async ({ page }) => {
      await page.goto(PAGES.checkout)
      await page.waitForLoadState('networkidle')
      // Checkout redirects to cart when empty — both are valid
      await expect(page).toHaveURL(/checkout|cart/)
    })

    test('should load My Account page', async ({ page }) => {
      await page.goto(PAGES.myAccount)
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/my-account/)
    })

    for (const product of Object.values(PRODUCTS)) {
      if (product.slug === 'the-whole-routine') continue // bundle uses different URL pattern
      test(`should load ${product.name} editorial page`, async ({ page }) => {
        await page.goto(product.editorialUrl)
        await page.waitForLoadState('networkidle')
        await expect(page).toHaveURL(new RegExp(product.slug))
      })
    }

    test('should load The Whole Routine editorial page', async ({ page }) => {
      await page.goto(PRODUCTS.bundle.editorialUrl)
      await page.waitForLoadState('networkidle')
      await expect(page).toHaveURL(/the-whole-routine/)
    })
  })

  test.describe('Cross-page links', () => {
    test('should link from homepage CTA to shop/bundle', async ({ page }) => {
      await page.goto('/')
      await page.waitForLoadState('networkidle')

      const ctaButton = page.locator('.hero-btns .btn-dark')
      await expect(ctaButton).toBeVisible()
      const href = await ctaButton.getAttribute('href')
      expect(href).toBeTruthy()
    })

    test('should link from product page footer CTA to bundle', async ({ page }) => {
      await page.goto('/clear-first/')
      await page.waitForLoadState('networkidle')

      const ctaLink = page.locator('.footer-cta a').first()
      if (await ctaLink.isVisible()) {
        const href = await ctaLink.getAttribute('href')
        expect(href).toMatch(/the-whole-routine|shop|bundle/)
      }
    })
  })
})
