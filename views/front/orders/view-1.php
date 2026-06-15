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


            <table class="orders-table fci-res-table fci-border-table">
                <thead>
                <tr>
                    <?php if ($fci_orders->is_status('order-row')): ?>
                        <th class="row"> <?= esc_html($labels->get_label('row')) ?></th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-id') || $fci_orders->is_status('order-date') || $fci_orders->is_status('order-meta')): ?>
                        <th class="order"> <?= esc_html($labels->get_label('order')) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-full-name') || $fci_orders->is_status('order-phone') || $fci_orders->is_status('order-email') || $fci_orders->is_status('order-meli-code') || $fci_orders->is_status('order-user-meta')): ?>
                        <th class="customer"> <?= esc_html($labels->get_label('customer')) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-addr') || $fci_orders->is_status('order-postcode') || $fci_orders->is_status('order-shipping-method')): ?>
                        <th class="address"> <?= esc_html(str_replace(':', '', $labels->get_label('address'))) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-payment-method') || $fci_orders->is_status('order-transaction-id')): ?>
                        <th class="payment"><?= esc_html($labels->get_label('payment')) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-customer-note') || $fci_orders->is_status('order-note')): ?>
                        <th class="note"> <?= esc_html($labels->get_label('note')) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-products')): ?>
                        <th class="products"> <?= esc_html($labels->get_label('products')) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-price')): ?>
                        <th class="price"> <?= esc_html($labels->get_label('price')) ?> </th>
                    <?php endif; ?>
                    <?php if ($fci_orders->is_status('order-provider-price')): ?>
                        <th class="provider-price"> <?= esc_html($labels->get_label('provider-price')) ?> </th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
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

                    <tr>
                        <?php if ($fci_orders->is_status('order-row')): ?>
                            <td class="row"> <?= ($i++) ?></td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-id') || $fci_orders->is_status('order-date')): ?>
                            <td class="order">
                                <?php if ($fci_orders->is_status('order-id')): ?>
                                    <div><a href=" <?= get_edit_post_link($order_id) ?> "
                                            target="_blank"> <?= $order_data->get_order_id() ?> </a></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-date')): ?>
                                    <div><?= $customer_info->get_order_date() ?> </div>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-full-name') || $fci_orders->is_status('order-phone') || $fci_orders->is_status('order-email') || $fci_orders->is_status('order-meli-code')): ?>
                            <td class="customer">
                                <?php if ($fci_orders->is_status('order-full-name')): ?>
                                    <div> <?= $customer_info->get_full_name() ?></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-meli-code')): ?>
                                    <div> <?= $customer_info->get_national_id() ?></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-email')): ?>
                                    <div><?= $customer_info->get_email() ?> </div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-phone')): ?>
                                    <div><?= $customer_info->get_phone() ?> </div>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-addr') || $fci_orders->is_status('order-postcode') || $fci_orders->is_status('order-shipping-method')): ?>
                            <td class="address">
                                <?php if ($fci_orders->is_status('order-addr')): ?>
                                    <div> <?= $customer_info->get_address() ?></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-postcode')): ?>
                                    <div> <?= $customer_info->get_postal_code() ?></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-shipping-method')): ?>
                                    <div><?= $customer_info->get_shipping_method() ?> </div>
                                <?php endif; ?>

                            </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-payment-method') || $fci_orders->is_status('order-transaction-id')): ?>
                            <td class="payment">
                                <?php if ($fci_orders->is_status('order-payment-method')): ?>
                                    <div> <?= $customer_info->get_payment_method() ?></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-transaction-id')): ?>
                                    <div> <?= $customer_info->get_transaction_id() ?></div>
                                <?php endif; ?>

                            </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-customer-note') || $fci_orders->is_status('order-note')): ?>
                            <td class="note">
                                <?php if ($fci_orders->is_status('order-customer-note')): ?>
                                    <div> <?= $order_data->get_order_note() ?></div>
                                <?php endif; ?>
                                <?php if ($fci_orders->is_status('order-note')): ?>
                                    <div> <?= $order_data->get_customer_note() ?></div>
                                <?php endif; ?>

                            </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-products')): ?>
                            <td class="products"> <?= $fci_orders->get_products($order) ?> </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-price')): ?>
                            <td class="price"> <?= $fci_orders->get_price($order) ?> </td>
                        <?php endif; ?>
                        <?php if ($fci_orders->is_status('order-provider-price')): ?>
                            <td class="provider"> <?= $fci_orders->get_provider_price($order) ?> </td>
                        <?php endif; ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="totals">
                <div>
                    <span><?= $labels->get_label('quantity') ?> </span><span> <?= $items_quantity ?> </span>
                </div>
                <div>
                    <span> <?= $labels->get_label('price') ?> </span><span><?= wc_price($fci_orders->total_amount) ?> </span>
                </div>
                |
                <div>
                    <span><?= $labels->get_label('provider-price') ?> </span><span> <?= wc_price($fci_orders->total_provider_price) ?> </span>
                </div>
            </div>


        <?php else: ?>

            <div class="no-orders"><?php _e('Use filter for display order list.', 'fci'); ?></div>

        <?php endif; ?>
    </div>

<?php include(FCI_VIEW_PATH . '/front/orders/footer.php'); ?>