# E-Commerce Website — Project Brief

## Project Overview

- **Platform:** WooCommerce (WordPress)
- **Type:** E-commerce website for healthcare/personal care products (facewash, creams, etc.)
- **Current Product Count:** 4
- **Client:** Requires a brand-flexible website that can adapt beyond skincare in the future

## Client Requirements

- User account registration and login page
- Database to store user information (accounts, orders, etc.)
- Product catalog with 4 initial products
- Shopping cart and checkout functionality
- WooCommerce-powered backend for product and order management

## Brand Philosophy

- **Tagline:** "Skincare for people who have better things to do"
- **Core Idea:** Products for people who don't know skincare — simple, no-nonsense, and approachable
- **Honesty-First Approach:** The client wants to build a brand around honesty. All product messaging should be straightforward and transparent — no exaggeration, no hype

## Product Description Guidelines

- Describe exactly what the product does — nothing more, nothing less
- No exaggerated claims (e.g., avoid "cures dark spots" or "removes acne overnight")
- If it's a face cream, say what the face cream actually does in plain language
- Tone: direct, honest, minimal — like talking to a friend who asked "what does this do?"

## Design Direction

- **Theme:** Minimalist
- **Important:** The website should NOT look like a typical skincare brand. The client may pivot products in the future, so the design must be category-neutral — it should not "scream" skincare
- **Color Palette:**
  - Black
  - White
  - Light Beige
  - Sage Green
- **Overall Feel:** Clean, modern, understated — the design should let the products and honest copy do the talking

## Brand Details

- **Brand Name:** ODD Care Co
- **Brand Motto:** "ONEE. DOSE. DAILY."
- **Brand Tagline:** "Skincare for people who have better things to do"

## Product Catalog

### 1. Clear First (ODD-01)
- **Type:** Facewash
- **Size:** 100 ml
- **Packaging:** Tube
- **Use:** AM or PM. Lather. Wash. Done.
- **Key Actives:** Niacinamide 2%, Salicylic acid 0.5%, Lactic acid 0.5%, AHA+BHA+PHA, Sodium Hyaluronate 0.1%, Rice Ferment Filtrate, Prebiotic ProVit B5, Orange Peel Exfoliator
- **Targets:** Cleansing, Microbiome Balancing, Exfoliating
- **Label:** ![Clear First Label](images/clear-first-facewash-label.jpeg)

### 2. Foam Rinse (ODD-02)
- **Type:** Foaming Body Wash
- **Size:** 250 ml
- **Packaging:** Taller pump bottle (foaming)
- **Use:** AM or PM. Wet skin. Foam. Rinse.
- **Key Actives:** Niacinamide 4%, Salicylic acid 0.5%, AHA+BHA+PHA, Humectants, ProVit B5
- **Targets:** Unclog pores, Reduce body acne, Controls excess oil, Refines skin texture, Hydrating non-drying, Leaves skin clean soft & refreshed
- **Label:** ![Foam Rinse Label](images/foam-rinse-body-wash-label.jpeg)

### 3. Dawn Shield (ODD-03)
- **Type:** AM Cream (All-in-one AM Routine — Sunscreen + Moisturizer with Actives)
- **Size:** 75 g
- **Packaging:** Narrow pump bottle
- **SPF:** 50+, PA ++++
- **Use:** Morning. Before sun exposure. Non-greasy.
- **Key Actives:** Niacinamide, Ceramide complex, Sodium Hyaluronate, UV Filter, PROVIMC B5
- **Targets:** UV protection, Hydration, Strengthens Skin Barrier, Brightens, Supports skin microbiome
- **Label:** ![Dawn Shield Label](images/dawn-shield-am-cream-label.jpeg)

### 4. Deep Dusk (ODD-04)
- **Type:** PM Cream (All-in-one PM Routine — Night Cream + Moisturizer with Actives)
- **Size:** 75 g
- **Packaging:** Narrow pump bottle
- **Use:** Night. Once. Wake up. Sleep. Levels.
- **Key Actives:** Marine Bio-Retinol from Microalgae, Niacinamide 2%, Shea Butter
- **Targets:** Anti Pigmentation, Strengthens Skin Barrier, Supports skin microbiome, Retains Moisture
- **Label:** ![Deep Dusk Label](images/deep-dusk-pm-cream-label.jpeg)

## Packaging Overview

![Packaging Overview](images/packaging-overview.jpeg)

Packaging types:
- **AM/PM Creams (Dawn Shield & Deep Dusk):** Narrow pump bottles
- **Facewash (Clear First):** Tube
- **Body Wash (Foam Rinse):** Taller pump bottle (foaming dispenser)

## Pricing

| Product | Individual Price |
|---------|-----------------|
| Clear First (Facewash) | ₹499 |
| Foam Rinse (Body Wash) | ₹449 |
| Dawn Shield (AM Cream) | ₹399 |
| Deep Dusk (PM Cream) | ₹549 |
| **The Whole Routine (Bundle)** | **₹1,499** (save ₹397) |

Individual total: ₹1,896 → Bundle: ₹1,499

## Product Description Pages — Status

All 5 product description pages have been created as static HTML files in `product-pages/`. These serve as content blueprints before WooCommerce integration.

### Format
- **Shorter tabbed format** — client-requested concise, to-the-point layout
- Each page is a self-contained HTML file with inline CSS and JavaScript
- Tabbed navigation for content sections (except the bundle page)
- Expandable ingredient cards with tap-to-reveal details
- Does/Doesn't grids, comparison rows, review cards, honest disclosure cards

### Pages

| File | Product | Theme | Tabs |
|------|---------|-------|------|
| `clear-first-facewash.html` | Clear First (Facewash) | Sage/Beige | wait / does / ingredients / reviews / honest |
| `foam-rinse-bodywash.html` | Foam Rinse (Body Wash) | Sage/Beige | wait / does / ingredients / reviews / honest |
| `dawn-shield-sunscreen.html` | Dawn Shield (AM Cream) | Warm Amber | excuses / does / ingredients / reviews / honest |
| `deep-dusk-nightcream.html` | Deep Dusk (PM Cream) | Dark/Black | retinol story / why you need this / does / ingredients / reviews / honest |
| `the-whole-routine-bundle.html` | The Whole Routine (Bundle) | Purple Accent | No tabs — member grid, timeline, does/doesn't, reviews |

### Product-Specific Design Notes
- **Facewash:** Sage green accents, comparison rows vs standard facewash, 7 expandable ingredients
- **Body Wash:** Sage green accents, body zone map, comparison rows, 5 expandable ingredients
- **Sunscreen:** Warm amber palette (#bfa98b, #f7f2eb), excuse accordion with verdicts, SPF visual explainer, India-specific UV dark strip
- **Night Cream:** Full dark theme with algae-green accents, bio-retinol story section, GenZ sleep science strip, weekly timeline, 3 expandable ingredients
- **Bundle:** Purple accent (#534AB7, #EEEDFE), 2×2 click-to-select member grid, timed daily routine timeline, no tabs

### Key Content Decisions
- All ingredients are the **actual product ingredients** from this brief (not from reference templates)
- Bundle named **"The Whole Routine"** (client-approved)
- All pages cross-reference the bundle: "or get all 4 in The Whole Routine — ₹1,499"
- Products cross-reference each other where relevant (e.g., night cream mentions sunscreen dependency)
- Brand voice maintained across all pages: direct, honest, Gen Z-aligned, no hype

## Next Steps

- [x] Homepage design
- [x] Static product pages (HTML blueprints)
- [x] Push homepage to WordPress
- [x] Push all 5 product pages to WordPress
- [x] Kadence theme + child theme installed and configured
- [x] Manifesto page — static blueprint + WordPress page template created
- [ ] Create "Why 4" WordPress page (Admin → Pages → Add New → Template: Manifesto → Slug: why-4)
- [ ] Our Mission page — static blueprint exists, needs WordPress page creation (slug: our-mission)
- [x] Add Manifesto + Our Mission buttons to homepage footer
- [x] Header icons (cart + account) added to Kadence header
- [x] My Account page — styled, branded, WooCommerce registration enabled
- [x] WooCommerce product setup and integration
- [x] Custom plugin (oddcareco-core) — wishlists, referral codes, analytics dashboard
- [ ] Plugin installation (Razorpay payments, SEO, etc.)
- [ ] Shipping configuration
- [ ] Testing and launch

## WordPress Integration — Status

**Local environment:** Local by Flywheel — `http://odd-care-co.local/`
**Theme:** Kadence (active)
**Auth method:** WordPress REST API with `X-WP-Nonce` from `wpApiSettings.nonce`

### Live Pages

| Page | WordPress URL | Page ID |
|------|--------------|---------|
| Homepage | `http://odd-care-co.local/` | 26 |
| Clear First (Facewash) | `http://odd-care-co.local/clear-first/` | 31 |
| Foam Rinse (Body Wash) | `http://odd-care-co.local/foam-rinse/` | 33 |
| Dawn Shield (AM Cream) | `http://odd-care-co.local/dawn-shield/` | 35 |
| Deep Dusk (PM Cream) | `http://odd-care-co.local/deep-dusk/` | 37 |
| The Whole Routine (Bundle) | `http://odd-care-co.local/the-whole-routine/` | 39 |
| My Account | `http://odd-care-co.local/my-account/` | 9 |

All pages are published with Kadence full-width layout and no page title (meta: `_kad_post_title: hide`, `_kad_post_layout: fullwidth`, `_kad_post_vertical_padding: remove`).

Homepage is set as the static front page in WordPress Settings → Reading.

### How Pages Were Pushed

All pages are pushed as raw HTML via the WordPress REST API (`POST /wp-json/wp/v2/pages`). Key technical details:

- **Gutenberg block wrapper**: Content is wrapped in `<!-- wp:html -->...<!-- /wp:html -->` to bypass WordPress's `wpautop` filter (which would otherwise insert `<p>` tags inside `<style>` blocks and break CSS selectors).
- **CSS variables**: `:root` CSS variable declarations are stripped before pushing. All `var(--xxx)` references are replaced with hard-coded hex values because variables defined inside a page's `<style>` tag don't propagate globally in Chrome. The full replacement map is in `window._createProductPage` (see Technical Notes below).
- **Kadence padding**: A `<style>.entry-content-wrap{padding-top:0!important;padding-bottom:0!important}</style>` snippet is appended to every page to remove Kadence's default content padding.
- **Homepage product card links**: All point to `/clear-first/`, `/foam-rinse/`, `/dawn-shield/`, `/deep-dusk/`, `/the-whole-routine/` (not `/product/...` WooCommerce-style URLs).

### CSS Variable Replacement Map

Used when pushing any page to WordPress (to avoid `:root` propagation issues):

```
--odd-black     → #000
--odd-white     → #fff
--odd-sage      → #9CAF88
--odd-beige     → #F5F0EB
--odd-dark      → #1a1a1a
--odd-gray      → #555
--odd-border    → #e5e5e5
--text-primary  → #1a1a1a
--text-secondary→ #555
--text-tertiary → #888
--bg-secondary  → #fafaf9
--border-light  → #e5e5e5
--warm          → #bfa98b      (Dawn Shield)
--warm-light    → #f7f2eb
--warm-border   → #e0d4c0
--warm-text     → #6b5540
--warm-dark     → #4a3828
--night         → #0d0d0d      (Deep Dusk)
--night-card    → #1a1a1a
--night-border  → #2a2a2a
--night-text    → #666
--night-heading → #e0e0e0
--algae         → #2d5a3a
--algae-light   → #4a8a5a
--algae-bg      → #0d1a10
--algae-border  → #1a3320
--purple        → #534AB7      (Bundle)
--purple-light  → #EEEDFE
--purple-border → #AFA9EC
--purple-dark   → #3C3489
--purple-text   → #7F77DD
```

## Manifesto Page — Status

**Session date:** 2026-04-13

The "Why 4" manifesto page explains the brand's core philosophy — why only 4 products, what was eliminated and why, and who the products are for.

### Source
Client provided reference at `odd_care_why4_manifesto.html` — plain content without design formatting.

### Files Created

| File | Purpose |
|------|---------|
| `product-pages/manifesto.html` | Static HTML blueprint (standalone, for preview/reference) |
| `wp-content/themes/oddcareco-child/page-manifesto.php` | WordPress blank-canvas page template |

### WordPress Page Template: "Manifesto"
- **Template name:** Manifesto (selectable in Page Attributes sidebar)
- **Blank canvas** — bypasses Kadence header/footer entirely; uses its own nav
- Still calls `wp_head()` / `wp_footer()` so SEO plugins and analytics work
- Nav links are dynamic WordPress URLs (`wc_get_page_permalink('shop')`, `home_url()`)
- **To activate:** Admin → Pages → Add New → Title: `Why 4` → Template: `Manifesto` → Slug: `why-4` → Publish

### Design Features
- Sticky scroll-progress bar (sage green, 2px)
- Sticky chapter strip with 6 tabs (intro / the problem / what we cut / the hard part / who it's for / the deal) — updates active state on scroll, clickable to jump
- Fade-in animations on all sections (IntersectionObserver)
- **Strike list:** 6 eliminated products strikethrough sequentially (180ms apart) on scroll into view
- **Counter animation:** "17" counts up 0→17 when the formulation stat block enters view
- **"Who it's for" list:** 4 paragraphs slide in with 130ms stagger
- Full-bleed beige pull quote section breaks the column rhythm
- Admin bar compatible (no z-index or fixed positioning conflicts)

### Content Structure (5 parts)
1. **Intro** — "The skincare industry has a vested interest in your confusion."
2. **Part One** — Nobody actually needs 12 products (with industry stat callout)
3. **Part Two** — Products eliminated + interactive strikethrough list → kept list
4. **Part Three** — Multifunctional formulation is harder (17 iterations counter)
5. **Part Four** — Who it's for + product colour row
6. **Ending** — Why we'll never make a 5th product + dark callout
7. **CTA** — "Get the group project — ₹1,499" + "See all 4 products"

---

## Session Log — 2026-04-13

### What was done
1. **Manifesto page built** — static blueprint (`product-pages/manifesto.html`) and WordPress blank-canvas template (`page-manifesto.php` in child theme). Content sourced from client reference file `odd_care_why4_manifesto.html`. All scroll animations, chapter strip, counter, and stagger effects implemented.

2. **Homepage footer buttons attempted** — added `.footer-links` CSS and two ghost-style buttons ("Manifesto" → `/why-4`, "Our Mission" → `/our-mission`) to the live WordPress homepage (page ID 26) via a one-time PHP update script. Changes were **rolled back** after the page broke — to be reattempted in next session with a safer approach.

3. **Child theme confirmed** — `oddcareco-child` is active on `odd-care-co.local`. Kadence parent theme installed. Child theme has `functions.php` (nav dropdown enhancement + product carousel) and `style.css` (brand variable overrides).

### Pending from this session
- Create the "Why 4" WordPress page and assign the Manifesto template
- Investigate why the homepage footer buttons broke the page before re-adding them
- Our Mission page (`product-pages/our-mission.html` exists) needs its own WordPress page and template

---

## Reference Materials

_Client-provided rough design references were used to build the product description pages. The shorter tabbed format was the final client-approved direction._

---

## Session Log — 2026-04-13 (Homepage Polish & Bug Fixes)

All changes applied to live Local by Flywheel WordPress site. Files modified: `oddcareco-child/style.css`, `oddcareco-child/functions.php`, WordPress page ID 26 (homepage content via block editor API).

### Changes Made

1. **Header: removed brand name text** — "ODD Care Co." text next to logo hidden via `.site-title-wrap { display: none }` in child theme CSS. Logo-only.

2. **Ticker bar: fixed contrast** — Non-highlighted items (no serum, no toner, etc.) were `#3a3a3a` on `#111111` background — near invisible. Changed to `#888888`. Product items remain sage green.

3. **Hero copy update** — "We're not making more." → "For skincare. That's the full lineup." Scopes the claim to skincare only, leaves room for future product categories.

4. **Products dropdown nav** — Consolidated individual product header links into a "PRODUCTS" dropdown. Created WordPress nav menu programmatically via PHP. Dropdown contains: Clear First, Foam Rinse, Dawn Shield, Deep Dusk, The Whole Routine — Bundle.

5. **Premium dropdown design** — Dark luxury panel (`#0d0d0d`, sage top border). JS in `wp_footer` adds rich structure per item: product code (ODD-01 in sage), name (white), type + price (muted gray). Bundle item has sage left border + greenish tint. Slide-in animation, hover highlights.

6. **Product section → auto-scroll carousel** — 2-column grid converted to full-bleed infinite auto-scrolling carousel (same style as header ticker). Cards are 300px wide, loop seamlessly via JS-cloned duplicates, animate at 36s/loop. Hovering pauses scroll.

7. **Carousel/bundle spacing** — Added `margin-bottom: 3.5rem` to carousel wrap and `margin-top: 0.5rem` to `.bundle-row` to prevent them collapsing together.

8. **Footer: Manifesto + Our Mission links** — Custom `.odd-footer` injected via `kadence_before_footer` hook. Dark `#0d0d0d` panel, brand name + tagline left, nav links right (sage hover). Resolves the previously failed footer button attempt.

9. **Fixed `overflow: clip` on `.wp-site-blocks`** — WordPress block wrapper was clipping full-bleed elements. Overridden to `overflow: visible` in child theme CSS.

### Bugs Fixed

10. **Homepage broken — raw CSS rendered as text** — `<style>` and `</style>` tags were stripped from the page's Custom HTML block during a content edit. Detected via `mainContent` showing raw CSS. Fixed by re-wrapping the CSS block (boundary at first `<div>` ~char 12,783) via the WP block editor API.

11. **Hero "4" overlapping all text** — `.hero::before` (position: absolute, z-index: 0) was painting over static children after `.hero > * { position: relative; z-index: 1 }` was lost in the style strip. Re-added as permanent override in child theme CSS.

### Files Changed

| File | What changed |
|---|---|
| `oddcareco-child/style.css` | Ticker, dropdown, carousel, footer, hero z-index, overflow fix |
| `oddcareco-child/functions.php` | Dropdown JS enhancer, carousel builder JS, footer PHP hook |
| WordPress page ID 26 | Hero copy, product grid CSS, `<style>` tag restoration |

---

## Session Log — 2026-04-14 (Product Page Formatting & Bundle Redesign)

All changes applied to live Local by Flywheel WordPress site via temporary PHP updater script (`odd-update-page.php`, deleted after each push). Source HTML blueprints live in `product-pages/`.

### Changes Made

1. **Footer CTA transition fix (all pages)** — The white-to-beige footer CTA section had an abrupt edge. Added `border-radius: 32px 32px 0 0` and `box-shadow: 0 -2px 24px rgba(0,0,0,0.04)` to `.footer-cta` for a smooth rounded transition. Added `margin-top: 3rem` for spacing between content and footer CTA.

2. **Header formatting standardized (all pages)** — Applied Clear First's header pattern to Foam Rinse, Dawn Shield, Deep Dusk, and The Whole Routine:
   - `body { background: #fff }` — white header area
   - `header.site-header .custom-logo { max-height: 80px }` — compact logo
   - `#inner-wrap { background: #F5F0EB; border-radius: 32px 32px 0 0; box-shadow: ... }` — rounded beige content area
   - Breadcrumb moved inside `.hero-band`
   - `.honest-strip` changed from bordered box to top-border separator

3. **Deep Dusk green→black theme** — All algae-themed CSS (`.callout-algae`, `.ing-detail`, `.badge-hero`) remapped from green (`#2d5a3a`) to dark/night palette (`#1a1a1a`, `#2a2a2a`, `#333`) to match the page's night theme.

4. **The Whole Routine — blue→sage green** — Bundle page color theme changed from purple/blue to sage green. `--purple` variable values remapped: `#534AB7→#6B7F5A`, `#EEEDFE→#F0F4ED`, `#AFA9EC→#B8C8AD`, `#3C3489→#5A6B4A`, `#7F77DD→#7A9168`. Variable names kept as `--purple` to avoid renaming every CSS reference.

5. **The Whole Routine — hero section cleanup** — Removed the white `.box-hero` card (border, border-radius, background) so content fills the beige `.hero-band` directly without a nested card appearance.

### Pages Updated

| Page | ID | Theme |
|------|----|-------|
| Clear First | 31 | Beige (reference page) |
| Foam Rinse | 33 | Beige |
| Dawn Shield | 35 | Warm |
| Deep Dusk | 37 | Dark/Night |
| The Whole Routine | 39 | Sage Green (was purple/blue) |

### CSS Variable Map Update

The bundle page now uses sage green values for `--purple` variables:

```
--purple        → #6B7F5A  (was #534AB7)
--purple-light  → #F0F4ED  (was #EEEDFE)
--purple-border → #B8C8AD  (was #AFA9EC)
--purple-dark   → #5A6B4A  (was #3C3489)
--purple-text   → #7A9168  (was #7F77DD)
```

---

## Session Log — 2026-04-14 (Header Icons, My Account Page & Bug Fixes)

All changes applied to live Local by Flywheel WordPress site. Files modified: `oddcareco-child/style.css`, `oddcareco-child/functions.php`, WordPress page ID 26 (homepage), WordPress page ID 9 (My Account).

### Changes Made

1. **Header icons (cart + account)** — Removed "Cart" and "Checkout" text links from the primary navigation menu. Added SVG account (person) and cart (shopping bag) icons to the Kadence header via the HTML widget slot. Icons are placed in `main_right` alongside the nav. Implemented as a one-time `set_theme_mod()` setup in `functions.php` with `odd_header_icons_v2` flag to prevent re-execution.

2. **My Account page — full brand styling** — Styled the WooCommerce My Account page (page ID 9) to match the ODD Care Co brand:
   - Single-column layout (overriding Kadence's default sidebar layout)
   - Horizontal nav tabs on beige background with sage-green active pill
   - Custom dashboard greeting: "YOUR ACCOUNT" eyebrow + "Hey, {name}." + subtitle
   - Hidden default WooCommerce greeting paragraphs and Kadence avatar
   - Removed "Downloads" nav item (physical products only)
   - Styled forms, inputs, order tables, address cards, and info/notice boxes
   - Login/register form with split layout, sage-green submit buttons
   - Responsive breakpoint at 768px
   - Page meta: fullwidth layout, hidden title, removed vertical padding
   - Enabled WooCommerce registration on My Account page (`woocommerce_enable_myaccount_registration = yes`)

3. **Homepage script fix** — Raw `faqToggle` JavaScript function was rendering as visible text above the footer. The `<script>` tags had been stripped during a previous content update. Fixed by re-wrapping the bare JS in `<script>` tags via REST API update.

4. **Footer color transition fix** — White background was bleeding between the dark "The whole group" CTA section and the dark footer. Fixed with:
   - `.home .entry.content-bg { background: transparent !important }` — removed white article wrapper background
   - `.footer-cta { padding-bottom: 0; margin-bottom: 0 }` — eliminated spacing gap
   - `.odd-footer { margin-top: 0; border-top: none }` — seamless transition
   - `.site-footer`, `.site-bottom-footer-wrap` — forced `#0d0d0d` background on Kadence copyright footer

### Files Changed

| File | What changed |
|---|---|
| `oddcareco-child/functions.php` | Header icon setup (`set_theme_mod`), removed Downloads nav, custom dashboard greeting, hidden default WooCommerce greeting |
| `oddcareco-child/style.css` | Full My Account page styling (~200 lines), footer transition fixes, Kadence copyright footer dark theme |
| WordPress page ID 26 | Re-wrapped bare `faqToggle` JS in `<script>` tags |
| WordPress page ID 9 | Set fullwidth layout, hidden title, removed padding meta |
| `product-pages/homepage.html` | Added sticky header with account/cart SVG icons |

### Technical Notes

- **Kadence Header Builder**: The Customizer JS API (`wp.customize().set()`) was unreliable for persisting header layout changes. PHP `set_theme_mod()` in `functions.php` with a one-time flag is the reliable approach.
- **Kadence Cart widget**: Collapses to 0×0px when WooCommerce has no products (`header-cart-is-empty-true` class). Both account and cart icons are placed in the HTML widget instead.
- **WordPress script stripping**: `<script>` tags inside `<!-- wp:html -->` blocks can be stripped during content updates. Always verify scripts are intact after REST API pushes.

---

## Session Log — 2026-04-15 (Footer Transition & Dark Page Contrast Fixes)

All changes applied to live Local by Flywheel WordPress site. File modified: `oddcareco-child/style.css`.

### Changes Made

1. **Homepage footer transition fix** — The transition between the footer CTA section, the ODD footer, and the Kadence copyright footer was rough with visible seams and color mismatches.
   - Unified `.footer-cta` background from `#1a1a1a` to `#0d0d0d` to match the footer sections
   - Added `padding-bottom: 4rem` to `.footer-cta` (was `0`) for breathing room before the brand footer
   - Removed side padding on `.home .entry-content-wrap` to fix beige bleed on edges
   - Replaced hard border dividers with subtle `rgba(255,255,255,0.05)` separators

2. **Homepage footer CTA text contrast** — Text in the dark footer CTA section was nearly invisible. Brightened all text colors:
   - `.footer-eyebrow`: `#444` → `#888`
   - `.footer-sub`: `#555` → `#aaa`
   - `.footer-fine`: `#333` → `#666`
   - `.btn-ghost-dark`: `#888/#333` → `#bbb/#555`

3. **ODD footer + Kadence footer text contrast** — Footer nav and copyright text were too dark:
   - `.odd-footer-tagline`: `#444` → `#888`
   - `.odd-footer-nav a`: `#555` → `#999`
   - `.site-footer .footer-html`: `#333` → `#666`
   - `.site-footer .footer-html a`: `#444` → `#777`

4. **Deep Dusk page (page ID 37) — full contrast overhaul** — The entire dark-themed page (`#0d0d0d` background) had text that was nearly unreadable. Added ~40 scoped `.page-id-37` overrides in child theme CSS:
   - Hero: labels, breadcrumb, stars, rating, price note, active tags all brightened
   - Tabs: inactive `#444` → `#777`, hover → `#aaa`, active → `#ddd`
   - Card/body text: `#666` → `#999`, callout spans `#888` → `#bbb`
   - GenZ strip: labels `#444` → `#777`, text `#888` → `#aaa`
   - Does/Doesn't grid: items and dots brightened for readability
   - Timeline: circle text, week labels, descriptions all brightened
   - Ingredients: intro, tldr, detail text, concentration all brightened
   - Reviews: tags, text, badges all brightened
   - Footer CTA: label and subtitle text brightened
   - Catch-all attribute selectors for inline `style="color: #333/444/555"` elements

5. **Tab scrollbar hidden (all product pages)** — The `.tabs` horizontal scrollbar was visible on product pages. Hidden via `scrollbar-width: none` (Firefox), `-ms-overflow-style: none` (IE/Edge), and `::-webkit-scrollbar { display: none }` (Chrome/Safari). Scroll functionality preserved.

### Files Changed

| File | What changed |
|---|---|
| `oddcareco-child/style.css` | Footer transition fix, footer CTA/footer text contrast, Deep Dusk full contrast overhaul (~40 rules), tab scrollbar hidden |

### Technical Notes

- **Inline style overrides**: Deep Dusk page content was pushed via REST API with hardcoded inline color values. Child theme CSS uses `.page-id-37` scoping with `!important` to override. Attribute selectors (`[style*="color: #333"]`) catch remaining inline styles.
- **Tab scrollbar**: The `.tabs` element uses `overflow-x: auto` for mobile scrolling. Scrollbar is hidden purely cosmetically — scroll still works via touch/trackpad.

---

## Session Log — 2026-04-16 (White Pillars, Hero "4" Visibility, Bundle Row Redesign)

All changes applied to live Local by Flywheel WordPress site. Files modified: `oddcareco-child/style.css`, `product-pages/homepage.html`, WordPress page ID 26 (homepage) via REST API.

### Changes Made

1. **Removed white pillars on all pages** — Thin white strips were visible on both sides of every page, caused by Kadence's article wrapper (`.entry.content-bg`) having a white background with a box-shadow, and `.entry-content-wrap` having 32px side padding, sitting inside a rounded `#inner-wrap`. Fixes in child theme CSS:
   - `body { background: #F5F0EB !important }` — beige body so no white shows through
   - `body.page-id-37 { background: #0d0d0d !important }` — Deep Dusk dark override
   - `header.site-header { background: #fff }` — keep header clean white
   - `#inner-wrap { border-radius: 0 !important; box-shadow: none !important }` — flatten the rounded-corner wrapper
   - `.entry.content-bg { background: transparent !important; box-shadow: none !important }` (promoted from homepage-only to global)
   - `.entry-content-wrap { padding-left: 0 !important; padding-right: 0 !important }` (promoted from homepage-only to global)

2. **Hero background "4" now visible** — The large watermark "4" behind the hero text was invisible because its color (`#F7F4EF`) was almost identical to the beige body background (`#F5F0EB`). Darkened via `.hero::before { color: #E2D9CF !important }` in child theme CSS — subtle but clearly readable.

3. **"The Whole Routine" bundle block redesigned** — The sage-green bordered box below the product carousel looked awkward because sage is an accent color in the ODD palette, never a surface color. Converted from a boxed card into a borderless "summary row":
   - Removed `background: #eef3ec` and `border: 1px solid #c8d9c4`
   - Added `border-top: 1px solid #e5dfd6` — thin divider line, same as used elsewhere
   - Badge switched from sage-bg to `#1a1a1a` dark-bg (matches CTA button pattern)
   - Name/price now `#1a1a1a`, tagline muted gray `#9e9e9e`
   - Sage (`#7a9e7e`) retained only on the "save ₹397" savings text
   - Added `<span class="bundle-arrow">→</span>` with hover translate for click affordance
   - Hover: opacity 0.7 fade (replaced the box-shadow pop)
   - Shortened tagline: "Complete AM + PM + body routine. One box."
   - Fixed stale link: `/product/the-whole-routine` → `/the-whole-routine/`
   - Both `product-pages/homepage.html` blueprint and WordPress page ID 26 updated

### Files Changed

| File | What changed |
|---|---|
| `oddcareco-child/style.css` | Body beige + Deep Dusk dark override, header explicit white, inner-wrap border-radius/shadow removed, entry.content-bg + entry-content-wrap promoted to global, hero::before color fix, bundle-row margin updated |
| `product-pages/homepage.html` | Bundle row CSS rewritten (borderless summary row), HTML updated with arrow + shortened tagline + corrected href |
| WordPress page ID 26 | Bundle CSS block + bundle HTML replaced via REST API (`wp.apiFetch` from admin context) |

### Technical Notes

- **Body background priority**: Page inline CSS (`body { background: #fff }`) was overriding the child theme. Used `!important` in child theme to force the beige, then scoped `body.page-id-37` override for the dark Deep Dusk page.
- **Global-only-needed-everywhere promotions**: `.entry.content-bg { background: transparent }` and `.entry-content-wrap { padding: 0 }` were originally scoped to `.home` only. Promoted to global since every page was affected by the same white-pillar issue.
- **WordPress REST API push from admin**: The frontend doesn't load `wpApiSettings` or `wp.apiFetch`. To push page updates via the browser, navigate to `/wp-admin/post.php?post={id}&action=edit` first — that loads the WP admin JS context where `wp.apiFetch()` works with automatic nonce handling.

---

## Session Log — 2026-04-17: Homepage Animations, Product Grid, Bundle Redesign & FAQ

All changes applied to live Local by Flywheel WordPress site. Files modified: `oddcareco-child/style.css`, `oddcareco-child/functions.php`.

### Changes Made

1. **Hero section text centered** — Main hero text was left-aligned instead of centered. Added `text-align: center !important` to `.hero` in child theme CSS.

2. **Product grid changed to 2x2 layout** — Switched from side-by-side flex row to a CSS Grid 2x2 layout (`grid-template-columns: 1fr 1fr`) to future-proof for product images. Single column on mobile via `@media (max-width: 768px)`.

3. **"Drop & Settle" entrance animation for products** — Added physics-style bounce animation triggered by scroll via IntersectionObserver. Products start invisible 100px above, drop in with overshoot/bounce using `@keyframes dropSettle`. Each card staggers by 0.12s delay (0s, 0.12s, 0.24s, 0.36s). Uses `animation-fill-mode: forwards` to persist final state.

4. **Removed spotlight dimming on hover** — Previously hovering one product card dimmed the others. Removed those CSS rules entirely per user preference.

5. **"The Whole Routine" bundle redesigned as star product** — Transformed the flat horizontal strip into a prominent dark premium card:
   - Dark background (#1a1a1a) with sage green left border accent
   - Rounded corners (20px) matching product cards
   - Badge inverted to sage green background with dark text
   - Larger typography for name (20px) and price (22px) in off-white
   - Hover: lift effect + sage glow shadow + arrow nudge
   - Drop animation as 5th element (0.48s delay) using general sibling combinator (`~`) to trigger from grid's `.revealed` class

6. **"Things we'll just say directly" contrast fix** — Section was beige-on-beige (card bg `#f5f0e8` vs page bg `#F5F0EB`). Changed `.honest-card` to white background with subtle border and shadow.

7. **"The Questions" FAQ section redesign** — Fixed multiple issues:
   - **Contrast**: Changed from one large beige card to individual white cards per question with borders, rounded corners (14px), and spacing
   - **Accordion functionality**: WordPress entity-encoded `>` as `&gt;` in inline `<script>`, breaking the JS. Moved FAQ toggle logic to `functions.php` `wp_footer` action instead. Accordion opens one question at a time, closing others.
   - **Answer font**: Applied clean system font stack (`-apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI'`) at 14px, 1.8 line-height, with antialiasing for professional appearance.

### Files Changed

| File | What changed |
|---|---|
| `oddcareco-child/style.css` | Hero centering, 2x2 product grid, `@keyframes dropSettle` animation, staggered delays, bundle dark card redesign, honest-card contrast fix, FAQ individual cards + font styling, mobile responsive grid |
| `oddcareco-child/functions.php` | Added FAQ accordion toggle JS (click handler with open/close class toggling) inside homepage `wp_footer` action |

### Technical Notes

- **IntersectionObserver for scroll-reveal**: Observes `.product-grid` at 15% threshold. Adds `.revealed` class which triggers CSS animations on child `.p-card` elements and sibling `.bundle-row`.
- **General sibling combinator (`~`)**: `.product-grid.revealed ~ .bundle-row` triggers bundle animation when the grid above it is revealed — they're siblings, not parent-child.
- **WordPress entity encoding workaround**: Inline `<script>` blocks in WordPress page content get entity-encoded (`>` → `&gt;`), breaking JS. Solution: inject JS via child theme `functions.php` `wp_footer` action hook instead.
- **CSS specificity with `!important`**: Required throughout because WordPress page content uses inline styles that override child theme CSS. All overrides scoped to `.page-id-26` for homepage only.

---

## Session Log — 2026-04-17: Remove All Hyphens from Website Text Content

All changes applied to static HTML blueprints and pushed to live Local by Flywheel WordPress site via a one-time PHP updater script (`odd-update-pages.php`, deleted after use).

### Changes Made

1. **Removed all hyphen characters (`-`) from visible text content across the entire website** — hyphens in text were replaced with spaces for readability. CSS, JavaScript, HTML attributes, and URLs were left untouched. A Python script was used to parse each HTML file, skip `<style>` and `<script>` blocks, skip inside HTML tags, and replace `-` with a space only in text nodes between tags.

2. **Product codes updated** — `ODD-01` → `ODD 01`, `ODD-02` → `ODD 02`, `ODD-03` → `ODD 03`, `ODD-04` → `ODD 04` across all pages and the nav dropdown in `functions.php`.

3. **Ingredient/science terms updated** — `bio-retinol` → `bio retinol`, `triple-acid` → `triple acid`, `retinol-equivalent` → `retinol equivalent`, `oil-soluble` → `oil soluble`, etc.

4. **Compound words updated** — `non-greasy` → `non greasy`, `non-comedogenic` → `non comedogenic`, `non-negotiable` → `non negotiable`, `rinse-off` → `rinse off`, `leave-on` → `leave on`, `daily-use` → `daily use`, `twice-daily` → `twice daily`, `post-acne` → `post acne`, `anti-aging` → `anti aging`, `fragrance-free` → `fragrance free`, `fragrance-forward` → `fragrance forward`, `pea-sized` → `pea sized`, `single-ingredient` → `single ingredient`, `finger-lengths` → `finger lengths`, `serum-level` → `serum level`, `well-compensated` → `well compensated`, `follow-up` → `follow up`, `pop-up` → `pop up`, `anxiety-inducing` → `anxiety inducing`, `skincare-obsessed` → `skincare obsessed`, `micro-tears` → `micro tears`, `micro-granules` → `micro granules`, `low-molecular-weight` → `low molecular weight`, `pre-foamed` → `pre foamed`, etc.

### Files Changed

| File | Lines changed |
|---|---|
| `product-pages/homepage.html` | 11 |
| `product-pages/clear-first-facewash.html` | 17 |
| `product-pages/foam-rinse-bodywash.html` | 16 |
| `product-pages/dawn-shield-sunscreen.html` | 14 |
| `product-pages/deep-dusk-nightcream.html` | 22 |
| `product-pages/the-whole-routine-bundle.html` | 8 |
| `product-pages/manifesto.html` | 7 |
| `product-pages/our-mission.html` | 4 |
| `oddcareco-child/functions.php` | Product codes in nav dropdown JS object |

### WordPress Pages Updated

| Page | ID | Status |
|------|----|--------|
| Homepage | 26 | Updated |
| Clear First (Facewash) | 31 | Updated |
| Foam Rinse (Body Wash) | 33 | Updated |
| Dawn Shield (AM Cream) | 35 | Updated |
| Deep Dusk (PM Cream) | 37 | Updated |
| The Whole Routine (Bundle) | 39 | Updated |

### Technical Notes

- **Text-only replacement**: A Python script parsed each HTML file character-by-character, tracking `<style>` and `<script>` blocks to skip. Only text nodes (content between `>` and `<` outside style/script) had hyphens replaced. This preserved all CSS class names, property names, hex color values, JavaScript code, HTML attributes, and URLs.
- **Em dashes (`—`) and en dashes (`–`) were NOT affected** — these are different Unicode characters (U+2014 and U+2013) from the ASCII hyphen (U+002D). CTA buttons, breadcrumb separators, and number ranges using em/en dashes remain unchanged.
- **WordPress push process**: Content was pushed via a temporary PHP script using `wp_update_post()`. CSS `:root` variable declarations were stripped and `var(--xxx)` references replaced with hard-coded hex values (same process as original page pushes). Content wrapped in `<!-- wp:html -->` blocks.

---

## Session Log — 2026-04-20: Database, Custom Plugin & WooCommerce Products

### Changes Made

1. **Custom plugin created: `oddcareco-core`** — 17-file WordPress plugin at `wp-content/plugins/oddcareco-core/`. Activated on live site. Creates 6 custom database tables via `dbDelta()` on activation.

2. **6 custom database tables created:**

| Table | Purpose |
|-------|---------|
| `wp_oddcareco_wishlists` | User wishlist definitions (title, share key, public flag) |
| `wp_oddcareco_wishlist_items` | Products saved to wishlists (unique per wishlist+product) |
| `wp_oddcareco_referrals` | Referral tracking: referrer → referee → reward chain |
| `wp_oddcareco_referral_stats` | Pre-aggregated referral stats per user |
| `wp_oddcareco_analytics_events` | Raw event log (page views, add-to-cart, purchases, etc.) |
| `wp_oddcareco_analytics_daily` | Daily aggregated stats for fast admin dashboard queries |

3. **Wishlist feature built:**
   - Heart toggle button on product pages (AJAX, logged-in users only)
   - "Wishlist" tab in My Account (added at priority 20, after theme's filter)
   - Share-by-link functionality via 32-char random key
   - Admin page: Wishlist Analytics (total wishlists, total items, most-wishlisted products)

4. **Referral code system built:**
   - Code format: `ODD-XXXXXX` (6 uppercase alphanumeric, no ambiguous chars)
   - Auto-generated on account creation or first login, stored in `wp_usermeta`
   - Cookie capture on `?ref=ODD-XXXXXX` URL visits (30-day cookie)
   - On referee's first purchase: referrer gets a WooCommerce reward coupon
   - "Your Referral Code" section on My Account dashboard
   - Admin page: Referral Program (stats, top referrers, recent referrals, configurable settings)
   - Settings: reward amount, reward type, cookie days, min spend, expiry — all configurable in admin

5. **Analytics tracking system built:**
   - Server-side hooks: `woocommerce_add_to_cart`, `woocommerce_remove_cart_item`, `woocommerce_checkout_order_processed`, `woocommerce_payment_complete`, `woocommerce_applied_coupon`
   - Frontend JS tracker (`tracker.js`, <2KB): tracks `page_view` and `product_view` via REST endpoint `oddcareco/v1/event`
   - GDPR-safe: IP hashed with daily rotating salt, device type derived from User-Agent then discarded, URLs stripped of PII params
   - Cron jobs: daily aggregation at 2am, raw event cleanup at 3am (90-day retention), weekly referral expiry
   - Admin page: Analytics Dashboard (conversion funnel, top viewed products, top added-to-cart, device breakdown, date range picker)

6. **GDPR privacy handlers:** WordPress privacy data exporter + eraser registered for wishlists, referrals, and analytics data.

7. **Clean uninstall:** `uninstall.php` drops all 6 custom tables, removes plugin options and referral code usermeta.

8. **5 WooCommerce products created programmatically:**

| Product | SKU | Price | Stock | Product ID |
|---------|-----|-------|-------|-----------|
| Clear First (Facewash) | ODD-01 | ₹499 | 100 | 112 |
| Foam Rinse (Body Wash) | ODD-02 | ₹449 | 100 | 113 |
| Dawn Shield (AM Cream) | ODD-03 | ₹399 | 100 | 114 |
| Deep Dusk (PM Cream) | ODD-04 | ₹549 | 100 | 115 |
| The Group Project (Bundle) | ODD-BUNDLE | ~~₹1,896~~ ₹1,499 | 50 | 116 |

   - All products: published, stock managed, category "Skincare", product attributes (Size, Type, SPF where applicable)
   - Bundle has regular price ₹1,896 and sale price ₹1,499 (save ₹397)
   - Bundle stores child product IDs in custom meta `_oddcareco_bundle_product_ids`
   - Descriptions use actual product ingredients from the brief

### Plugin Structure

```
oddcareco-core/
  oddcareco-core.php              — Main plugin (activation hook, autoloader, HPOS compat)
  includes/
    class-oddcareco-db.php        — 6 table schemas, dbDelta, cron scheduling, drop_tables
    class-oddcareco-wishlist.php  — CRUD, AJAX toggle, My Account tab, share link
    class-oddcareco-referrals.php — Code gen, cookie capture, reward coupons, expiry cron
    class-oddcareco-analytics.php — Event recording, REST endpoint, WC hooks, aggregation cron
    class-oddcareco-privacy.php   — GDPR data exporter + eraser
  admin/
    class-oddcareco-admin.php             — Menu registration, asset enqueue
    class-oddcareco-admin-analytics.php   — Analytics dashboard controller
    class-oddcareco-admin-wishlist.php    — Wishlist analytics controller
    class-oddcareco-admin-referrals.php   — Referral management controller
    views/
      analytics-dashboard.php    — Funnel, top products, device breakdown
      wishlist-dashboard.php     — Stats cards, most-wishlisted table
      referrals-dashboard.php    — Stats, leaderboard, recent activity, settings form
  assets/
    admin.css    — Admin page styles (sage green accents, ODD brand)
    admin.js     — Funnel bar animation
    tracker.js   — Frontend analytics tracker (<2KB, sendBeacon)
  uninstall.php  — Clean table drop + option/meta cleanup
```

### Files Changed

| File | What changed |
|---|---|
| `wp-content/plugins/oddcareco-core/` (17 files) | New plugin — entire directory created |
| `ecommerce.md` | Updated Next Steps, added session log |

### Technical Notes

- **Architecture: plugin, not child theme** — Data layer code lives in `oddcareco-core` plugin, separate from the presentation-layer child theme (`oddcareco-child`). Plugin survives theme changes. Child theme `functions.php` (215 lines) remains untouched.
- **dbDelta() formatting** — Two spaces after `PRIMARY KEY`, each column on its own line, `KEY` not `INDEX`. Standard WordPress pattern from WooCommerce's own `class-wc-install.php`.
- **Referral status enum** — Changed from MySQL `enum()` to `varchar(20)` for `dbDelta()` compatibility (WordPress `dbDelta` doesn't handle MySQL enum types reliably).
- **WooCommerce HPOS compatibility** — Plugin declares compatibility with `custom_order_tables` via `FeaturesUtil::declare_compatibility()` in `before_woocommerce_init` hook.
- **Product creation** — Used temporary PHP script (`odd-create-products.php`, deleted after use) with `WC_Product_Simple` class. First run hit "duplicated SKU" error because products were partially created without the duplicate guard. Fixed by adding a cleanup pass that deletes existing products by SKU before creating fresh ones.
- **Bundle implementation** — `WC_Product_Simple` with regular_price=1896 and sale_price=1499 (not `WC_Product_Grouped`). Child product IDs stored in `_oddcareco_bundle_product_ids` postmeta for future stock-sync hook.
- **Existing static pages retained** — The editorial HTML pages (IDs 31, 33, 35, 37, 39) remain as landing pages. The new WooCommerce products (IDs 112-116) are separate product posts with "Add to Cart" functionality.

---

## Session Log — 2026-05-01: Homepage CSS Restoration, Product Section Fixes & 3D Mascot Pipeline

### Major Problems Solved

#### 1. Homepage CSS Completely Broken (wpautop corruption)

**Problem:** The entire homepage structure and architecture was broken — raw CSS was rendering as text, layout was destroyed, all styling gone.

**Root Cause:** WordPress's `wpautop()` filter. A previous session used `content.rendered` (from the REST API without `?context=edit`) to read the page content, then saved it back. The `content.rendered` version has already been processed by `wpautop()`, which injects `<p></p>` tags into `<style>` blocks. Saving this processed content back as raw content meant 70+ `<p>` tags were embedded inside CSS rules, breaking every single style declaration.

**Fix:** Analyzed all WordPress revisions (212-226) to find revision 222 as the last clean version with all features intact. Restored from that revision. All subsequent edits used `content.raw` (via `?context=edit` parameter) to avoid `wpautop()` processing.

**Key Learning:** When using the WordPress REST API, ALWAYS read with `?context=edit` to get `content.raw`. Never read `content.rendered` and write it back — `wpautop()` will corrupt any inline `<style>` blocks.

#### 2. Website Reverted to Old Version After Restoration

**Problem:** After fixing the CSS corruption, the site looked like an older version — missing the grid structure background, missing the logo in the hero section grid, and other recent changes.

**Root Cause:** The initial restoration went too far back — to revision 212, which predated several feature additions. Revisions 213-222 contained incremental improvements (hero merge, watermark, grid structure, etc.) that were lost.

**Fix:** Mapped the full revision history (212-226), identified that revision 223 was where corruption began (first `<p>` tags appeared inside `<style>` blocks), and restored to revision 222 instead — the last clean revision that contained all accumulated changes.

#### 3. Black Backgrounds in Product Display Section

**Problem:** The product section had unwanted black color backgrounds on the section label card and combo/bundle bar.

**Root Cause:** The `.odd-grid` container has `background: var(--black)` (which creates 2px black gap lines between cards). Cards that used `background: var(--beige)` were rendering as transparent because `--beige` was never defined as a CSS variable in the page's `:root` or `.wf {}` block — it was stripped during the WordPress push process.

**Fix:** Replaced all `var(--beige)` references with the hardcoded hex value `#F5F0EB`. Added explicit `style="background-color: #F5F0EB;"` inline attributes to `.card-section-label` and `.card-combo` HTML elements. Inline styles were used instead of CSS rules because WordPress strips CSS rules with compound selectors.

#### 4. Harsh Borders and Misaligned Products

**Problem:** The borders between product cards looked too harsh/industrial, and products were not aligned symmetrically in the product display section.

**Root Cause:** The `.odd-grid` 2px gap with black background created stark black divider lines. Product card image areas had `min-height: 180px` causing inconsistent heights across cards.

**Fix:** Added soft outline borders (`outline: 1px solid rgba(0,0,0,0.1); outline-offset: -1px`) to product cards and section labels. Changed `.product-card-img` from `min-height: 180px` to `height: 220px; display: flex; align-items: center; justify-content: center;` for consistent image area heights. Added flex layout to `.product-card-body` for consistent text alignment.

### 3D Mascot Pipeline (Created then Partially Removed)

Built a 3-step automated pipeline to generate an animated 3D mascot from a reference image:

#### Scripts Created

| File | Purpose | Status |
|------|---------|--------|
| `3d-mascot-pipeline/generate_mesh.py` | Step 1: Sends mascot.jpeg to Tripo3D or CSM API, downloads `mascot_raw.glb` | Kept |
| `3d-mascot-pipeline/process_mascot.py` | Step 2: Blender Python script — imports GLB, creates 9-bone humanoid armature, parents mesh with auto-weights, keyframes 60-frame waving animation, exports `mascot_waving.glb` | Kept |
| `3d-mascot-pipeline/run_pipeline.py` | Step 3: Orchestrator — chains Steps 1 & 2 with preflight checks (Blender detection, API key validation, `--skip-generate` flag) | Kept |
| `3d-mascot-pipeline/create_mascot_blender.py` | Alternative: Procedural Blender geometry generation (sphere head, cylinder body, capsule limbs, visor, "ODD" text, PBR materials, 3 NLA animations) | **Removed** — output quality was poor |
| `3d-mascot-pipeline/mascot_animated.glb` | Generated 3D model (282KB, 3290 faces, 11 bones, 3 animation clips) | **Removed** — looked choppy and untextured |
| `3d-mascot-pipeline/test_viewer.html` | Three.js GLB viewer with orbit controls and animation buttons | **Removed** |

#### Pipeline Problems Encountered

**Problem 1: Tripo3D API credits exhausted**
- **Error:** `403 Forbidden — "You don't have enough credit to create this task"`
- **Root Cause:** Free API credits on the Tripo3D account were used up. The image upload succeeded but task creation was blocked.
- **Resolution:** Pipeline works correctly — just needs credits or a different backend. `--skip-generate` flag allows bypassing Step 1 if a GLB is obtained manually.

**Problem 2: Unicode encoding error on Windows**
- **Error:** `UnicodeEncodeError: 'charmap' codec can't encode characters` when printing box-drawing characters (`╔═╗║╚╝`) and checkmarks (`✓`)
- **Root Cause:** Windows terminal uses cp1252 encoding which doesn't support Unicode box-drawing characters.
- **Fix:** Replaced all Unicode decorative characters with ASCII equivalents (`═` → `=`, `╔╗╚╝║` → removed, `✓` → `[OK]`, `—` → `--`).

**Problem 3: Blender 5.0 Action API change**
- **Error:** `AttributeError: 'Action' object has no attribute 'fcurves'`
- **Root Cause:** Blender 5.0 restructured the Action type to use a layered system (layers > strips > channelbags > fcurves) instead of direct `action.fcurves` access.
- **Fix:** Created `get_action_fcurves()` helper that tries the new layered API first (`action.layers[].strips[].channelbag(slot).fcurves`), falling back to `action.fcurves` for older Blender versions.

**Problem 4: Procedural model quality too low**
- **Outcome:** The Blender procedural approach produced a working 282KB GLB with geometry, materials, rig, and 3 animations — but the visual quality was choppy and untextured. Primitive geometry (spheres + cylinders) can't match the smooth, organic feel of the reference mascot.
- **Resolution:** Removed all generated files. A proper 3D model requires either: (a) a paid AI image-to-3D service with texturing, (b) manual sculpting by a 3D artist, or (c) the existing procedural Three.js mascot in `odd-mascot.js` which uses cel-shading to mask the geometric simplicity.

### Existing Procedural Mascot Discovery

During investigation, discovered that `product-pages/odd-mascot.js` (627 lines) already contains a fully functional procedural Three.js mascot with:
- Cel-shaded MeshToonMaterial rendering (hides geometric simplicity)
- Animations: wave, point, pop-up entrance, idle bob, random blink
- Mobile-optimized (reduced geometry segments, visibility observer)
- Already integrated into `product-pages/homepage.html`
- ~20KB JavaScript, zero external file dependencies

This procedural mascot is a viable alternative to a GLB model for website use.

### Files Changed

| File | What changed |
|---|---|
| WordPress page ID 26 | Restored from revision 222, CSS variable fixes, inline background colors, product alignment CSS |
| `3d-mascot-pipeline/generate_mesh.py` | Created (Tripo3D/CSM API mesh generation), fixed Unicode characters |
| `3d-mascot-pipeline/process_mascot.py` | Created (Blender rig + animation), fixed Unicode characters |
| `3d-mascot-pipeline/run_pipeline.py` | Created (pipeline orchestrator), fixed Unicode characters and encoding |

### Technical Notes

- **WordPress revision analysis**: Used `GET /wp-json/wp/v2/pages/26/revisions` to list all revisions, then checked each for `<p>` tag contamination inside `<style>` blocks. Revision 223 was the first corrupted one (introduced by saving `content.rendered` back as raw).
- **Inline styles vs CSS rules in WordPress**: WordPress strips CSS rules that use compound selectors (e.g., `.card-combo.span-4c`) from page content. Inline `style=""` attributes on HTML elements are the reliable workaround.
- **Blender 5.0 headless mode**: `blender.exe -b -P script.py` works on Windows. The `-b` flag runs without GUI. Blender 5.0 deprecates `Material.use_nodes` (scheduled for removal in 6.0) but it still functions.
- **NLA tracks for multi-animation GLB export**: Each animation must be a separate Action pushed to its own NLA track (muted) with the active action set to None. The glTF exporter then writes each track as a separate AnimationClip. Set `export_nla_strips=True` in export settings.

---

## Session Log — 2026-05-03: Product Card Hover Fix, Colored Circles & Section Label Restyle

### Changes Made

#### 1. Removed Black Hover Effect on Product Cards

**Problem:** Hovering over any product card in the product display section caused a solid black background flash.

**Root Cause:** The live homepage serves content from `homepage-wireframe.html` (NOT `homepage.html`). The wireframe's inline CSS had an explicit `background: var(--black)` rule on `.wf .product-card:hover` (lines 501-507). Previous attempts failed because they targeted the wrong file (`homepage.html`) which uses completely different class names (`.sys-card` vs `.product-card`).

**Fix:** Changed `.wf .product-card:hover` background from `var(--black)` to `#fff` in both the wireframe inline CSS and the child theme CSS (with `!important` to override inline styles). Also reset hover colors on `.card-tag`, `.product-price`, `.product-card-desc`, and `.product-card-body` border.

#### 2. Added Colored Circles Behind Product Bottles

Added decorative circles behind each product bottle using `::before` pseudo-elements on `.product-card-img`, with colors matching each product's theme:

| Product | Bottle class | Circle color | Size |
|---------|-------------|-------------|------|
| Clear First (Facewash) | `.bottle-tube` | `#d5d5d5` (light gray) | 140px |
| Foam Rinse (Body Wash) | `.bottle-foamer` | `#8a8a8a` (medium gray) | 165px |
| Dawn Shield (AM Cream) | `.bottle-airless-am` | `#d4b078` (golden tan) | 140px |
| Deep Dusk (PM Cream) | `.bottle-airless-pm` | `#1a1a1a` (black) | 140px |

Used CSS `:has()` selector to target circles per bottle type. Circles are absolutely positioned with `top: 50%; left: 50%; transform: translate(-50%, -50%)`.

#### 3. Centered Bottles and Circles in Card Boxes

Changed `.card-bottle-wrap` from `align-items: flex-end` (bottles at bottom) to `align-items: center` so bottles sit in the vertical center of their image area, aligned with the circles behind them.

#### 4. Restyled "THE SYSTEM" Section Label

- Changed background from beige to white (`#fff`)
- Changed heading text to Caveat (handwritten) font at 2.5rem bold
- Added organic hand-drawn SVG underlines on "THE SYSTEM" and "That's it." text using `::after` pseudo-elements with dual-path SVG data URIs (two overlapping wavy cubic bezier paths for brush-stroke effect)
- Positioned mascot image in bottom-right corner of the card
- Used JavaScript DOM manipulation in `functions.php` to wrap text in `.hand-underline` spans at runtime (avoids needing database access to modify WordPress page HTML)

### Files Changed

| File | What changed |
|---|---|
| `product-pages/homepage-wireframe.html` | Removed black hover CSS, added circle CSS, centered bottles, added `.hand-underline` spans to section label HTML |
| `wp-content/themes/oddcareco-child/style.css` | Added `!important` overrides for hover fix, circles, bottle centering, section label restyle (white bg, Caveat font, SVG underlines, mascot positioning) |
| `wp-content/themes/oddcareco-child/functions.php` | Added JS in homepage `wp_footer` hook to inject `.hand-underline` spans on `#wf-products` section label text |
| `ecommerce.md` | Added this session log |

### Technical Notes

- **Critical discovery: live page uses `homepage-wireframe.html`** — The live WordPress page (ID 26) serves content from `homepage-wireframe.html`, which uses `.wf .product-card` / `.odd-card` / `.odd-grid` classes. `homepage.html` uses completely different classes (`.sys-card`, `.system-grid`) and is NOT the live page. All homepage CSS changes must target the wireframe's class names.
- **Child theme CSS `!important` strategy** — The page's inline `<style>` loads in `<body>` after child theme CSS in `<head>`. Since inline CSS doesn't use `!important`, child theme `!important` declarations win the specificity battle.
- **SVG underline technique** — Two overlapping `<path>` elements in an SVG data URI: a primary stroke (2.8px width) and a secondary stroke (1.5px, 40% opacity) offset slightly, creating an organic hand-drawn appearance. The SVG uses `preserveAspectRatio="none"` to stretch to any text width.
- **DOM manipulation for WordPress content** — Since modifying WordPress page HTML requires database access, JavaScript in `functions.php`'s `wp_footer` hook wraps target text in `<span class="hand-underline">` at runtime. This approach works without touching the database and survives content edits.

---

## Session Log — 2026-05-04: Routine Section Merge & Reviews Ticker Redesign

### Changes Made

#### 1. Routine Section — Merged Headline + Product Timeline Into Single Row

**Problem:** The "3.5 Minutes Total" headline (Card I) and the 4 product timeline steps (Card J) were in separate bento grid cards, taking up two rows.

**Fix:** Merged both cards into a single `span-4c` card with internal flexbox layout (`.routine-combined`). The headline sits on the left (`flex: 0 0 220px`) and the 4 product steps fill the right side (`flex: 1`). Section 04 ("We don't do shortcuts") was pushed down below. Used ASCII comment markers (`<!-- Card I:`, `<!-- Card K:`) as boundaries for string replacement — unicode box-drawing characters in comments are unreliable for JavaScript string matching in the WordPress editor.

#### 2. Reviews Section — Replaced Static Cards With Multi-Lane Auto-Scrolling Ticker

**Problem:** The homepage had 3 static review cards (Ananya, Rohan, Mehak) inside the bento grid. The user wanted a unique, dynamic, playful display matching the site's design language.

**Design process:** Used the brainstorming skill to explore options. User chose: 10+ reviews, auto-rotating, playful & dynamic, full-width breakout from the grid. Three approaches proposed (multi-lane ticker, floating card cloud, 3D carousel drum). User selected multi-lane ticker. Visual mockup built at `product-pages/reviews-mockup.html` and approved before implementation.

**Implementation:**
- Split the single `.odd-grid` into two grids at the review boundary
- Removed the 3 static `.card-review` cards (grid indices 7, 8, 9)
- Inserted a full-width `.wf-reviews-ticker` section between the two grids
- Section contains 3 horizontal lanes of review cards scrolling in alternating directions:
  - Lane 1: scrolls left at 40s
  - Lane 2: scrolls right at 55s (slower, per user request)
  - Lane 3: scrolls left at 50s
- 12 reviews (expanded from 3), with a realistic mix of 4-star and 5-star ratings
- Cards duplicate for seamless infinite loop
- Pause on hover per lane
- Fade-to-background edges on both sides (120px gradient)
- Section eyebrow: "05 — What They Say" / "Real People. Real Routines." / "no paid actors, we promise" (Caveat handwriting font)

**Card styling (matching homepage theme):**
- White background, square corners (no border-radius), 1px border `rgba(0,0,0,0.08)`
- Grid pattern overlay (28px, 3% opacity — matches page background grid)
- Sage green avatars, star ratings, and tags
- Tags per review: VERIFIED, NEW USER, 3 MONTHS, 6 MONTHS, 1 YEAR
- Hover: scale 1.03 + soft box-shadow
- 4-star reviews use outline star (☆) for empty position

**CSS scoped under `.wf`** to avoid conflicts. Uses `@keyframes wfScrollLeft` / `wfScrollRight` (prefixed to avoid collision with any existing animations). JavaScript populates lanes from a reviews data array using `innerHTML` duplication.

#### 3. Reviews Data (12 reviews)

| Name | Age | Skin Type | Stars | Tag |
|------|-----|-----------|-------|-----|
| Ananya | 28 | Combination | 5 | verified |
| Rohan | 31 | Oily | 5 | verified |
| Mehak | 26 | Dry | 4 | verified |
| Priya | 24 | Sensitive | 5 | 3 months |
| Arjun | 29 | Normal | 4 | new user |
| Kavya | 33 | Combination | 5 | verified |
| Vikram | 27 | Oily | 5 | 6 months |
| Sneha | 30 | Dry | 4 | verified |
| Aditya | 25 | Acne-Prone | 5 | new user |
| Neha | 34 | Mature | 4 | 1 year |
| Rahul | 22 | Oily | 5 | verified |
| Isha | 28 | Sensitive | 5 | verified |

### Files Changed

| File | What changed |
|---|---|
| WordPress page ID 26 | Merged routine cards, replaced 3 static review cards with full-width ticker section, split `.odd-grid` into two |
| `product-pages/reviews-mockup.html` | Created — standalone mockup of the ticker design (used for visual approval before implementation) |

### Technical Notes

- **Grid split strategy:** The single `.odd-grid` was split at the review boundary. `</div><!-- /.odd-grid part 1 -->` closes the first grid after `.card-rules`, the ticker section sits between, then `<div class="odd-grid">` reopens for remaining cards (mascot, mission, footer). The original `</div><!-- /.odd-grid -->` closing tag now closes the second grid.
- **WordPress code editor injection:** Content modified via `nativeInputValueSetter` on the code editor textarea + dispatching `input` event to trigger Gutenberg state updates. This is the reliable pattern for programmatic WordPress content edits.
- **Unicode in WordPress JS:** Star characters (★☆) are rendered via `\u2605` / `\u2606` in the inline `<script>`. The createCard function builds star strings dynamically based on each review's `stars` field.
- **Comment markers for boundaries:** Used `<!-- Card N: Review 1 -->` and `<!-- Card Q: Mascot Feature -->` as replacement boundaries — ASCII-only comments are reliable for JavaScript `indexOf()` matching in the WordPress editor.

---

## Session Log — 2026-05-04: About Us Section Redesign & Footer Beige Theme

All changes applied to live Local by Flywheel WordPress site. Files modified: `oddcareco-child/style.css`, `product-pages/homepage-wireframe.html`, WordPress page ID 26 (homepage content via REST API).

### Changes Made

#### 1. About Us Section — Complete Redesign

**Problem:** The About section had 4 misaligned value boxes inside the `.odd-grid` (black background). The black grid background was overlapping into the section, making it look broken.

**Fix:** Replaced the 4 boxed value cards with a clean, box-free standalone section outside the grid.

- Removed Cards R, S, T, U (Clean & Safe, Cruelty Free, Sustainable, Made in India) from inside `.odd-grid`
- Created new `.wf-about` section with 6 values in a 3x2 centered grid layout
- Added 2 new values: **Dermatologist Tested** ("Tested by experts. Not just influencers.") and **pH Balanced** ("Your skin's comfort zone. We stay in it.")
- Section heading: "ABOUT US" in sage green small caps + subtitle "What we believe in. Nothing more."
- Sage green SVG icons for each value, centered with subtle divider lines between cells
- Beige background (#F5F0EB), full-width (no max-width constraint), responsive single-column on mobile

#### 2. Removed Mascot Section

- The mascot card ("Built different. On purpose.") was the only card left in the second `.odd-grid` after the value cards were removed
- This caused a large black rectangle (grid background showing through empty cells)
- Removed the second `.odd-grid` wrapper entirely and the mascot card with it
- Flow is now: Reviews Ticker → About Us → Footer

#### 3. Reviews-to-About Transition

**Problem:** Hard visual cut between the white reviews section and beige About section.

**Fix:**
- Added `::after` gradient on `.wf-reviews-ticker` that fades from transparent to beige (#F5F0EB), 80px height
- Made `.wf-about` full-width (`max-width: none`) so the beige background covers edge-to-edge, hiding the grid pattern on the sides

#### 4. Footer Redesign — Beige Theme with Watermark

**Problem:** The dark black footer (#000) clashed with the site's light, minimalist beige theme.

**Fix:** Complete color inversion from dark-on-light to light-on-dark:

- **Background:** Changed from black (#000) to beige (#F5F0EB) with dark grid overlay (`rgba(0,0,0,0.03)`)
- **"ODD CARE CO." watermark:** Added `::before` pseudo-element matching the hero section's watermark — `font-size: 11.4vw`, `font-weight: 900`, `color: rgba(0,0,0,0.06)`, centered absolute position
- **Newsletter:** Dark border input, dark (#1a1a1a) subscribe button with white text
- **Column headings:** Bold black (#1a1a1a), `font-weight: 800`
- **Links:** Dark gray (#333), hover → black (#000)
- **Trust badges:** Dark gray (#333) text, #555 icon strokes
- **Copyright/bottom bar:** #666 text, social icons #555 → hover #000
- **Borders:** `rgba(0,0,0,0.08)` (dark subtle lines instead of white)
- **Structure unchanged:** Newsletter CTA, 4-column links (Shop, Company, Help, badges), bottom bar with socials

### Files Changed

| File | What changed |
|---|---|
| `oddcareco-child/style.css` | About section CSS (~80 lines), reviews-to-about gradient transition, mascot standalone styles, footer beige redesign with watermark (~90 lines) |
| `product-pages/homepage-wireframe.html` | Replaced 4 value cards with standalone `.wf-about` section (6 values, 3x2 grid) |
| WordPress page ID 26 | Removed value cards (ROW 8), removed second `.odd-grid` + mascot card, inserted About Us section HTML |

### Technical Notes

- **About section outside grid:** The `.wf-about` section sits between the grid close (`</div><!-- /.odd-grid -->`) and the footer (`<footer class="wf-footer">`). It's a standalone section, not inside any grid container.
- **Full-width beige coverage:** `.wf-about` uses `width: 100%; max-width: none` to ensure the beige background covers the full viewport width, hiding the parent `.wf` container's grid-pattern background on the edges.
- **Footer watermark source:** The hero section's "ODD CARE CO." watermark is defined in inline CSS on the page as `.card-hero-merged::before` with `content: "ODD  CARE  CO."`, `font-size: 11.4vw`, `color: rgba(0,0,0,0.06)`. The footer uses the same values on `.wf .wf-footer::before`.
- **Footer z-index stacking:** `.wf-footer::before` (watermark) has `z-index: 0`, `.footer-inner` has `z-index: 1` — ensures text content sits above the watermark.
- **WordPress REST API updates:** Page content modified via `wp.apiFetch()` from the admin editor tab, using `?context=edit` to read `content.raw` (avoiding `wpautop` corruption).
