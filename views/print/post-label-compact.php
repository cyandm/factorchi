<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var array<string, mixed> $data */
?>
<div class="fc-compact fc-compact-post-label">
    <?php if (!empty($data['postbarcode']) && get_fci_settings('tapin_status')) : ?>
        <div class="fc-compact-section">
            <?php echo $data['postbarcode']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    <?php endif; ?>

    <div class="fc-compact-label-grid">
        <div class="fc-compact-address-box">
            <h3><?php esc_html_e('گیرنده', 'factorchi'); ?></h3>
            <?php echo $data['recipient'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['full_name'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['r_postcode'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['r_phone'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['r_email'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['customer_note'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <div class="fc-compact-address-box">
            <h3><?php esc_html_e('فرستنده', 'factorchi'); ?></h3>
            <?php if (!empty($data['logo'])) : ?>
                <?php echo $data['logo']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php endif; ?>
            <?php echo $data['title'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['sender'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['postcode'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['phone'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['email'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </div>

    <div class="fc-compact-footer">
        <div>
            <?php echo $data['order_id_html'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['shipping'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $data['print_date'] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php if (!empty($data['barcode'])) : ?>
            <div><?php echo $data['barcode']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <?php endif; ?>
    </div>
</div>
