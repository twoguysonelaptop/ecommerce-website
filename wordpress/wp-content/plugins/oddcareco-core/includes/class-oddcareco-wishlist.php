<?php
/**
 * Wishlist feature — CRUD, AJAX toggle, My Account tab, share-by-link.
 */

defined( 'ABSPATH' ) || exit;

class OddCareCo_Wishlist {

    public static function init() {
        // AJAX handlers (logged-in users only).
        add_action( 'wp_ajax_oddcareco_wishlist_toggle', [ __CLASS__, 'ajax_toggle' ] );

        // Add heart icon after Add to Cart button.
        add_action( 'woocommerce_after_add_to_cart_button', [ __CLASS__, 'render_heart_button' ] );

        // Add "Wishlist" tab to My Account.
        add_filter( 'woocommerce_account_menu_items', [ __CLASS__, 'add_account_tab' ], 20 );
        add_action( 'init', [ __CLASS__, 'register_endpoint' ] );
        add_action( 'woocommerce_account_wishlist_endpoint', [ __CLASS__, 'render_account_page' ] );

        // Shared wishlist shortcode for public links.
        add_action( 'template_redirect', [ __CLASS__, 'handle_shared_wishlist' ] );

        // Enqueue frontend JS/CSS on product and account pages.
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    /* ── CRUD ────────────────────────────────────────────── */

    /**
     * Get or create the default wishlist for a user.
     */
    public static function get_default_wishlist( $user_id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_wishlists';

        $wishlist = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY id ASC LIMIT 1",
            $user_id
        ) );

        if ( ! $wishlist ) {
            $share_key = wp_generate_password( 32, false );
            $wpdb->insert( $table, [
                'user_id'   => $user_id,
                'title'     => 'My Wishlist',
                'share_key' => $share_key,
            ]);
            $wishlist = $wpdb->get_row( $wpdb->prepare(
                "SELECT * FROM $table WHERE id = %d", $wpdb->insert_id
            ) );
        }

        return $wishlist;
    }

    /**
     * Get all items in a wishlist.
     */
    public static function get_items( $wishlist_id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_wishlist_items';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table WHERE wishlist_id = %d ORDER BY added_at DESC",
            $wishlist_id
        ) );
    }

    /**
     * Check if a product is in the user's wishlist.
     */
    public static function has_product( $user_id, $product_id ) {
        global $wpdb;
        $wishlists_table = $wpdb->prefix . 'oddcareco_wishlists';
        $items_table     = $wpdb->prefix . 'oddcareco_wishlist_items';

        return (bool) $wpdb->get_var( $wpdb->prepare(
            "SELECT i.id FROM $items_table i
             JOIN $wishlists_table w ON w.id = i.wishlist_id
             WHERE w.user_id = %d AND i.product_id = %d LIMIT 1",
            $user_id, $product_id
        ) );
    }

    /**
     * Add a product to the user's default wishlist.
     */
    public static function add_product( $user_id, $product_id ) {
        global $wpdb;
        $wishlist = self::get_default_wishlist( $user_id );
        $table    = $wpdb->prefix . 'oddcareco_wishlist_items';

        // Prevent duplicates.
        if ( self::has_product( $user_id, $product_id ) ) {
            return false;
        }

        $wpdb->insert( $table, [
            'wishlist_id' => $wishlist->id,
            'product_id'  => $product_id,
        ]);

        // Track analytics event.
        if ( class_exists( 'OddCareCo_Analytics' ) ) {
            OddCareCo_Analytics::record_event( 'wishlist_add', [ 'product_id' => $product_id ] );
        }

        return true;
    }

    /**
     * Remove a product from the user's wishlist.
     */
    public static function remove_product( $user_id, $product_id ) {
        global $wpdb;
        $wishlist = self::get_default_wishlist( $user_id );
        $table    = $wpdb->prefix . 'oddcareco_wishlist_items';

        $deleted = $wpdb->delete( $table, [
            'wishlist_id' => $wishlist->id,
            'product_id'  => $product_id,
        ]);

        if ( $deleted && class_exists( 'OddCareCo_Analytics' ) ) {
            OddCareCo_Analytics::record_event( 'wishlist_remove', [ 'product_id' => $product_id ] );
        }

        return (bool) $deleted;
    }

    /* ── AJAX ────────────────────────────────────────────── */

    /**
     * Toggle product in/out of wishlist via AJAX.
     */
    public static function ajax_toggle() {
        check_ajax_referer( 'oddcareco_wishlist', 'nonce' );

        $product_id = absint( $_POST['product_id'] ?? 0 );
        if ( ! $product_id ) {
            wp_send_json_error( 'Invalid product.' );
        }

        $user_id = get_current_user_id();
        if ( ! $user_id ) {
            wp_send_json_error( 'Please log in to use wishlists.' );
        }

        if ( self::has_product( $user_id, $product_id ) ) {
            self::remove_product( $user_id, $product_id );
            wp_send_json_success( [ 'action' => 'removed', 'in_wishlist' => false ] );
        } else {
            self::add_product( $user_id, $product_id );
            wp_send_json_success( [ 'action' => 'added', 'in_wishlist' => true ] );
        }
    }

    /* ── Frontend rendering ──────────────────────────────── */

    /**
     * Render heart icon button on product pages.
     */
    public static function render_heart_button() {
        global $product;
        if ( ! $product ) return;

        $in_wishlist = is_user_logged_in()
            ? self::has_product( get_current_user_id(), $product->get_id() )
            : false;

        $class = $in_wishlist ? 'oddcareco-heart active' : 'oddcareco-heart';

        printf(
            '<button type="button" class="%s" data-product-id="%d" aria-label="Toggle wishlist">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="%s" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </button>',
            esc_attr( $class ),
            esc_attr( $product->get_id() ),
            $in_wishlist ? 'currentColor' : 'none'
        );
    }

    /**
     * Enqueue wishlist JS and inline CSS on relevant pages.
     */
    public static function enqueue_assets() {
        if ( ! is_product() && ! is_account_page() ) return;

        wp_enqueue_style( 'oddcareco-wishlist', false );
        wp_add_inline_style( 'oddcareco-wishlist', '
            .oddcareco-heart {
                background: none; border: 1px solid #e5e5e5; border-radius: 50%;
                width: 42px; height: 42px; cursor: pointer; display: inline-flex;
                align-items: center; justify-content: center; margin-left: 10px;
                transition: all 0.2s ease; color: #999; vertical-align: middle;
            }
            .oddcareco-heart:hover { border-color: #9CAF88; color: #9CAF88; }
            .oddcareco-heart.active { border-color: #9CAF88; color: #9CAF88; }
            .oddcareco-heart.active svg { fill: #9CAF88; }
        ' );

        if ( is_product() && is_user_logged_in() ) {
            wp_enqueue_script( 'oddcareco-wishlist-js', false, [], false, true );
            wp_add_inline_script( 'oddcareco-wishlist-js', '
                document.addEventListener("click", function(e) {
                    var btn = e.target.closest(".oddcareco-heart");
                    if (!btn) return;
                    e.preventDefault();
                    var productId = btn.dataset.productId;
                    var formData = new FormData();
                    formData.append("action", "oddcareco_wishlist_toggle");
                    formData.append("product_id", productId);
                    formData.append("nonce", "' . wp_create_nonce( 'oddcareco_wishlist' ) . '");
                    fetch("' . admin_url( 'admin-ajax.php' ) . '", {
                        method: "POST", body: formData
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.success) {
                            btn.classList.toggle("active");
                            var svg = btn.querySelector("svg path");
                            if (data.data.in_wishlist) {
                                svg.setAttribute("fill", "currentColor");
                            } else {
                                svg.setAttribute("fill", "none");
                            }
                        }
                    });
                });
            ' );
        }
    }

    /* ── My Account tab ──────────────────────────────────── */

    public static function register_endpoint() {
        add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    }

    public static function add_account_tab( $items ) {
        $new_items = [];
        foreach ( $items as $key => $label ) {
            $new_items[ $key ] = $label;
            if ( $key === 'orders' ) {
                $new_items['wishlist'] = 'Wishlist';
            }
        }
        return $new_items;
    }

    public static function render_account_page() {
        $user_id  = get_current_user_id();
        $wishlist = self::get_default_wishlist( $user_id );
        $items    = self::get_items( $wishlist->id );

        echo '<h3>My Wishlist</h3>';

        if ( empty( $items ) ) {
            echo '<p style="color:#888;">Your wishlist is empty. Browse products and tap the heart to save them here.</p>';
            return;
        }

        // Share link.
        if ( $wishlist->share_key ) {
            $share_url = add_query_arg( 'wishlist', $wishlist->share_key, home_url( '/' ) );
            echo '<p style="font-size:13px;color:#888;margin-bottom:1.5rem;">Share link: <input type="text" value="' . esc_url( $share_url ) . '" readonly onclick="this.select()" style="width:320px;font-size:12px;padding:4px 8px;border:1px solid #e5e5e5;border-radius:6px;"></p>';
        }

        echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">';
        foreach ( $items as $item ) {
            $product = wc_get_product( $item->product_id );
            if ( ! $product ) continue;

            echo '<div style="border:1px solid #e5e5e5;border-radius:12px;padding:1rem;text-align:center;">';
            echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
            echo $product->get_image( 'woocommerce_thumbnail', [ 'style' => 'border-radius:8px;max-width:100%;' ] );
            echo '<p style="font-weight:600;margin:0.5rem 0 0.25rem;color:#1a1a1a;">' . esc_html( $product->get_name() ) . '</p>';
            echo '</a>';
            echo '<p style="color:#9CAF88;font-weight:600;">' . $product->get_price_html() . '</p>';
            echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button" style="font-size:12px;">Add to Cart</a>';
            echo '</div>';
        }
        echo '</div>';
    }

    /* ── Shared wishlist (public link) ───────────────────── */

    public static function handle_shared_wishlist() {
        $key = sanitize_text_field( $_GET['wishlist'] ?? '' );
        if ( ! $key ) return;

        global $wpdb;
        $table    = $wpdb->prefix . 'oddcareco_wishlists';
        $wishlist = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE share_key = %s AND is_public = 1",
            $key
        ) );

        if ( ! $wishlist ) return;

        // Let WordPress handle it normally — the shortcode/template will render.
        // For now, set a global so the theme can pick it up.
        $GLOBALS['oddcareco_shared_wishlist'] = $wishlist;
    }

    /* ── Data access for admin/privacy ───────────────────── */

    /**
     * Get most-wishlisted products for admin dashboard.
     */
    public static function get_top_products( $limit = 10 ) {
        global $wpdb;
        $table = $wpdb->prefix . 'oddcareco_wishlist_items';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT product_id, COUNT(*) as wish_count
             FROM $table GROUP BY product_id
             ORDER BY wish_count DESC LIMIT %d",
            $limit
        ) );
    }

    /**
     * Count total wishlists.
     */
    public static function count_wishlists() {
        global $wpdb;
        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}oddcareco_wishlists"
        );
    }

    /**
     * Count total wishlist items.
     */
    public static function count_items() {
        global $wpdb;
        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->prefix}oddcareco_wishlist_items"
        );
    }
}
