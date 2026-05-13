<?php
/**
 * Admin page: Wishlist Analytics.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Admin_Wishlist {

    public static function render() {
        $total_wishlists = OddCareCo_Wishlist::count_wishlists();
        $total_items     = OddCareCo_Wishlist::count_items();
        $top_products    = OddCareCo_Wishlist::get_top_products( 10 );

        include ODDCARECO_PLUGIN_DIR . 'admin/views/wishlist-dashboard.php';
    }
}
