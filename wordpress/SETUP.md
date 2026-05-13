# ODD Care Co — WordPress Local Setup Guide

Follow these steps to get the site running on your machine.

## Prerequisites

- [Local by Flywheel](https://localwp.com/) installed

## Steps

### 1. Create a new site in Local by Flywheel

- Open Local → Create a new site
- Name: `odd-care-co` (or anything you prefer)
- Choose **Custom** environment: PHP 8.x, MySQL 8.x, nginx
- Set username/password (you'll replace the DB anyway)
- Let it finish creating the site

### 2. Import the database

- In Local, right-click your site → **Open Site Shell**
- Run this command to import the database:

```bash
mysql -u root -proot local < /path/to/this/repo/wordpress/database/local.sql
```

Or use **Adminer** (Database tab in Local) to import `wordpress/database/local.sql`.

**Important:** After importing, update the site URL in the database if your Local site uses a different domain. In Adminer, run:

```sql
UPDATE wp_options SET option_value = 'http://YOUR-LOCAL-SITE.local' WHERE option_name IN ('siteurl', 'home');
```

### 3. Copy theme files

Copy these into your Local site's `wp-content/themes/` folder:

```
wordpress/wp-content/themes/kadence/       → app/public/wp-content/themes/kadence/
wordpress/wp-content/themes/oddcareco-child/ → app/public/wp-content/themes/oddcareco-child/
```

Your Local site's folder is usually at:
- **Windows:** `C:\Users\<you>\Local Sites\<site-name>\app\public\`
- **Mac:** `~/Local Sites/<site-name>/app/public/`

### 4. Copy the custom plugin

```
wordpress/wp-content/plugins/oddcareco-core/ → app/public/wp-content/plugins/oddcareco-core/
```

### 5. Copy uploads (media files)

```
wordpress/wp-content/uploads/ → app/public/wp-content/uploads/
```

### 6. Install WooCommerce

- Go to WP Admin → Plugins → Add New → Search "WooCommerce" → Install & Activate
- The products and settings are already in the database

### 7. Activate theme & plugin

- WP Admin → Appearance → Themes → Activate **ODD Care Co Child**
- WP Admin → Plugins → Activate **ODD Care Co Core**

### 8. Verify

- Visit the frontend — homepage and product pages should load
- Check WP Admin → WooCommerce → Products — all 4 SKUs should be there

## Database credentials (Local by Flywheel defaults)

| Setting | Value |
|---------|-------|
| DB Name | `local` |
| DB User | `root` |
| DB Password | `root` |
| DB Host | `localhost` |

## Reference

- `wp-config-sample.php` — copy of the original wp-config.php for reference
- `database/local.sql` — full WordPress database dump
