<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap oddcareco-admin">
    <h1>Analytics Dashboard</h1>

    <!-- Date range picker -->
    <form method="get" class="oddcareco-date-range">
        <input type="hidden" name="page" value="oddcareco">
        <label>From <input type="date" name="start_date" value="<?php echo esc_attr( $start ); ?>"></label>
        <label>To <input type="date" name="end_date" value="<?php echo esc_attr( $end ); ?>"></label>
        <button type="submit" class="button button-primary">Apply</button>
    </form>

    <!-- Conversion Funnel -->
    <div class="oddcareco-card">
        <h2>Conversion Funnel</h2>
        <div class="oddcareco-funnel" id="funnel-chart">
            <?php
            $labels = [
                'page_view'        => 'Page Views',
                'product_view'     => 'Product Views',
                'add_to_cart'      => 'Add to Cart',
                'begin_checkout'   => 'Checkout',
                'purchase_complete' => 'Purchase',
            ];
            $max_val = max( array_values( $funnel ) ) ?: 1;
            foreach ( $funnel as $step => $count ) :
                $pct = round( ( $count / $max_val ) * 100 );
            ?>
                <div class="funnel-step">
                    <div class="funnel-bar" style="width:<?php echo $pct; ?>%"></div>
                    <span class="funnel-label"><?php echo esc_html( $labels[ $step ] ?? $step ); ?></span>
                    <span class="funnel-value"><?php echo number_format( $count ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="oddcareco-grid-2">
        <!-- Top Viewed Products -->
        <div class="oddcareco-card">
            <h2>Top Viewed Products</h2>
            <?php if ( empty( $top_views ) ) : ?>
                <p class="oddcareco-empty">No data yet.</p>
            <?php else : ?>
                <table class="oddcareco-table">
                    <thead><tr><th>Product</th><th>Views</th></tr></thead>
                    <tbody>
                    <?php foreach ( $top_views as $row ) :
                        $product = wc_get_product( $row->product_id );
                    ?>
                        <tr>
                            <td><?php echo $product ? esc_html( $product->get_name() ) : '#' . $row->product_id; ?></td>
                            <td><?php echo number_format( $row->total ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Top Added to Cart -->
        <div class="oddcareco-card">
            <h2>Top Added to Cart</h2>
            <?php if ( empty( $top_carts ) ) : ?>
                <p class="oddcareco-empty">No data yet.</p>
            <?php else : ?>
                <table class="oddcareco-table">
                    <thead><tr><th>Product</th><th>Adds</th></tr></thead>
                    <tbody>
                    <?php foreach ( $top_carts as $row ) :
                        $product = wc_get_product( $row->product_id );
                    ?>
                        <tr>
                            <td><?php echo $product ? esc_html( $product->get_name() ) : '#' . $row->product_id; ?></td>
                            <td><?php echo number_format( $row->total ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Device Breakdown -->
    <div class="oddcareco-card">
        <h2>Device Breakdown</h2>
        <?php if ( empty( $devices ) ) : ?>
            <p class="oddcareco-empty">No data yet.</p>
        <?php else : ?>
            <div class="oddcareco-device-grid">
                <?php foreach ( $devices as $d ) : ?>
                    <div class="device-item">
                        <span class="device-type"><?php echo esc_html( ucfirst( $d->device_type ?: 'unknown' ) ); ?></span>
                        <span class="device-count"><?php echo number_format( $d->total ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
