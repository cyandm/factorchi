<?php
if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}
?>

<?php include(FCI_VIEW_PATH . '/front/orders/header.php'); ?>

<body class="<?php echo $this->append_body_class(); ?>">

<?php include(FCI_VIEW_PATH . '/front/orders/sticky-nav.php'); ?>

    <div class="container">
        <?php if ($state && $status): ?>

            <?php include(FCI_VIEW_PATH . '/front/orders/bread.php'); ?>

            <div class="view2-order-holder">
                <?php $i = 1;
                foreach ($orders as $order_id) { ?>
                    <?php
                    $order = wc_get_order($order_id);
                    if (is_a($order, 'WC_Order_Refund')) {
                        $order = wc_get_order($order->get_parent_id());
                    }
                    $items_quantity += $order->get_item_count();

                    $order_data = new FCI_Order_Detail($order_id, 'order');
                    $customer_info = new FCI_Customer_Detail($order_id, 'order');
                    $shop_info = new FCI_Shop($order_id, 'order');
                    ?>

                    <div class="view2-order-card-holder">
                        <div class="view2-order-card">
                            <?php if ($fci_orders->is_status('order-row')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('row')) ?>
                                    </div>
                                    <div class="content">
                                        <?= ($i + 1) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-id') || $fci_orders->is_status('order-date')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('order')) ?>
                                    </div>
                                    <div class="content">
                                        <?php if ($fci_orders->is_status('order-id')): ?>
                                            <a href="<?= get_edit_post_link($order_id) ?>"
                                               target="_blank"><?= $order_data->get_order_id() ?></a>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-date')): ?>
                                            <div><?= $customer_info->get_order_date() ?> </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-full-name') || $fci_orders->is_status('order-phone') || $fci_orders->is_status('order-email') || $fci_orders->is_status('order-meli-code')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('customer')) ?>
                                    </div>
                                    <div class="content">
                                        <?php if ($fci_orders->is_status('order-full-name')): ?>
                                            <div> <?= $customer_info->get_full_name() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-meli-code')): ?>
                                            <div><?= $customer_info->get_national_id() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-email')): ?>
                                            <div><?= $customer_info->get_email() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-phone')): ?>
                                            <div><?= $customer_info->get_phone() ?> </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-addr') || $fci_orders->is_status('order-postcode') || $fci_orders->is_status('order-shipping-method')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html(str_replace(':', '', $labels->get_label('address'))) ?>
                                    </div>
                                    <div class="content">
                                        <?php if ($fci_orders->is_status('order-addr')): ?>
                                            <div> <?= $customer_info->get_address() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-postcode')): ?>
                                            <div><?= $customer_info->get_postal_code() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-shipping-method')): ?>
                                            <div><?= $customer_info->get_shipping_method() ?> </div>;
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-payment-method') || $fci_orders->is_status('order-transaction-id')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('payment'))  ?>
                                    </div>
                                    <div class="content">
                                        <?php if ($fci_orders->is_status('order-payment-method')): ?>
                                            <div> <?= $customer_info->get_payment_method() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-transaction-id')): ?>
                                            <div><?= $customer_info->get_transaction_id() ?> </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-customer-note') || $fci_orders->is_status('order-note')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('note'))  ?>
                                    </div>
                                    <div class="content">
                                        <?php if ($fci_orders->is_status('order-customer-note')): ?>
                                            <div><?= $order_data->get_order_note() ?> </div>
                                        <?php endif; ?>
                                        <?php if ($fci_orders->is_status('order-note')): ?>
                                            <div><?= $order_data->get_customer_note() ?> </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-products')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('products'))  ?>
                                    </div>
                                    <div class="content">
                                        <div> <?= $fci_orders->get_products($order) ?> </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-price')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('price')) ?>
                                    </div>
                                    <div class="content">
                                        <div> <?= $fci_orders->get_price($order) ?> </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fci_orders->is_status('order-provider-price')): ?>
                                <div class="view2-order-item">
                                    <div class="title">
                                        <?= esc_html($labels->get_label('provider-price')) ?>
                                    </div>
                                    <div class="content">
                                        <div> <?= $fci_orders->get_provider_price($order) ?> </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="totals">
                            <div>
                                <span><?= $labels->get_label('quantity')?> </span><span> <?= $items_quantity ?> </span>
                            </div>
                            <div>
                                <span> <?= $labels->get_label('price') ?> </span><span><?= wc_price($fci_orders->total_amount) ?> </span>
                            </div>
                             |<div>
                                <span><?= $labels->get_label('provider-price') ?> </span><span> <?= wc_price($fci_orders->total_provider_price) ?> </span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        <?php else: ?>
            <div class="no-orders"><?php _e('Use filter for display order list.', 'fci'); ?></div>
        <?php endif; ?>
    </div>

<?php include(FCI_VIEW_PATH . '/front/orders/footer.php'); ?>