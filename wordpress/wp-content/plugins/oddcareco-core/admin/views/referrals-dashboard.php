<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap oddcareco-admin">
    <h1>Referral Program</h1>

    <!-- Stats overview -->
    <div class="oddcareco-grid-4">
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo number_format( $stats['total'] ); ?></span>
            <span class="stat-label">Total Referrals</span>
        </div>
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo number_format( $stats['pending'] ); ?></span>
            <span class="stat-label">Pending</span>
        </div>
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo number_format( $stats['rewarded'] ); ?></span>
            <span class="stat-label">Rewarded</span>
        </div>
        <div class="oddcareco-stat-card">
            <span class="stat-number"><?php echo number_format( $stats['expired'] ); ?></span>
            <span class="stat-label">Expired</span>
        </div>
    </div>

    <div class="oddcareco-grid-2">
        <!-- Top Referrers -->
        <div class="oddcareco-card">
            <h2>Top Referrers</h2>
            <?php if ( empty( $top_referrers ) ) : ?>
                <p class="oddcareco-empty">No referrals yet.</p>
            <?php else : ?>
                <table class="oddcareco-table">
                    <thead><tr><th>User</th><th>Referrals</th><th>Completed</th><th>Earned</th></tr></thead>
                    <tbody>
                    <?php foreach ( $top_referrers as $r ) : ?>
                        <tr>
                            <td><?php echo esc_html( $r->display_name ); ?><br><small style="color:#888;"><?php echo esc_html( $r->user_email ); ?></small></td>
                            <td><?php echo $r->total_referrals; ?></td>
                            <td><?php echo $r->completed_referrals; ?></td>
                            <td><?php echo '₹' . number_format( $r->total_reward_value ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Recent Activity -->
        <div class="oddcareco-card">
            <h2>Recent Referrals</h2>
            <?php if ( empty( $recent ) ) : ?>
                <p class="oddcareco-empty">No referrals yet.</p>
            <?php else : ?>
                <table class="oddcareco-table">
                    <thead><tr><th>Referrer</th><th>Referee</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php foreach ( $recent as $r ) : ?>
                        <tr>
                            <td><?php echo esc_html( $r->referrer_name ?: 'Unknown' ); ?></td>
                            <td><?php echo esc_html( $r->referee_name ?: 'Pending signup' ); ?></td>
                            <td><span class="oddcareco-badge badge-<?php echo esc_attr( $r->status ); ?>"><?php echo esc_html( ucfirst( $r->status ) ); ?></span></td>
                            <td><?php echo esc_html( date( 'M j, Y', strtotime( $r->created_at ) ) ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Settings -->
    <div class="oddcareco-card">
        <h2>Referral Settings</h2>
        <form method="post">
            <?php wp_nonce_field( 'oddcareco_referral_settings' ); ?>
            <table class="form-table">
                <tr>
                    <th>Referrer Reward Amount (₹)</th>
                    <td><input type="number" name="referrer_reward_amount" value="<?php echo esc_attr( $settings['referrer_reward_amount'] ?? 100 ); ?>" step="1" min="0"></td>
                </tr>
                <tr>
                    <th>Referrer Reward Type</th>
                    <td>
                        <select name="referrer_reward_type">
                            <option value="fixed_cart" <?php selected( $settings['referrer_reward_type'] ?? '', 'fixed_cart' ); ?>>Fixed Cart Discount</option>
                            <option value="percent" <?php selected( $settings['referrer_reward_type'] ?? '', 'percent' ); ?>>Percentage Discount</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Referee Discount (%)</th>
                    <td><input type="number" name="referee_discount" value="<?php echo esc_attr( $settings['referee_discount'] ?? 10 ); ?>" step="1" min="0" max="100"></td>
                </tr>
                <tr>
                    <th>Cookie Duration (days)</th>
                    <td><input type="number" name="referral_cookie_days" value="<?php echo esc_attr( $settings['referral_cookie_days'] ?? 30 ); ?>" min="1"></td>
                </tr>
                <tr>
                    <th>Min. Spend for Reward (₹)</th>
                    <td><input type="number" name="reward_min_spend" value="<?php echo esc_attr( $settings['reward_min_spend'] ?? 399 ); ?>" step="1" min="0"></td>
                </tr>
                <tr>
                    <th>Reward Coupon Expiry (days)</th>
                    <td><input type="number" name="reward_expiry_days" value="<?php echo esc_attr( $settings['reward_expiry_days'] ?? 90 ); ?>" min="1"></td>
                </tr>
                <tr>
                    <th>Pending Referral Expiry (days)</th>
                    <td><input type="number" name="pending_expiry_days" value="<?php echo esc_attr( $settings['pending_expiry_days'] ?? 60 ); ?>" min="1"></td>
                </tr>
            </table>
            <p><button type="submit" name="oddcareco_save_referral_settings" class="button button-primary">Save Settings</button></p>
        </form>
    </div>
</div>
