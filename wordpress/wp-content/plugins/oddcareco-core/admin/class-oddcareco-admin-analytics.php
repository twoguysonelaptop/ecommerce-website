<?php
/**
 * Admin page: Analytics Dashboard.
 *
 * Displays conversion funnel, revenue over time, top products,
 * device breakdown with Chart.js visualizations.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Admin_Analytics {

    public static function render() {
        $end   = gmdate( 'Y-m-d' );
        $start = gmdate( 'Y-m-d', strtotime( '-30 days' ) );

        if ( ! empty( $_GET['start_date'] ) ) {
            $start = sanitize_text_field( $_GET['start_date'] );
        }
        if ( ! empty( $_GET['end_date'] ) ) {
            $end = sanitize_text_field( $_GET['end_date'] );
        }

        $funnel    = OddCareCo_Analytics::get_funnel( $start, $end );
        $devices   = OddCareCo_Analytics::get_device_breakdown( $start, $end );
        $top_views = OddCareCo_Analytics::get_top_products( 'product_view', $start, $end, 5 );
        $top_carts = OddCareCo_Analytics::get_top_products( 'add_to_cart', $start, $end, 5 );
        $daily     = OddCareCo_Analytics::get_daily_stats( $start, $end );

        include ODDCARECO_PLUGIN_DIR . 'admin/views/analytics-dashboard.php';
    }
}
