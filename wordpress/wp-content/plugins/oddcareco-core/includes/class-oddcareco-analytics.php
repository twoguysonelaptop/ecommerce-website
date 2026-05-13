<?php
/**
 * Analytics tracking — event recording, REST endpoint, cron aggregation/cleanup.
 *
 * Records: page_view, product_view, add_to_cart, remove_from_cart,
 *          begin_checkout, purchase_complete, wishlist_add, wishlist_remove,
 *          referral_link_click, search, coupon_applied.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Analytics {

    public static function init() {
        $settings = get_option( 'oddcareco_analytics_settings', [] );
        if ( empty( $settings['enabled'] ) ) return;

        // REST endpoint for frontend tracker.
        add_action( 'rest_api_init', [ __CLASS__, 'register_rest_route' ] );

        // WooCommerce server-side hooks.
        add_action( 'woocommerce_add_to_cart', [ __CLASS__, 'on_add_to_cart' ], 10, 4 );
        add_action( 'woocommerce_remove_cart_item', [ __CLASS__, 'on_remove_from_cart' ], 10, 2 );
        add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'on_checkout' ], 10, 3 );
        add_action( 'woocommerce_payment_complete', [ __CLASS__, 'on_purchase' ] );
        add_action( 'woocommerce_applied_coupon', [ __CLASS__, 'on_coupon_applied' ] );

        // Cron handlers.
        add_action( 'oddcareco_analytics_aggregate', [ __CLASS__, 'cron_aggregate' ] );
        add_action( 'oddcareco_analytics_cleanup', [ __CLASS__, 'cron_cleanup' ] );

        // Frontend tracker script.
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_tracker' ] );
    }

    /* ── REST endpoint ───────────────────────────────────── */

    public static function register_rest_route() {
        register_rest_route( 'oddcareco/v1', '/event', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'rest_record_event' ],
            'permission_callback' => '__return_true',
        ] );
    }

    /**
     * Handle frontend event tracking via REST.
     */
    public static function rest_record_event( $request ) {
        $event_type = sanitize_text_field( $request->get_param( 'event_type' ) ?? '' );
        $allowed    = [
            'page_view', 'product_view', 'add_to_cart', 'remove_from_cart',
            'begin_checkout', 'purchase_complete', 'wishlist_add', 'wishlist_remove',
            'referral_link_click', 'search', 'coupon_applied',
        ];

        if ( ! in_array( $event_type, $allowed, true ) ) {
            return new \WP_Error( 'invalid_event', 'Invalid event type.', [ 'status' => 400 ] );
        }

        $data = [
            'product_id'   => absint( $request->get_param( 'product_id' ) ),
            'page_url'     => esc_url_raw( $request->get_param( 'page_url' ) ?? '' ),
            'referrer_url' => esc_url_raw( $request->get_param( 'referrer_url' ) ?? '' ),
            'session_id'   => sanitize_text_field( $request->get_param( 'session_id' ) ?? '' ),
            'extra'        => $request->get_param( 'extra' ),
        ];

        self::record_event( $event_type, $data );

        return new \WP_REST_Response( [ 'ok' => true ], 200 );
    }

    /* ── Event recording ─────────────────────────────────── */

    /**
     * Record an analytics event.
     *
     * @param string $event_type One of the allowed event types.
     * @param array  $data       Optional event data.
     */
    public static function record_event( $event_type, $data = [] ) {
        global $wpdb;

        $session_id = $data['session_id'] ?? ( $_COOKIE['oddcareco_sid'] ?? wp_generate_uuid4() );
        $user_id    = get_current_user_id() ?: null;

        // GDPR: hash IP with daily rotating salt.
        $salt    = get_transient( 'oddcareco_ip_salt' );
        if ( ! $salt ) {
            $salt = wp_generate_password( 32, true, true );
            set_transient( 'oddcareco_ip_salt', $salt, DAY_IN_SECONDS );
        }
        $ip_hash = hash( 'sha256', ( $_SERVER['REMOTE_ADDR'] ?? '' ) . $salt );

        // Device type from User-Agent.
        $ua          = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $device_type = 'desktop';
        if ( preg_match( '/Mobile|Android.*Mobile|iPhone|iPod/i', $ua ) ) {
            $device_type = 'mobile';
        } elseif ( preg_match( '/iPad|Android(?!.*Mobile)|Tablet/i', $ua ) ) {
            $device_type = 'tablet';
        }

        // Strip personal data from URLs.
        $page_url     = self::sanitize_url( $data['page_url'] ?? ( $_SERVER['REQUEST_URI'] ?? '' ) );
        $referrer_url = self::sanitize_url( $data['referrer_url'] ?? ( $_SERVER['HTTP_REFERER'] ?? '' ) );

        // JSON-encode extra data.
        $event_data = null;
        $extra      = $data['extra'] ?? $data;
        unset( $extra['session_id'], $extra['page_url'], $extra['referrer_url'] );
        if ( ! empty( $extra ) ) {
            $event_data = wp_json_encode( $extra );
        }

        $wpdb->insert( $wpdb->prefix . 'oddcareco_analytics_events', [
            'session_id'   => substr( $session_id, 0, 64 ),
            'user_id'      => $user_id,
            'event_type'   => $event_type,
            'event_data'   => $event_data,
            'product_id'   => absint( $data['product_id'] ?? 0 ) ?: null,
            'page_url'     => substr( $page_url, 0, 2048 ),
            'referrer_url' => substr( $referrer_url, 0, 2048 ),
            'device_type'  => $device_type,
            'ip_hash'      => $ip_hash,
        ] );
    }

    /**
     * Strip email/name query params from URLs for privacy.
     */
    private static function sanitize_url( $url ) {
        $stripped = preg_replace( '/([?&])(email|name|user|token)=[^&]*/i', '$1_redacted_', $url );
        return $stripped ?: $url;
    }

    /* ── WooCommerce hooks ───────────────────────────────── */

    public static function on_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id ) {
        self::record_event( 'add_to_cart', [
            'product_id' => $product_id,
            'quantity'   => $quantity,
        ] );
    }

    public static function on_remove_from_cart( $cart_item_key, $cart ) {
        $item = $cart->get_cart_item( $cart_item_key );
        if ( $item ) {
            self::record_event( 'remove_from_cart', [
                'product_id' => $item['product_id'],
            ] );
        }
    }

    public static function on_checkout( $order_id, $posted_data, $order ) {
        self::record_event( 'begin_checkout', [
            'order_id' => $order_id,
            'total'    => $order->get_total(),
        ] );
    }

    public static function on_purchase( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        self::record_event( 'purchase_complete', [
            'order_id'  => $order_id,
            'total'     => $order->get_total(),
            'items'     => $order->get_item_count(),
        ] );
    }

    public static function on_coupon_applied( $coupon_code ) {
        self::record_event( 'coupon_applied', [
            'coupon_code' => $coupon_code,
        ] );
    }

    /* ── Frontend tracker ────────────────────────────────── */

    public static function enqueue_tracker() {
        if ( is_admin() ) return;

        wp_enqueue_script(
            'oddcareco-tracker',
            ODDCARECO_PLUGIN_URL . 'assets/tracker.js',
            [],
            ODDCARECO_VERSION,
            true
        );

        wp_localize_script( 'oddcareco-tracker', 'oddcarecoTracker', [
            'endpoint' => rest_url( 'oddcareco/v1/event' ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
        ] );
    }

    /* ── Cron: aggregate daily stats ─────────────────────── */

    public static function cron_aggregate() {
        global $wpdb;
        $yesterday = gmdate( 'Y-m-d', strtotime( '-1 day' ) );
        $events    = $wpdb->prefix . 'oddcareco_analytics_events';
        $daily     = $wpdb->prefix . 'oddcareco_analytics_daily';

        $wpdb->query( $wpdb->prepare(
            "INSERT INTO $daily
                (event_date, event_type, product_id, device_type, event_count, unique_sessions, unique_users)
             SELECT
                DATE(created_at) as event_date,
                event_type,
                product_id,
                device_type,
                COUNT(*) as event_count,
                COUNT(DISTINCT session_id) as unique_sessions,
                COUNT(DISTINCT user_id) as unique_users
             FROM $events
             WHERE DATE(created_at) = %s
             GROUP BY DATE(created_at), event_type, product_id, device_type
             ON DUPLICATE KEY UPDATE
                event_count = VALUES(event_count),
                unique_sessions = VALUES(unique_sessions),
                unique_users = VALUES(unique_users)",
            $yesterday
        ) );
    }

    /* ── Cron: cleanup old raw events ────────────────────── */

    public static function cron_cleanup() {
        global $wpdb;
        $settings = get_option( 'oddcareco_analytics_settings', [] );
        $days     = $settings['retention_days'] ?? 90;
        $cutoff   = gmdate( 'Y-m-d H:i:s', strtotime( "-{$days} days" ) );

        // Delete in batches to avoid long-running queries.
        $wpdb->query( $wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}oddcareco_analytics_events
             WHERE created_at < %s LIMIT 10000",
            $cutoff
        ) );
    }

    /* ── Data access for admin dashboard ─────────────────── */

    /**
     * Get aggregated event counts for a date range.
     */
    public static function get_daily_stats( $start_date, $end_date, $event_type = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_analytics_daily';

        $where = $wpdb->prepare( "WHERE event_date BETWEEN %s AND %s", $start_date, $end_date );
        if ( $event_type ) {
            $where .= $wpdb->prepare( " AND event_type = %s", $event_type );
        }

        return $wpdb->get_results(
            "SELECT event_date, event_type,
                    SUM(event_count) as total_events,
                    SUM(unique_sessions) as total_sessions,
                    SUM(unique_users) as total_users
             FROM $table $where
             GROUP BY event_date, event_type
             ORDER BY event_date ASC"
        );
    }

    /**
     * Get conversion funnel for a date range.
     */
    public static function get_funnel( $start_date, $end_date ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_analytics_daily';

        $steps = [ 'page_view', 'product_view', 'add_to_cart', 'begin_checkout', 'purchase_complete' ];
        $funnel = [];

        foreach ( $steps as $step ) {
            $count = $wpdb->get_var( $wpdb->prepare(
                "SELECT SUM(unique_sessions) FROM $table
                 WHERE event_date BETWEEN %s AND %s AND event_type = %s",
                $start_date, $end_date, $step
            ) );
            $funnel[ $step ] = (int) $count;
        }

        return $funnel;
    }

    /**
     * Get top products by event type.
     */
    public static function get_top_products( $event_type, $start_date, $end_date, $limit = 10 ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_analytics_daily';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT product_id, SUM(event_count) as total
             FROM $table
             WHERE event_type = %s AND event_date BETWEEN %s AND %s
                   AND product_id IS NOT NULL AND product_id > 0
             GROUP BY product_id ORDER BY total DESC LIMIT %d",
            $event_type, $start_date, $end_date, $limit
        ) );
    }

    /**
     * Get device breakdown for a date range.
     */
    public static function get_device_breakdown( $start_date, $end_date ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_analytics_daily';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT device_type, SUM(event_count) as total
             FROM $table
             WHERE event_date BETWEEN %s AND %s
             GROUP BY device_type ORDER BY total DESC",
            $start_date, $end_date
        ) );
    }

    /**
     * Get total events count (for admin overview).
     */
    public static function get_total_events() {
        global $wpdb;
        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}oddcareco_analytics_events"
        );
    }
}
