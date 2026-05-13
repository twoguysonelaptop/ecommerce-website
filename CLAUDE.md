# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

E-commerce website for **ODD Care Co**, a healthcare/personal care brand.

**Platform:** WooCommerce (WordPress) running on Local by Flywheel
**Products:** 4 SKUs (facewash, body wash, AM cream, PM cream)
**Site URL in database:** `http://odd-care-co.local`

## First-Time Setup (for new collaborators)

If the WordPress site is not running locally yet, follow these steps:

### Prerequisites
- Install [Local by Flywheel](https://localwp.com/)

### Quick Setup
1. Open Local by Flywheel → Create a new site (name: `odd-care-co`, PHP 8.x, MySQL 8.x, nginx)
2. Run the setup script — it copies themes, plugins, uploads, and attempts DB import:
   ```bash
   bash scripts/setup.sh "/c/Users/<YOUR_USERNAME>/Local Sites/odd-care-co"
   ```
3. Import the database manually if the script couldn't auto-import:
   - In Local, right-click the site → **Open Site Shell**
   - Run: `mysql -u root -proot local < wordpress/database/local.sql`
4. If your Local site domain differs from `odd-care-co.local`, update the URL in Adminer:
   ```sql
   UPDATE wp_options SET option_value = 'http://YOUR-SITE.local' WHERE option_name IN ('siteurl', 'home');
   ```
5. Install WooCommerce: WP Admin → Plugins → Add New → Search "WooCommerce" → Install & Activate
6. Activate theme: WP Admin → Appearance → Themes → **ODD Care Co Child**
7. Activate plugin: WP Admin → Plugins → **ODD Care Co Core**

### Database credentials (Local by Flywheel defaults)
- DB Name: `local` | User: `root` | Password: `root` | Host: `localhost`

## Repository Structure

```
├── CLAUDE.md                          # This file — project instructions
├── ecommerce.md                       # Full project brief (products, brand, requirements)
├── images/                            # Product label reference photos
├── product-pages/                     # HTML mockups & page designs
│   ├── homepage.html                  # Main homepage
│   ├── homepage-wireframe.html        # Homepage wireframe variant
│   ├── clear-first-facewash.html      # Product page — facewash
│   ├── dawn-shield-sunscreen.html     # Product page — AM cream/sunscreen
│   ├── deep-dusk-nightcream.html      # Product page — PM cream
│   ├── foam-rinse-bodywash.html       # Product page — body wash
│   ├── the-whole-routine-bundle.html  # Bundle page
│   ├── manifesto.html                 # Brand manifesto page
│   ├── our-mission.html               # Our Mission page
│   ├── about-section-mockup.html      # About section mockup
│   ├── reviews-mockup.html            # Reviews section mockup
│   ├── product-3d-models.html         # 3D product viewer
│   └── odd-mascot.js                  # 3D mascot animation script
├── 3d-mascot-pipeline/                # Python scripts for 3D mascot generation
├── tests/                             # Playwright E2E test suite
│   ├── e2e/                           # Test specs (auth, features, navigation)
│   ├── pages/                         # Page Object Models
│   ├── fixtures/                      # Test data & auth helpers
│   └── playwright.config.ts           # Playwright configuration
├── scripts/
│   └── setup.sh                       # Automated local setup script
├── wordpress/                         # WordPress site export
│   ├── database/
│   │   ├── local.sql                  # Full WordPress database dump
│   │   └── patches/                   # Individual SQL update scripts
│   │       ├── update_hero_svg.sql
│   │       └── update_hero_text.sql
│   ├── wp-config-sample.php           # Reference wp-config.php
│   └── wp-content/
│       ├── themes/
│       │   ├── kadence/               # Parent theme (Kadence)
│       │   └── oddcareco-child/       # Custom child theme
│       ├── plugins/
│       │   └── oddcareco-core/        # Custom plugin (analytics, wishlist, referrals)
│       └── uploads/                   # Media files (logos, labels, product images)
```

## Brand Rules (enforce in all copy and UI)

- **Tone:** Direct, honest, minimal — "like talking to a friend who asked 'what does this do?'"
- **No exaggerated claims.** Describe exactly what each product does, nothing more.
- **Tagline:** "Skincare for people who have better things to do"
- **Motto:** "ONEE. DOSE. DAILY."
- **Design must be category-neutral** — the client may pivot beyond skincare, so nothing should "scream" skincare.

## Design Direction

- Minimalist, clean, modern, understated
- **Color palette:** Black, White, Light Beige, Sage Green
- Let products and honest copy do the talking

## Working with the Codebase

### Theme customization
The active theme is `oddcareco-child` (child of Kadence). Edit files in:
- `wordpress/wp-content/themes/oddcareco-child/`
- After changes, copy updated files to your Local site's `wp-content/themes/oddcareco-child/`

### Custom plugin
`oddcareco-core` handles analytics, wishlist, and referral features. Files in:
- `wordpress/wp-content/plugins/oddcareco-core/`

### HTML mockups
Standalone page designs in `product-pages/` — these are reference/prototype HTML files, not live WordPress templates. Use them as the design source of truth when implementing pages in WordPress.

### Running tests
```bash
cd tests
npm install
npx playwright test
```

### Database patches
SQL scripts in `wordpress/database/patches/` contain individual database updates. Apply them via Local's Site Shell or Adminer when needed.
