<?php
/**
 * Database schema and table management for ODD Care Co.
 *
 * Creates 6 custom tables via dbDelta() on plugin activation.
 * Handles version upgrades and cron scheduling.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_DB {

    /**
     * Plugin activation: create tables + schedule crons.
     */
    public static function activate() {
        self::create_tables();
        self::schedule_crons();
        update_option( 'oddcareco_db_version', ODDCARECO_DB_VERSION );

        // Set default referral settings if not already configured.
        if ( ! get_option( 'oddcareco_referral_settings' ) ) {
            update_option( 'oddcareco_referral_settings', [
                'referrer_reward_amount' => 100,
                'referrer_reward_type'   => 'fixed_cart',
                'referee_discount'       => 10,
                'referee_discount_type'  => 'percent',
                'referral_cookie_days'   => 30,
                'reward_min_spend'       => 399,
                'reward_expiry_days'     => 90,
                'pending_expiry_days'    => 60,
            ]);
        }

        // Set default analytics settings.
        if ( ! get_option( 'oddcareco_analytics_settings' ) ) {
            update_option( 'oddcareco_analytics_settings', [
                'retention_days' => 90,
                'enabled'        => true,
            ]);
        }
    }

    /**
     * Plugin deactivation: clear scheduled crons.
     */
    public static function deactivate() {
        wp_clear_scheduled_hook( 'oddcareco_analytics_aggregate' );
        wp_clear_scheduled_hook( 'oddcareco_analytics_cleanup' );
        wp_clear_scheduled_hook( 'oddcareco_referral_expiry' );
    }

    /**
     * Create all 6 custom tables using dbDelta().
     */
    private static function create_tables() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();
        $prefix          = $wpdb->prefix;

        /*
         * dbDelta() is particular about formatting:
         * - Two spaces after PRIMARY KEY
         * - Each column on its own line
         * - KEY definitions use KEY not INDEX
         */

        $sql = "
CREATE TABLE {$prefix}oddcareco_wishlists (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    user_id bigint(20) unsigned NOT NULL,
    title varchar(200) NOT NULL DEFAULT 'My Wishlist',
    share_key varchar(32) DEFAULT NULL,
    is_public tinyint(1) NOT NULL DEFAULT 0,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    KEY user_id (user_id),
    UNIQUE KEY share_key (share_key)
) $charset_collate;

CREATE TABLE {$prefix}oddcareco_wishlist_items (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    wishlist_id bigint(20) unsigned NOT NULL,
    product_id bigint(20) unsigned NOT NULL,
    added_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    note varchar(500) DEFAULT NULL,
    PRIMARY KEY  (id),
    UNIQUE KEY wishlist_product (wishlist_id, product_id),
    KEY product_id (product_id)
) $charset_collate;

CREATE TABLE {$prefix}oddcareco_referrals (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    referrer_user_id bigint(20) unsigned NOT NULL,
    referral_code varchar(20) NOT NULL,
    referee_user_id bigint(20) unsigned DEFAULT NULL,
    referee_email varchar(200) DEFAULT NULL,
    referee_order_id bigint(20) unsigned DEFAULT NULL,
    status varchar(20) NOT NULL DEFAULT 'pending',
    reward_type varchar(50) DEFAULT NULL,
    reward_value decimal(10,2) DEFAULT NULL,
    reward_coupon_id bigint(20) unsigned DEFAULT NULL,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at datetime DEFAULT NULL,
    rewarded_at datetime DEFAULT NULL,
    PRIMARY KEY  (id),
    UNIQUE KEY referral_code (referral_code),
    KEY referrer_user_id (referrer_user_id),
    KEY referee_user_id (referee_user_id),
    KEY status (status)
) $charset_collate;

CREATE TABLE {$prefix}oddcareco_referral_stats (
    user_id bigint(20) unsigned NOT NULL,
    total_referrals int(11) NOT NULL DEFAULT 0,
    completed_referrals int(11) NOT NULL DEFAULT 0,
    total_reward_value decimal(10,2) NOT NULL DEFAULT 0.00,
    last_referral_at datetime DEFAULT NULL,
    PRIMARY KEY  (user_id)
) $charset_collate;

CREATE TABLE {$prefix}oddcareco_analytics_events (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    session_id varchar(64) NOT NULL,
    user_id bigint(20) unsigned DEFAULT NULL,
    event_type varchar(50) NOT NULL,
    event_data text DEFAULT NULL,
    product_id bigint(20) unsigned DEFAULT NULL,
    page_url varchar(2048) DEFAULT NULL,
    referrer_url varchar(2048) DEFAULT NULL,
    device_type varchar(20) DEFAULT NULL,
    ip_hash varchar(64) DEFAULT NULL,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    KEY session_id (session_id),
    KEY user_id (user_id),
    KEY event_type (event_type),
    KEY product_id (product_id),
    KEY created_at (created_at),
    KEY event_type_created (event_type, created_at)
) $charset_collate;

CREATE TABLE {$prefix}oddcareco_analytics_daily (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    event_date date NOT NULL,
    event_type varchar(50) NOT NULL,
    product_id bigint(20) unsigned DEFAULT NULL,
    device_type varchar(20) DEFAULT NULL,
    event_count int(11) unsigned NOT NULL DEFAULT 0,
    unique_sessions int(11) unsigned NOT NULL DEFAULT 0,
    unique_users int(11) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY  (id),
    UNIQUE KEY daily_aggregate (event_date, event_type, product_id, device_type)
) $charset_collate;
";

        dbDelta( $sql );
    }

    /**
     * Schedule recurring cron jobs.
     */
    private static function schedule_crons() {
        if ( ! wp_next_scheduled( 'oddcareco_analytics_aggregate' ) ) {
            wp_schedule_event( strtotime( 'tomorrow 02:00' ), 'daily', 'oddcareco_analytics_aggregate' );
        }
        if ( ! wp_next_scheduled( 'oddcareco_analytics_cleanup' ) ) {
            wp_schedule_event( strtotime( 'tomorrow 03:00' ), 'daily', 'oddcareco_analytics_cleanup' );
        }
        if ( ! wp_next_scheduled( 'oddcareco_referral_expiry' ) ) {
            wp_schedule_event( time(), 'weekly', 'oddcareco_referral_expiry' );
        }
    }

    /**
     * Drop all custom tables (called from uninstall.php only).
     */
    public static function drop_tables() {
        global $wpdb;
        $prefix = $wpdb->prefix;

        $tables = [
            'oddcareco_wishlists',
            'oddcareco_wishlist_items',
            'oddcareco_referrals',
            'oddcareco_referral_stats',
            'oddcareco_analytics_events',
            'oddcareco_analytics_daily',
        ];

        foreach ( $tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$prefix}{$table}" );
        }

        delete_option( 'oddcareco_db_version' );
        delete_option( 'oddcareco_referral_settings' );
        delete_option( 'oddcareco_analytics_settings' );
    }
}
