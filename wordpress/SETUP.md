# ODD Care Co — WordPress Local Setup Guide

See the **First-Time Setup** section in the root `CLAUDE.md` for the complete guide.

## Quick Reference

```bash
# 1. Create a site in Local by Flywheel (name: odd-care-co, PHP 8.x, MySQL 8.x, nginx)

# 2. Run the setup script (copies themes, plugins, uploads)
bash scripts/setup.sh "/c/Users/<YOUR_USERNAME>/Local Sites/odd-care-co"

# 3. Import database (in Local's Site Shell)
mysql -u root -proot local < wordpress/database/local.sql

# 4. Update site URL if your domain differs from odd-care-co.local (in Adminer)
# UPDATE wp_options SET option_value = 'http://YOUR-SITE.local' WHERE option_name IN ('siteurl', 'home');

# 5. Install WooCommerce from WP Admin -> Plugins -> Add New

# 6. Activate theme: WP Admin -> Appearance -> ODD Care Co Child
# 7. Activate plugin: WP Admin -> Plugins -> ODD Care Co Core
```

## Database credentials (Local by Flywheel defaults)

| Setting | Value |
|---------|-------|
| DB Name | `local` |
| DB User | `root` |
| DB Password | `root` |
| DB Host | `localhost` |
