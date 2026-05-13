<?php
/**
 * Plugin Name: ODD Care Co Core
 * Plugin URI:  https://oddcareco.com
 * Description: Custom features for ODD Care Co — wishlists, referral codes, analytics dashboard.
 * Version:     1.0.0
 * Author:      ODD Care Co
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 8.0
 * WC tested up to: 10.6.2
 * Text Domain: oddcareco-core
 */

defined( 'ABSPATH' ) || exit;

define( 'ODDCARECO_VERSION', '1.0.0' );
define( 'ODDCARECO_DB_VERSION', '1.0.0' );
define( 'ODDCARECO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ODDCARECO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* ── Autoload classes ────────────────────────────────────── */

require_once ODDCARECO_PLUGIN_DIR . 'includes/class-oddcareco-db.php';
require_once ODDCARECO_PLUGIN_DIR . 'includes/class-oddcareco-wishlist.php';
require_once ODDCARECO_PLUGIN_DIR . 'includes/class-oddcareco-referrals.php';
require_once ODDCARECO_PLUGIN_DIR . 'includes/class-oddcareco-analytics.php';
require_once ODDCARECO_PLUGIN_DIR . 'includes/class-oddcareco-privacy.php';

if ( is_admin() ) {
    require_once ODDCARECO_PLUGIN_DIR . 'admin/class-oddcareco-admin.php';
    require_once ODDCARECO_PLUGIN_DIR . 'admin/class-oddcareco-admin-analytics.php';
    require_once ODDCARECO_PLUGIN_DIR . 'admin/class-oddcareco-admin-wishlist.php';
    require_once ODDCARECO_PLUGIN_DIR . 'admin/class-oddcareco-admin-referrals.php';
}

/* ── Activation / Deactivation ───────────────────────────── */

register_activation_hook( __FILE__, [ 'OddCareCo_DB', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'OddCareCo_DB', 'deactivate' ] );

/* ── DB version check on every load ──────────────────────── */

add_action( 'plugins_loaded', function () {
    $current = get_option( 'oddcareco_db_version', '0' );
    if ( version_compare( $current, ODDCARECO_DB_VERSION, '<' ) ) {
        OddCareCo_DB::activate();
    }
});

/* ── Initialize feature modules ──────────────────────────── */

add_action( 'init', function () {
    OddCareCo_Wishlist::init();
    OddCareCo_Referrals::init();
    OddCareCo_Analytics::init();
    OddCareCo_Privacy::init();
});

/* ── Admin pages ─────────────────────────────────────────── */

if ( is_admin() ) {
    add_action( 'init', function () {
        OddCareCo_Admin::init();
    });
}

/* ── Declare WooCommerce HPOS compatibility ──────────────── */

add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            __FILE__,
            true
        );
    }
});
