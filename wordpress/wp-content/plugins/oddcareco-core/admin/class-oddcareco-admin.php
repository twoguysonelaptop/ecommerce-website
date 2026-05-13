<?php
/**
 * Admin menu registration and shared utilities.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Admin {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    public static function register_menus() {
        // Top-level menu.
        add_menu_page(
            'ODD Care Co',
            'ODD Care Co',
            'manage_woocommerce',
            'oddcareco',
            [ 'OddCareCo_Admin_Analytics', 'render' ],
            'dashicons-store',
            56
        );

        // Submenu: Analytics (default page).
        add_submenu_page(
            'oddcareco',
            'Analytics',
            'Analytics',
            'manage_woocommerce',
            'oddcareco',
            [ 'OddCareCo_Admin_Analytics', 'render' ]
        );

        // Submenu: Wishlists.
        add_submenu_page(
            'oddcareco',
            'Wishlists',
            'Wishlists',
            'manage_woocommerce',
            'oddcareco-wishlists',
            [ 'OddCareCo_Admin_Wishlist', 'render' ]
        );

        // Submenu: Referrals.
        add_submenu_page(
            'oddcareco',
            'Referrals',
            'Referrals',
            'manage_woocommerce',
            'oddcareco-referrals',
            [ 'OddCareCo_Admin_Referrals', 'render' ]
        );
    }

    public static function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'oddcareco' ) === false ) return;

        wp_enqueue_style(
            'oddcareco-admin',
            ODDCARECO_PLUGIN_URL . 'assets/admin.css',
            [],
            ODDCARECO_VERSION
        );

        wp_enqueue_script(
            'oddcareco-admin-js',
            ODDCARECO_PLUGIN_URL . 'assets/admin.js',
            [],
            ODDCARECO_VERSION,
            true
        );
    }
}
