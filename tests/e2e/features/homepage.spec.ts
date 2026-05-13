import { test, expect } from '@playwright/test'
import { HomePage } from '../../pages/HomePage'
import { PRODUCTS } from '../../fixtures/data'

test.describe('Homepage', () => {
  let homePage: HomePage

  test.beforeEach(async ({ page }) => {
    homePage = new HomePage(page)
    await homePage.goto()
  })

  test.describe('Hero Section', () => {
    test('should display hero section with brand messaging', async () => {
      await expect(homePage.heroSection).toBeVisible()
      await expect(homePage.heroTitle).toBeVisible()
    })

    test('should display CTA button linking to bundle', async () => {
      await expect(homePage.heroCtaButton).toBeVisible()
      await expect(homePage.heroCtaButton).toContainText('1,499')
    })

    test('should display background watermark "4"', async ({ page }) => {
      const heroBeforeContent = await page.evaluate(() => {
        const hero = document.querySelector('.hero')
        if (!hero) return ''
        const content = window.getComputedStyle(hero, '::before').getPropertyValue('content')
        // CSS content values are quoted — e.g. '"4"' or "'4'"
        return content.replace(/['"]/g, '')
      })
      expect(heroBeforeContent).toContain('4')
    })
  })

  test.describe('Product Grid', () => {
    test('should display exactly 4 product cards', async () => {
      await expect(homePage.productCards).toHaveCount(4)
    })

    test('should display correct product names', async () => {
      const names = await homePage.getProductCardNames()
      expect(names).toContain('Clear First')
      expect(names).toContain('Foam Rinse')
      expect(names).toContain('Dawn Shield')
      expect(names).toContain('Deep Dusk')
    })

    test('should display correct prices on all cards', async () => {
      const prices = await homePage.getProductCardPrices()
      expect(prices).toContain(PRODUCTS.clearFirst.price)
      expect(prices).toContain(PRODUCTS.foamRinse.price)
      expect(prices).toContain(PRODUCTS.dawnShield.price)
      expect(prices).toContain(PRODUCTS.deepDusk.price)
    })

    test('should display product codes on all cards', async ({ page }) => {
      const codes = await page.locator('.p-card-num').allTextContents()
      expect(codes).toContain(PRODUCTS.clearFirst.code)
      expect(codes).toContain(PRODUCTS.foamRinse.code)
      expect(codes).toContain(PRODUCTS.dawnShield.code)
      expect(codes).toContain(PRODUCTS.deepDusk.code)
    })

    test('should navigate to product page on card click', async ({ page }) => {
      await homePage.clickProduct('Clear First')
      await expect(page).toHaveURL(/clear-first/)
    })

    test('should display active ingredient tags on cards', async ({ page }) => {
      const tags = page.locator('.p-card .p-active-tag')
      const count = await tags.count()
      expect(count).toBeGreaterThan(0)
    })
  })

  test.describe('Bundle Row', () => {
    test('should display The Whole Routine bundle', async () => {
      await expect(homePage.bundleRow).toBeVisible()
    })

    test('should show bundle price and savings', async ({ page }) => {
      await expect(page.locator('.bundle-price')).toContainText('1,499')
      await expect(page.locator('.bundle-saving')).toContainText('save')
    })

    test('should navigate to bundle page on click', async ({ page }) => {
      await homePage.clickBundle()
      await expect(page).toHaveURL(/the-whole-routine|whole-routine/)
    })
  })

  test.describe('Why 4 Section', () => {
    test('should display "Why 4" section', async () => {
      await expect(homePage.why4Section).toBeVisible()
    })

    test('should list all 4 product benefits', async () => {
      await expect(homePage.why4Items).toHaveCount(4)
    })

    test('should mention each product name', async () => {
      const text = await homePage.why4Section.textContent()
      expect(text).toContain('Clear First')
      expect(text).toContain('Foam Rinse')
      expect(text).toContain('Dawn Shield')
      expect(text).toContain('Deep Dusk')
    })
  })

  test.describe('Routine Section', () => {
    test('should display AM and PM routines', async () => {
      await expect(homePage.routineSection).toBeVisible()
    })

    test('should show 3 AM steps and 2 PM steps', async () => {
      await expect(homePage.amRoutineSteps).toHaveCount(3)
      await expect(homePage.pmRoutineSteps).toHaveCount(2)
    })
  })

  test.describe('Honest Cards', () => {
    test('should display 4 honest cards', async () => {
      await expect(homePage.honestCards).toHaveCount(4)
    })

    test('should include key questions', async () => {
      const text = await homePage.honestSection.textContent()
      expect(text).toContain('Why only 4')
      expect(text).toContain('When do results show')
      expect(text).toContain('Will you add products')
      expect(text).toContain('sensitive skin')
    })
  })

  test.describe('FAQ Accordion', () => {
    test('should display FAQ items', async () => {
      const count = await homePage.faqItems.count()
      expect(count).toBeGreaterThanOrEqual(4)
    })

    test('should toggle FAQ answer on click', async () => {
      // All should be closed initially
      const isOpenBefore = await homePage.isFaqOpen(0)
      expect(isOpenBefore).toBe(false)

      // Open first FAQ
      await homePage.toggleFaq(0)
      const isOpenAfter = await homePage.isFaqOpen(0)
      expect(isOpenAfter).toBe(true)
    })

    test('should close other FAQs when opening one (accordion)', async () => {
      // Open first
      await homePage.toggleFaq(0)
      expect(await homePage.isFaqOpen(0)).toBe(true)

      // Open second — first should close
      await homePage.toggleFaq(1)
      expect(await homePage.isFaqOpen(0)).toBe(false)
      expect(await homePage.isFaqOpen(1)).toBe(true)
    })
  })

  test.describe('Reviews', () => {
    test('should display review cards', async () => {
      const count = await homePage.reviewCards.count()
      expect(count).toBeGreaterThanOrEqual(3)
    })
  })

  test.describe('Footer CTA', () => {
    test('should display footer CTA with bundle link', async () => {
      await expect(homePage.footerCta).toBeVisible()
      await expect(homePage.footerCtaButton).toContainText('1,499')
    })
  })
})
