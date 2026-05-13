<?php
/**
 * Admin page: Referral Program Management.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Admin_Referrals {

    public static function render() {
        // Handle settings save.
        if ( isset( $_POST['oddcareco_save_referral_settings'] ) && check_admin_referer( 'oddcareco_referral_settings' ) ) {
            $settings = [
                'referrer_reward_amount' => floatval( $_POST['referrer_reward_amount'] ?? 100 ),
                'referrer_reward_type'   => sanitize_text_field( $_POST['referrer_reward_type'] ?? 'fixed_cart' ),
                'referee_discount'       => floatval( $_POST['referee_discount'] ?? 10 ),
                'referee_discount_type'  => sanitize_text_field( $_POST['referee_discount_type'] ?? 'percent' ),
                'referral_cookie_days'   => absint( $_POST['referral_cookie_days'] ?? 30 ),
                'reward_min_spend'       => floatval( $_POST['reward_min_spend'] ?? 399 ),
                'reward_expiry_days'     => absint( $_POST['reward_expiry_days'] ?? 90 ),
                'pending_expiry_days'    => absint( $_POST['pending_expiry_days'] ?? 60 ),
            ];
            update_option( 'oddcareco_referral_settings', $settings );
            echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
        }

        $stats         = OddCareCo_Referrals::get_stats_summary();
        $top_referrers = OddCareCo_Referrals::get_top_referrers( 10 );
        $recent        = OddCareCo_Referrals::get_recent_referrals( 20 );
        $settings      = get_option( 'oddcareco_referral_settings', [] );

        include ODDCARECO_PLUGIN_DIR . 'admin/views/referrals-dashboard.php';
    }
}
