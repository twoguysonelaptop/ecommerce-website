<?php
/**
 * Referral code system — generation, cookie tracking, rewards.
 *
 * Flow: ?ref=ODD-XXXXXX → cookie → signup → first purchase → reward coupon.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Referrals {

    const COOKIE_NAME = 'oddcareco_ref';

    public static function init() {
        // Capture referral code from URL and set cookie.
        add_action( 'template_redirect', [ __CLASS__, 'capture_referral_code' ] );

        // Generate referral code for new users.
        add_action( 'woocommerce_created_customer', [ __CLASS__, 'on_customer_created' ], 10, 3 );
        add_action( 'wp_login', [ __CLASS__, 'ensure_referral_code' ], 10, 2 );

        // Track referral on first purchase.
        add_action( 'woocommerce_payment_complete', [ __CLASS__, 'on_payment_complete' ] );

        // Add referral section to My Account dashboard.
        add_action( 'woocommerce_account_dashboard', [ __CLASS__, 'render_account_section' ], 15 );

        // Referral expiry cron.
        add_action( 'oddcareco_referral_expiry', [ __CLASS__, 'expire_pending_referrals' ] );
    }

    /* ── Code generation ─────────────────────────────────── */

    /**
     * Generate a unique ODD-XXXXXX referral code.
     */
    public static function generate_code() {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referrals';

        do {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // No 0/O/1/I to avoid confusion.
            $code  = 'ODD-';
            for ( $i = 0; $i < 6; $i++ ) {
                $code .= $chars[ wp_rand( 0, strlen( $chars ) - 1 ) ];
            }
            $exists = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM $table WHERE referral_code = %s", $code
            ) );
        } while ( $exists );

        return $code;
    }

    /**
     * Get or create a referral code for a user.
     */
    public static function get_user_code( $user_id ) {
        $code = get_user_meta( $user_id, '_oddcareco_referral_code', true );

        if ( ! $code ) {
            $code = self::generate_code();
            update_user_meta( $user_id, '_oddcareco_referral_code', $code );
        }

        return $code;
    }

    /* ── URL capture + cookie ────────────────────────────── */

    /**
     * Capture ?ref= parameter and store in cookie.
     */
    public static function capture_referral_code() {
        $ref = sanitize_text_field( $_GET['ref'] ?? '' );
        if ( ! $ref || ! preg_match( '/^ODD-[A-Z0-9]{6}$/', $ref ) ) return;

        // Validate code exists.
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referrals';
        $referrer_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '_oddcareco_referral_code' AND meta_value = %s",
            $ref
        ) );

        if ( ! $referrer_id ) return;

        // Don't set cookie for the referrer themselves.
        if ( is_user_logged_in() && get_current_user_id() == $referrer_id ) return;

        $settings = get_option( 'oddcareco_referral_settings', [] );
        $days     = $settings['referral_cookie_days'] ?? 30;

        setcookie( self::COOKIE_NAME, $ref, time() + ( $days * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
        $_COOKIE[ self::COOKIE_NAME ] = $ref;

        // Track analytics event.
        if ( class_exists( 'OddCareCo_Analytics' ) ) {
            OddCareCo_Analytics::record_event( 'referral_link_click', [
                'referral_code' => $ref,
                'referrer_user_id' => $referrer_id,
            ] );
        }
    }

    /* ── Customer creation hook ──────────────────────────── */

    /**
     * When a new customer registers, assign them a referral code
     * and link them to the referrer if a cookie exists.
     */
    public static function on_customer_created( $customer_id, $new_customer_data, $password_generated ) {
        // Generate referral code for the new user.
        self::get_user_code( $customer_id );

        // Check if they were referred.
        $ref_code = $_COOKIE[ self::COOKIE_NAME ] ?? '';
        if ( ! $ref_code ) return;

        $referrer_id = self::get_referrer_by_code( $ref_code );
        if ( ! $referrer_id || $referrer_id == $customer_id ) return;

        // Create pending referral.
        global $wpdb;
        $wpdb->insert( $wpdb->prefix . 'oddcareco_referrals', [
            'referrer_user_id' => $referrer_id,
            'referral_code'    => $ref_code,
            'referee_user_id'  => $customer_id,
            'referee_email'    => $new_customer_data['user_email'] ?? '',
            'status'           => 'pending',
        ] );

        // Update stats.
        self::increment_stat( $referrer_id, 'total_referrals' );
    }

    /**
     * Ensure existing users get a referral code on login.
     */
    public static function ensure_referral_code( $user_login, $user ) {
        self::get_user_code( $user->ID );
    }

    /* ── Payment complete → reward ───────────────────────── */

    /**
     * On first purchase by a referred user, mark referral as completed and issue rewards.
     */
    public static function on_payment_complete( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        $customer_id = $order->get_customer_id();
        if ( ! $customer_id ) return;

        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referrals';

        // Find pending referral for this user.
        $referral = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE referee_user_id = %d AND status = 'pending' LIMIT 1",
            $customer_id
        ) );

        if ( ! $referral ) return;

        $settings = get_option( 'oddcareco_referral_settings', [] );

        // Update referral to completed.
        $wpdb->update( $table, [
            'status'           => 'completed',
            'referee_order_id' => $order_id,
            'completed_at'     => current_time( 'mysql' ),
        ], [ 'id' => $referral->id ] );

        // Issue reward coupon to referrer.
        $coupon_id = self::create_reward_coupon( $referral->referrer_user_id, $settings );

        if ( $coupon_id ) {
            $reward_amount = $settings['referrer_reward_amount'] ?? 100;
            $wpdb->update( $table, [
                'status'          => 'rewarded',
                'reward_type'     => $settings['referrer_reward_type'] ?? 'fixed_cart',
                'reward_value'    => $reward_amount,
                'reward_coupon_id' => $coupon_id,
                'rewarded_at'     => current_time( 'mysql' ),
            ], [ 'id' => $referral->id ] );

            // Update stats.
            self::increment_stat( $referral->referrer_user_id, 'completed_referrals' );
            self::increment_reward( $referral->referrer_user_id, $reward_amount );
        }

        // Clear the referral cookie.
        setcookie( self::COOKIE_NAME, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
    }

    /**
     * Create a WooCommerce coupon as the referrer's reward.
     */
    private static function create_reward_coupon( $referrer_id, $settings ) {
        $amount    = $settings['referrer_reward_amount'] ?? 100;
        $type      = $settings['referrer_reward_type'] ?? 'fixed_cart';
        $min_spend = $settings['reward_min_spend'] ?? 399;
        $expiry    = $settings['reward_expiry_days'] ?? 90;

        $code = 'REF-' . $referrer_id . '-' . time();

        $coupon = new \WC_Coupon();
        $coupon->set_code( $code );
        $coupon->set_discount_type( $type );
        $coupon->set_amount( $amount );
        $coupon->set_individual_use( true );
        $coupon->set_usage_limit( 1 );
        $coupon->set_minimum_amount( $min_spend );
        $coupon->set_date_expires( strtotime( "+{$expiry} days" ) );
        $coupon->set_email_restrictions( [ get_userdata( $referrer_id )->user_email ] );
        $coupon->save();

        return $coupon->get_id();
    }

    /* ── Helpers ──────────────────────────────────────────── */

    private static function get_referrer_by_code( $code ) {
        global $wpdb;
        return (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT user_id FROM {$wpdb->usermeta}
             WHERE meta_key = '_oddcareco_referral_code' AND meta_value = %s",
            $code
        ) );
    }

    private static function increment_stat( $user_id, $column ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referral_stats';

        $exists = $wpdb->get_var( $wpdb->prepare(
            "SELECT user_id FROM $table WHERE user_id = %d", $user_id
        ) );

        if ( $exists ) {
            $wpdb->query( $wpdb->prepare(
                "UPDATE $table SET {$column} = {$column} + 1, last_referral_at = %s WHERE user_id = %d",
                current_time( 'mysql' ), $user_id
            ) );
        } else {
            $data = [
                'user_id'              => $user_id,
                'total_referrals'      => 0,
                'completed_referrals'  => 0,
                'total_reward_value'   => 0,
                'last_referral_at'     => current_time( 'mysql' ),
            ];
            $data[ $column ] = 1;
            $wpdb->insert( $table, $data );
        }
    }

    private static function increment_reward( $user_id, $amount ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referral_stats';
        $wpdb->query( $wpdb->prepare(
            "UPDATE $table SET total_reward_value = total_reward_value + %f WHERE user_id = %d",
            $amount, $user_id
        ) );
    }

    /* ── Cron: expire old pending referrals ───────────────── */

    public static function expire_pending_referrals() {
        global $wpdb;
        $settings = get_option( 'oddcareco_referral_settings', [] );
        $days     = $settings['pending_expiry_days'] ?? 60;
        $cutoff   = gmdate( 'Y-m-d H:i:s', strtotime( "-{$days} days" ) );

        $wpdb->query( $wpdb->prepare(
            "UPDATE {$wpdb->prefix}oddcareco_referrals
             SET status = 'expired' WHERE status = 'pending' AND created_at < %s",
            $cutoff
        ) );
    }

    /* ── My Account section ──────────────────────────────── */

    public static function render_account_section() {
        $user_id = get_current_user_id();
        $code    = self::get_user_code( $user_id );
        $url     = add_query_arg( 'ref', $code, home_url( '/' ) );

        echo '<div style="margin-top:2rem;padding:1.5rem;border:1px solid #e5e5e5;border-radius:12px;background:#fafaf9;">';
        echo '<p style="font-size:10px;text-transform:uppercase;letter-spacing:0.16em;color:#9CAF88;font-weight:500;margin-bottom:0.5rem;">Your Referral Code</p>';
        echo '<p style="font-size:22px;font-weight:700;letter-spacing:0.08em;color:#1a1a1a;margin-bottom:0.5rem;">' . esc_html( $code ) . '</p>';
        echo '<p style="font-size:13px;color:#888;margin-bottom:0.75rem;">Share this link with friends. When they make their first purchase, you both get rewarded.</p>';
        echo '<input type="text" value="' . esc_url( $url ) . '" readonly onclick="this.select()" style="width:100%;max-width:400px;font-size:13px;padding:8px 12px;border:1px solid #e5e5e5;border-radius:8px;background:#fff;">';
        echo '</div>';
    }

    /* ── Data access for admin ────────────────────────────── */

    public static function get_stats_summary() {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referrals';

        return [
            'total'     => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" ),
            'pending'   => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'pending'" ),
            'completed' => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'completed'" ),
            'rewarded'  => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'rewarded'" ),
            'expired'   => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'expired'" ),
        ];
    }

    public static function get_top_referrers( $limit = 10 ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referral_stats';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT s.*, u.display_name, u.user_email
             FROM $table s JOIN {$wpdb->users} u ON u.ID = s.user_id
             ORDER BY s.completed_referrals DESC LIMIT %d",
            $limit
        ) );
    }

    public static function get_recent_referrals( $limit = 20 ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_referrals';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT r.*,
                    referrer.display_name as referrer_name,
                    referee.display_name as referee_name
             FROM $table r
             LEFT JOIN {$wpdb->users} referrer ON referrer.ID = r.referrer_user_id
             LEFT JOIN {$wpdb->users} referee ON referee.ID = r.referee_user_id
             ORDER BY r.created_at DESC LIMIT %d",
            $limit
        ) );
    }
}
