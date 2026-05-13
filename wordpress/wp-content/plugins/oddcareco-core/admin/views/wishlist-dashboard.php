<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap oddcareco-admin">
    <h1>Wishlist Analytics</h1>

    <div class="oddcareco-grid-3">
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo number_format( $total_wishlists ); ?></span>
            <span class="stat-label">Total Wishlists</span>
        </div>
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo number_format( $total_items ); ?></span>
            <span class="stat-label">Total Items Saved</span>
        </div>
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo $total_wishlists ? round( $total_items / $total_wishlists, 1 ) : 0; ?></span>
            <span class="stat-label">Avg Items per Wishlist</span>
        </div>
    </div>

    <div class="oddcareco-card">
        <h2>Most Wishlisted Products</h2>
        <?php if ( empty( $top_products ) ) : ?>
            <p class="oddcareco-empty">No wishlist data yet. Products will appear here once customers start adding items to their wishlists.</p>
        <?php else : ?>
            <table class="oddcareco-table">
                <thead>
                    <tr><th>Product</th><th>SKU</th><th>Price</th><th>Times Wishlisted</th></tr>
                </thead>
                <tbody>
                <?php foreach ( $top_products as $row ) :
                    $product = wc_get_product( $row->product_id );
                ?>
                    <tr>
                        <td><?php echo $product ? esc_html( $product->get_name() ) : '#' . $row->product_id; ?></td>
                        <td><?php echo $product ? esc_html( $product->get_sku() ) : ''; ?></td>
                        <td><?php echo $product ? $product->get_price_html() : ''; ?></td>
                        <td><strong><?php echo number_format( $row->wish_count ); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
