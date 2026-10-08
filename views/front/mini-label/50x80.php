<?php

if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}

include FCI_VIEW_PATH . 'header.php';

$is_preview = method_exists($this, 'is_preview') && $this->is_preview();

if ($is_preview) {
    $name     = __('علی رضایی', 'factorchi');
    $phone    = '09121234567';
    $address  = __('تهران، خیابان نمونه، پلاک ۱۲، واحد ۳', 'factorchi');
    $postcode = '1234567890';
    $note     = __('لطفاً قبل از ظهر ارسال شود.', 'factorchi');
    $order_items = [
        ['name' => __('محصول نمونه ۱، قرمز', 'factorchi'), 'qty' => 2],
        ['name' => __('محصول نمونه ۲', 'factorchi'), 'qty' => 1],
    ];
} else {
    $name     = $customer->get_full_name();
    $phone    = $customer->get_phone();
    $address  = $customer->get_address();
    $postcode = $customer->get_postal_code();
    $note     = $customer->get_customer_note();
    $order_items = $products->get_list();
}

$fit_order_id = (string) ($data['shop_order_id'] ?? $this->get_order_id());
?>
<div class="fc-mini-label fc-mini-label-50x80 container"
     data-fc-fit
     data-order-id="<?php echo esc_attr($fit_order_id); ?>"
     data-continued-label="<?php esc_attr_e('ادامه سفارش', 'factorchi'); ?>"
     data-next-label="<?php esc_attr_e('… ادامه در برچسب بعد', 'factorchi'); ?>"
     data-page-label="<?php /* translators: 1: current label number, 2: total labels */ esc_attr_e('برچسب %1$s از %2$s', 'factorchi'); ?>">
    <div class="inner">
        <?php if ($name !== '') : ?>
            <p class="fc-mini-name">
                <strong><?php esc_html_e('نام سفارش دهنده/دریافت کننده:', 'factorchi'); ?></strong>
                <span><?php echo esc_html($name); ?></span>
            </p>
        <?php endif; ?>

        <?php if ($phone !== '') : ?>
            <p class="fc-mini-phone">
                <strong><?php esc_html_e('تلفن:', 'factorchi'); ?></strong>
                <span dir="ltr"><?php echo esc_html($phone); ?></span>
            </p>
        <?php endif; ?>

        <?php if ($address !== '') : ?>
            <p class="fc-mini-address">
                <strong><?php esc_html_e('آدرس دریافت کننده:', 'factorchi'); ?></strong>
                <span><?php echo esc_html($address); ?></span>
            </p>
        <?php endif; ?>

        <?php if ($postcode !== '') : ?>
            <p class="fc-mini-postcode">
                <strong><?php esc_html_e('کد پستی:', 'factorchi'); ?></strong>
                <span dir="ltr"><?php echo esc_html($postcode); ?></span>
            </p>
        <?php endif; ?>

        <?php if ($order_items !== []) : ?>
            <div class="fc-mini-orders">
                <strong class="fc-mini-orders-label"><?php esc_html_e('سفارشات:', 'factorchi'); ?></strong>
                <ol class="fc-mini-orders-list">
                    <?php
                    $index = 0;
                    foreach ($order_items as $item) :
                        $index++;
                        ?>
                        <li>
                            <?php echo esc_html((string) $index . '. ' . $item['name']); ?>
                            &times; <?php echo esc_html((string) $item['qty']); ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        <?php endif; ?>

        <p class="fc-mini-note">
            <strong><?php esc_html_e('یادداشت:', 'factorchi'); ?></strong>
            <?php if ($note !== '') : ?>
                <span><?php echo esc_html($note); ?></span>
            <?php endif; ?>
        </p>
    </div>
</div>
<?php if (!defined('FACTORCHI_MINI_LABEL_FIT_LOADED')) : ?>
    <?php define('FACTORCHI_MINI_LABEL_FIT_LOADED', true); ?>
    <script src="<?php echo esc_url(FACTORCHI_JS_URL . 'mini-label-fit.js?ver=' . FACTORCHI_VERSION); ?>"></script>
<?php endif; ?>
<?php
include FCI_VIEW_PATH . 'footer.php';
