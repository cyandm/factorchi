<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var array<string, mixed> $data */
?>
<div class="fc-compact fc-compact-invoice">
    <header class="fc-compact-header">
        <div class="fc-compact-shop">
            <?php if (!empty($data['logo'])) : ?>
                <?php echo $data['logo']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php endif; ?>
            <div class="fc-compact-shop-info">
                <?php echo $data['title'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['phone'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['email'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['url'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
        <div class="fc-compact-meta">
            <?php echo $data['order_id_html'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['print_date'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['transmission_date'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['barcode'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </header>

    <?php if (!empty($data['sender']) || !empty($data['postcode']) || !empty($data['economical']) || !empty($data['reg'])) : ?>
        <div class="fc-compact-section">
            <?php echo $data['sender'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['postcode'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['economical'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['reg'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['recipient']) || !empty($data['full_name']) || !empty($data['r_postcode']) || !empty($data['r_phone'])) : ?>
        <div class="fc-compact-customer">
            <div class="fc-compact-block">
                <div class="fc-compact-section-title"><?php esc_html_e('مشتری', 'factorchi'); ?></div>
                <?php echo $data['recipient'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['full_name'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['r_postcode'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['r_phone'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['r_email'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <div class="fc-compact-block">
                <div class="fc-compact-section-title"><?php esc_html_e('جزئیات سفارش', 'factorchi'); ?></div>
                <?php echo $data['order_date'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['pay_method'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['shipping'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['trans_id'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $data['national_id'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['products_table'])) : ?>
        <?php echo $data['products_table']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endif; ?>

    <?php if (!empty($data['total_table'])) : ?>
        <div class="fc-compact-totals">
            <?php echo $data['total_table']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['customer_note'])) : ?>
        <div class="fc-compact-note">
            <?php echo $data['customer_note']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['order_note'])) : ?>
        <div class="fc-compact-note">
            <?php echo $data['order_note']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    <?php endif; ?>
</div>
