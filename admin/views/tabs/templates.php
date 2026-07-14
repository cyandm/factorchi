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
];

$title = __('انتخاب قالب', 'factorchi');
$description = __('قالب‌های موجود از پوشه views/front اسکن می‌شوند.', 'factorchi');
include $partials . 'card-section.php';

foreach ($template_fields as $key => $label) {
    $options = Factorchi_Template_Registry::get_options_for_setting($key);
    $default = $key === 'post_label_view' ? 'modern-a4' : 'modern';
    $current = (string) ($s[$key] ?? $default);

    if (in_array($key, ['invoice_default_view', 'pre_invoice_view'], true)) {
        $current = Factorchi_Settings::normalize_invoice_view($current);
    } elseif ($key === 'post_label_view') {
        $current = Factorchi_Settings::normalize_post_label_view($current);
    }

    if (!isset($options[$current]) && $current !== '') {
        $options[$current] = Factorchi_Template_Registry::label_for_slug($current);
    }

    $preview_base = Factorchi_Template_Registry::preview_base_url($key);
    $preview_href = Factorchi_Template_Registry::preview_url($key, $current);

    $show_slug = false;
?>
    <div class="fc-field" data-preview-base="<?php echo esc_attr($preview_base); ?>">
        <label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
        <div class="fc-select-row">
            <select id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" class="fc-input fc-select fc-template-select">
                <?php foreach ($options as $opt_value => $opt_label) : ?>
                    <option value="<?php echo esc_attr($opt_value); ?>" <?php selected($current, $opt_value); ?>><?php echo esc_html($show_slug ? $opt_label . ' (' . $opt_value . ')' : $opt_label); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($preview_href !== '') : ?>
                <a href="<?php echo esc_url($preview_href); ?>" target="_blank" rel="noopener" class="button fc-preview-btn"><?php esc_html_e('پیش‌نمایش', 'factorchi'); ?></a>
            <?php endif; ?>
        </div>
    </div>
<?php
}

include $partials . 'card-section-end.php';

$title = __('حاشیه چاپ (پیکسل)', 'factorchi');
$description = '';
include $partials . 'card-section.php';

foreach (
    [
        'invoice_margin'     => __('حاشیه فاکتور', 'factorchi'),
        'pre_invoice_margin' => __('حاشیه پیش‌فاکتور', 'factorchi'),
        'post_label_margin'  => __('حاشیه برچسب پستی', 'factorchi'),
    ] as $key => $label
) {
    $name = $key;
    $value = (string) ($s[$key] ?? '10');
    $type = 'number';
    include $partials . 'field-text.php';
}

include $partials . 'card-section-end.php';

$title = __('سایز فونت (پیکسل)', 'factorchi');
$description = __('سایز پایه متن برای هر نوع سند. محدوده ۱۰ تا ۲۴ پیکسل.', 'factorchi');
include $partials . 'card-section.php';

foreach (
    [
        'font_size_invoice'     => __('فاکتور', 'factorchi'),
        'font_size_pre_invoice' => __('پیش‌فاکتور', 'factorchi'),
        'font_size_post_label'  => __('برچسب پستی', 'factorchi'),
        'font_size_label'       => __('برچسب فروشگاه / مشتری / محصول', 'factorchi'),
    ] as $key => $label
) {
    $defaults = [
        'font_size_invoice'     => '14',
        'font_size_pre_invoice' => '14',
        'font_size_post_label'  => '12',
        'font_size_label'       => '12',
    ];
    $name  = $key;
    $value = (string) ($s[$key] ?? ($defaults[$key] ?? '14'));
    $type  = 'number';
    include $partials . 'field-text.php';
}

include $partials . 'card-section-end.php';

$title = __('ظاهر فاکتور', 'factorchi');
$description = __('تنظیمات ظاهری مشترک قالب‌های فاکتور و پیش‌فاکتور.', 'factorchi');
include $partials . 'card-section.php';

$name = 'enable_border_radius';
$label = __('گوشه‌های گرد قالب', 'factorchi');
$description = __('در صورت خاموش بودن، گوشه‌های کارت، جداول و بلوک‌ها تیز می‌شوند.', 'factorchi');
$checked = ($s['enable_border_radius'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_product_image';
$label = __('نمایش تصویر محصول', 'factorchi');
$description = __('تصویر مربع محصول کنار نام در جدول اقلام نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_product_image'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'product_image_size';
$label = __('اندازه تصویر محصول (پیکسل - مربع)', 'factorchi');
$value = (string) ($s['product_image_size'] ?? '70');
$type = 'number';
$description = __('مثال: ۷۰ - محدوده ۲۴ تا ۲۰۰ پیکسل.', 'factorchi');
include $partials . 'field-text.php';

$name = 'show_barcode_top';
$label = __('نمایش بارکد در بالای فاکتور', 'factorchi');
$description = __('بارکد کنار لوگوی فروشگاه در هدر فاکتور و پیش‌فاکتور نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_top'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_bottom';
$label = __('نمایش بارکد در پایین فاکتور', 'factorchi');
$description = __('بارکد در ردیف پایین فاکتور و پیش‌فاکتور (کنار جمع و یادداشت) نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_bottom'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';
