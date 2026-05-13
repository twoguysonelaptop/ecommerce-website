<?php
/**
 * GDPR privacy handlers — data exporter and eraser.
 *
 * Registers with WordPress privacy tools so user data from
 * wishlists, referrals, and analytics can be exported or deleted.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Privacy {

    public static function init() {
        add_filter( 'wp_privacy_personal_data_exporters', [ __CLASS__, 'register_exporters' ] );
        add_filter( 'wp_privacy_personal_data_erasers', [ __CLASS__, 'register_erasers' ] );
    }

    /* ── Register ────────────────────────────────────────── */

    public static function register_exporters( $exporters ) {
        $exporters['oddcareco-wishlists'] = [
            'exporter_friendly_name' => 'ODD Care Co Wishlists',
            'callback'               => [ __CLASS__, 'export_wishlists' ],
        ];
        $exporters['oddcareco-referrals'] = [
            'exporter_friendly_name' => 'ODD Care Co Referrals',
            'callback'               => [ __CLASS__, 'export_referrals' ],
        ];
        $exporters['oddcareco-analytics'] = [
            'exporter_friendly_name' => 'ODD Care Co Analytics',
            'callback'               => [ __CLASS__, 'export_analytics' ],
        ];
        return $exporters;
    }

    public static function register_erasers( $erasers ) {
        $erasers['oddcareco-all'] = [
            'eraser_friendly_name' => 'ODD Care Co Data',
            'callback'             => [ __CLASS__, 'erase_user_data' ],
        ];
        return $erasers;
    }

    /* ── Exporters ───────────────────────────────────────── */

    public static function export_wishlists( $email, $page = 1 ) {
        global $wpdb;
        $user = get_user_by( 'email', $email );
        if ( ! $user ) return [ 'data' => [], 'done' => true ];

        $items = $wpdb->get_results( $wpdb->prepare(
            "SELECT i.product_id, i.added_at, i.note
             FROM {$wpdb->prefix}oddcareco_wishlist_items i
             JOIN {$wpdb->prefix}oddcareco_wishlists w ON w.id = i.wishlist_id
             WHERE w.user_id = %d",
            $user->ID
        ) );

        $data = [];
        foreach ( $items as $item ) {
            $product = wc_get_product( $item->product_id );
            $data[] = [
                'group_id'    => 'oddcareco-wishlists',
                'group_label' => 'Wishlists',
                'item_id'     => 'wishlist-item-' . $item->product_id,
                'data'        => [
                    [ 'name' => 'Product', 'value' => $product ? $product->get_name() : '#' . $item->product_id ],
                    [ 'name' => 'Added', 'value' => $item->added_at ],
                    [ 'name' => 'Note', 'value' => $item->note ?: '' ],
                ],
            ];
        }

        return [ 'data' => $data, 'done' => true ];
    }

    public static function export_referrals( $email, $page = 1 ) {
        global $wpdb;
        $user = get_user_by( 'email', $email );
        if ( ! $user ) return [ 'data' => [], 'done' => true ];

        $code = get_user_meta( $user->ID, '_oddcareco_referral_code', true );
        $referrals = $wpdb->get_results( $wpdb->prepare(
            "SELECT referral_code, status, created_at, completed_at, reward_value
             FROM {$wpdb->prefix}oddcareco_referrals
             WHERE referrer_user_id = %d OR referee_user_id = %d",
            $user->ID, $user->ID
        ) );

        $data = [];
        if ( $code ) {
            $data[] = [
                'group_id'    => 'oddcareco-referrals',
                'group_label' => 'Referral Program',
                'item_id'     => 'referral-code',
                'data'        => [
                    [ 'name' => 'Your Referral Code', 'value' => $code ],
                ],
            ];
        }
        foreach ( $referrals as $ref ) {
            $data[] = [
                'group_id'    => 'oddcareco-referrals',
                'group_label' => 'Referral Program',
                'item_id'     => 'referral-' . $ref->referral_code,
                'data'        => [
                    [ 'name' => 'Code', 'value' => $ref->referral_code ],
                    [ 'name' => 'Status', 'value' => $ref->status ],
                    [ 'name' => 'Created', 'value' => $ref->created_at ],
                    [ 'name' => 'Completed', 'value' => $ref->completed_at ?: 'N/A' ],
                    [ 'name' => 'Reward', 'value' => $ref->reward_value ? '₹' . $ref->reward_value : 'N/A' ],
                ],
            ];
        }

        return [ 'data' => $data, 'done' => true ];
    }

    public static function export_analytics( $email, $page = 1 ) {
        global $wpdb;
        $user = get_user_by( 'email', $email );
        if ( ! $user ) return [ 'data' => [], 'done' => true ];

        $events = $wpdb->get_results( $wpdb->prepare(
            "SELECT event_type, product_id, page_url, created_at
             FROM {$wpdb->prefix}oddcareco_analytics_events
             WHERE user_id = %d ORDER BY created_at DESC LIMIT 100",
            $user->ID
        ) );

        $data = [];
        foreach ( $events as $i => $event ) {
            $data[] = [
                'group_id'    => 'oddcareco-analytics',
                'group_label' => 'Analytics Events',
                'item_id'     => 'event-' . $i,
                'data'        => [
                    [ 'name' => 'Event', 'value' => $event->event_type ],
                    [ 'name' => 'Product ID', 'value' => $event->product_id ?: 'N/A' ],
                    [ 'name' => 'Page', 'value' => $event->page_url ?: '' ],
                    [ 'name' => 'Date', 'value' => $event->created_at ],
                ],
            ];
        }

        return [ 'data' => $data, 'done' => true ];
    }

    /* ── Eraser ──────────────────────────────────────────── */

    public static function erase_user_data( $email, $page = 1 ) {
        global $wpdb;
        $user = get_user_by( 'email', $email );
        if ( ! $user ) {
            return [ 'items_removed' => 0, 'items_retained' => 0, 'messages' => [], 'done' => true ];
        }

        $uid     = $user->ID;
        $removed = 0;

        // Wishlist items + wishlists.
        $wishlist_ids = $wpdb->get_col( $wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}oddcareco_wishlists WHERE user_id = %d", $uid
        ) );
        if ( $wishlist_ids ) {
            $placeholders = implode( ',', array_fill( 0, count( $wishlist_ids ), '%d' ) );
            $removed += $wpdb->query( $wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}oddcareco_wishlist_items WHERE wishlist_id IN ($placeholders)",
                ...$wishlist_ids
            ) );
        }
        $removed += $wpdb->delete( $wpdb->prefix . 'oddcareco_wishlists', [ 'user_id' => $uid ] );

        // Referral stats.
        $removed += $wpdb->delete( $wpdb->prefix . 'oddcareco_referral_stats', [ 'user_id' => $uid ] );

        // Anonymize referrals (don't delete — needed for auditing, but remove PII).
        $wpdb->query( $wpdb->prepare(
            "UPDATE {$wpdb->prefix}oddcareco_referrals
             SET referee_email = NULL WHERE referee_user_id = %d",
            $uid
        ) );

        // Analytics events.
        $removed += $wpdb->delete( $wpdb->prefix . 'oddcareco_analytics_events', [ 'user_id' => $uid ] );

        // Referral code usermeta.
        delete_user_meta( $uid, '_oddcareco_referral_code' );
        $removed++;

        return [
            'items_removed'  => $removed,
            'items_retained' => 0,
            'messages'       => [],
            'done'           => true,
        ];
    }
}
