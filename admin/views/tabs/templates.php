<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$template_fields = [
    'invoice_default_view' => __('قالب پیش‌فرض فاکتور', 'factorchi'),
    'pre_invoice_view'     => __('قالب پیش‌فاکتور', 'factorchi'),
    'post_label_view'      => __('قالب برچسب پستی', 'factorchi'),
    'order_label_view'     => __('قالب برچسب سفارش', 'factorchi'),
    'orders_view'          => __('قالب گزارش سفارشات', 'factorchi'),
];

$title = __('انتخاب قالب', 'factorchi');
$description = __('قالب‌های موجود از پوشه views/front اسکن می‌شوند.', 'factorchi');
include $partials . 'card-section.php';

foreach ($template_fields as $key => $label) {
    $options = Factorchi_Template_Registry::get_options_for_setting($key);
    $current = (string) ($s[$key] ?? 'view-1');

    if (!isset($options[$current]) && $current !== '') {
        $options[$current] = Factorchi_Template_Registry::label_for_slug($current);
    }

    $preview_base = add_query_arg([
        'action' => 'factorchi-show',
        'type'   => [
            'invoice_default_view' => 'invoice',
            'pre_invoice_view'     => 'pre-invoice',
            'post_label_view'      => 'post-label',
            'order_label_view'     => 'order-label',
            'orders_view'          => 'orders',
        ][$key] ?? 'invoice',
    ], home_url('/'));
    ?>
    <div class="fc-field" data-preview-base="<?php echo esc_attr($preview_base); ?>">
        <label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
        <div class="fc-select-row">
            <select id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" class="fc-input fc-select fc-template-select">
                <?php foreach ($options as $opt_value => $opt_label) : ?>
                    <option value="<?php echo esc_attr($opt_value); ?>" <?php selected($current, $opt_value); ?>><?php echo esc_html($opt_label . ' (' . $opt_value . ')'); ?></option>
                <?php endforeach; ?>
            </select>
            <a href="<?php echo esc_url(Factorchi_Template_Registry::preview_url($key, $current)); ?>" target="_blank" rel="noopener" class="button fc-preview-btn"><?php esc_html_e('پیش‌نمایش', 'factorchi'); ?></a>
        </div>
    </div>
    <?php
}

include $partials . 'card-section-end.php';

$title = __('حاشیه چاپ (پیکسل)', 'factorchi');
$description = '';
include $partials . 'card-section.php';

foreach ([
    'invoice_margin'     => __('حاشیه فاکتور', 'factorchi'),
    'pre_invoice_margin' => __('حاشیه پیش‌فاکتور', 'factorchi'),
    'post_label_margin'  => __('حاشیه برچسب پستی', 'factorchi'),
] as $key => $label) {
    $name = $key; $value = (string) ($s[$key] ?? '10'); $type = 'number';
    include $partials . 'field-text.php';
}

include $partials . 'card-section-end.php';

$title = __('سایز فونت (پیکسل)', 'factorchi');
$description = __('سایز پایه متن برای هر نوع سند. محدوده ۱۰ تا ۲۴ پیکسل.', 'factorchi');
include $partials . 'card-section.php';

foreach ([
    'font_size_invoice'     => __('فاکتور', 'factorchi'),
    'font_size_pre_invoice' => __('پیش‌فاکتور', 'factorchi'),
    'font_size_post_label'  => __('برچسب پستی', 'factorchi'),
    'font_size_order_label' => __('برچسب سفارش', 'factorchi'),
    'font_size_orders'      => __('گزارش سفارشات', 'factorchi'),
    'font_size_label'       => __('برچسب فروشگاه / مشتری / محصول', 'factorchi'),
] as $key => $label) {
    $defaults = [
        'font_size_invoice'     => '14',
        'font_size_pre_invoice' => '14',
        'font_size_post_label'  => '12',
        'font_size_order_label' => '12',
        'font_size_orders'      => '13',
        'font_size_label'       => '12',
    ];
    $name  = $key;
    $value = (string) ($s[$key] ?? ($defaults[$key] ?? '14'));
    $type  = 'number';
    include $partials . 'field-text.php';
}

include $partials . 'card-section-end.php';

$title = __('تصویر محصول در فاکتور', 'factorchi');
$description = __('تصویر مربع محصول کنار نام در جدول اقلام فاکتور و پیش‌فاکتور نمایش داده می‌شود.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_product_image';
$label = __('نمایش تصویر محصول', 'factorchi');
$checked = ($s['show_product_image'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'product_image_size';
$label = __('اندازه تصویر (پیکسل — مربع)', 'factorchi');
$value = (string) ($s['product_image_size'] ?? '70');
$type = 'number';
$description = __('مثال: ۷۰ — محدوده ۲۴ تا ۲۰۰ پیکسل.', 'factorchi');
include $partials . 'field-text.php';

include $partials . 'card-section-end.php';
