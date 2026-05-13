<?php
/**
 * ODD Care Co Core — Uninstall
 *
 * Drops all custom tables and removes plugin options.
 * Only runs when the plugin is explicitly deleted from WP Admin.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/class-oddcareco-db.php';

// Need to define the constant since the main plugin file isn't loaded during uninstall.
if ( ! defined( 'ODDCARECO_DB_VERSION' ) ) {
    define( 'ODDCARECO_DB_VERSION', '1.0.0' );
}

OddCareCo_DB::drop_tables();

// Clean up cron events.
wp_clear_scheduled_hook( 'oddcareco_analytics_aggregate' );
wp_clear_scheduled_hook( 'oddcareco_analytics_cleanup' );
wp_clear_scheduled_hook( 'oddcareco_referral_expiry' );

// Remove all user meta for referral codes.
global $wpdb;
$wpdb->delete( $wpdb->usermeta, [ 'meta_key' => '_oddcareco_referral_code' ] );
