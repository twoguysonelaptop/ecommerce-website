#!/bin/bash
# ============================================================
# ODD Care Co — Local WordPress Setup Script
# ============================================================
# This script copies themes, plugins, uploads, and imports the
# database into a Local by Flywheel site.
#
# Usage:
#   bash scripts/setup.sh <local-site-path>
#
# Example:
#   bash scripts/setup.sh "/c/Users/YourName/Local Sites/odd-care-co"
#   bash scripts/setup.sh ~/Local\ Sites/odd-care-co
# ============================================================

set -e

# --- Validate arguments ---
if [ -z "$1" ]; then
    echo ""
    echo "Usage: bash scripts/setup.sh <local-site-path>"
    echo ""
    echo "  <local-site-path>  Path to your Local by Flywheel site folder"
    echo ""
    echo "Example:"
    echo "  bash scripts/setup.sh \"/c/Users/YourName/Local Sites/odd-care-co\""
    echo "  bash scripts/setup.sh ~/Local\\ Sites/odd-care-co"
    echo ""
    exit 1
fi

LOCAL_SITE="$1"
WP_ROOT="$LOCAL_SITE/app/public"
WP_CONTENT="$WP_ROOT/wp-content"
REPO_DIR="$(cd "$(dirname "$0")/.." && pwd)"
WP_EXPORT="$REPO_DIR/wordpress"

# --- Validate paths ---
if [ ! -d "$LOCAL_SITE" ]; then
    echo "ERROR: Local site path not found: $LOCAL_SITE"
    echo "Make sure you've created a site in Local by Flywheel first."
    exit 1
fi

if [ ! -d "$WP_ROOT" ]; then
    echo "ERROR: WordPress root not found at: $WP_ROOT"
    echo "This doesn't look like a valid Local by Flywheel site."
    exit 1
fi

echo ""
echo "============================================"
echo "  ODD Care Co — WordPress Setup"
echo "============================================"
echo ""
echo "Repo:       $REPO_DIR"
echo "Local site: $LOCAL_SITE"
echo "WP root:    $WP_ROOT"
echo ""

# --- Step 1: Copy child theme ---
echo "[1/5] Copying child theme (oddcareco-child)..."
cp -r "$WP_EXPORT/wp-content/themes/oddcareco-child" "$WP_CONTENT/themes/"
echo "      Done."

# --- Step 2: Copy parent theme (Kadence) ---
echo "[2/5] Copying parent theme (kadence)..."
if [ -d "$WP_CONTENT/themes/kadence" ]; then
    echo "      Kadence already exists — replacing with repo version..."
    rm -rf "$WP_CONTENT/themes/kadence"
fi
cp -r "$WP_EXPORT/wp-content/themes/kadence" "$WP_CONTENT/themes/"
echo "      Done."

# --- Step 3: Copy custom plugin ---
echo "[3/5] Copying custom plugin (oddcareco-core)..."
cp -r "$WP_EXPORT/wp-content/plugins/oddcareco-core" "$WP_CONTENT/plugins/"
echo "      Done."

# --- Step 4: Copy uploads (media files) ---
echo "[4/5] Copying uploads (logos, labels, product images)..."
cp -r "$WP_EXPORT/wp-content/uploads/"* "$WP_CONTENT/uploads/" 2>/dev/null || true
echo "      Done."

# --- Step 5: Import database ---
echo "[5/5] Importing database..."
SQL_FILE="$WP_EXPORT/database/local.sql"

if [ ! -f "$SQL_FILE" ]; then
    echo "      ERROR: Database file not found at: $SQL_FILE"
    exit 1
fi

# Try to find the mysql binary from Local by Flywheel
MYSQL_BIN=""
if command -v mysql &>/dev/null; then
    MYSQL_BIN="mysql"
elif [ -f "$LOCAL_SITE/conf/mysql/mysqld.cnf" ]; then
    echo "      NOTE: mysql CLI not in PATH."
    echo "      Import the database manually using Adminer (Database tab in Local)."
    echo "      File: $SQL_FILE"
    MYSQL_BIN=""
fi

if [ -n "$MYSQL_BIN" ]; then
    $MYSQL_BIN -u root -proot local < "$SQL_FILE" 2>/dev/null && echo "      Database imported." || {
        echo "      Auto-import failed. Import manually:"
        echo "      1. In Local, right-click your site -> Open Site Shell"
        echo "      2. Run: mysql -u root -proot local < \"$SQL_FILE\""
        echo "      Or use Adminer (Database tab in Local) to import the file."
    }
else
    echo "      Skipped auto-import (mysql not in PATH)."
    echo ""
    echo "      MANUAL DATABASE IMPORT REQUIRED:"
    echo "      1. In Local, right-click your site -> Open Site Shell"
    echo "      2. Run: mysql -u root -proot local < \"$SQL_FILE\""
    echo "      Or use Adminer (Database tab in Local) to import the file."
fi

# --- Done ---
echo ""
echo "============================================"
echo "  Setup complete!"
echo "============================================"
echo ""
echo "Remaining manual steps:"
echo ""
echo "  1. IMPORT DATABASE (if not auto-imported above):"
echo "     - Open Local -> right-click site -> Open Site Shell"
echo "     - Run: mysql -u root -proot local < \"$SQL_FILE\""
echo ""
echo "  2. UPDATE SITE URL (if your Local site domain differs):"
echo "     The database uses: http://odd-care-co.local"
echo "     If your site uses a different domain, open Adminer and run:"
echo "       UPDATE wp_options SET option_value = 'http://YOUR-SITE.local'"
echo "       WHERE option_name IN ('siteurl', 'home');"
echo ""
echo "  3. INSTALL WOOCOMMERCE:"
echo "     WP Admin -> Plugins -> Add New -> Search 'WooCommerce' -> Install & Activate"
echo ""
echo "  4. ACTIVATE THEME & PLUGIN:"
echo "     WP Admin -> Appearance -> Themes -> Activate 'ODD Care Co Child'"
echo "     WP Admin -> Plugins -> Activate 'ODD Care Co Core'"
echo ""
echo "  5. VERIFY:"
echo "     - Visit the frontend — homepage should load"
echo "     - WP Admin -> WooCommerce -> Products — 4 SKUs should appear"
echo ""
