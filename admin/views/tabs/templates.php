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

$title = __('چاپ و نمایش', 'factorchi');
$description = __('فونت، تاریخ و گزینه‌های نمایش مشترک اسناد.', 'factorchi');
include $partials . 'card-section.php';

$name = 'font_family'; $label = __('فونت', 'factorchi');
$value = Factorchi_Font_Registry::normalize_key((string) ($s['font_family'] ?? 'peyda'));
$options = Factorchi_Font_Registry::options();
include $partials . 'field-select.php';

$name = 'use_persian_number'; $label = __('اعداد فارسی', 'factorchi'); $checked = ($s['use_persian_number'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'use_jalali_date'; $label = __('تاریخ شمسی', 'factorchi'); $checked = ($s['use_jalali_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_print_date'; $label = __('نمایش تاریخ چاپ', 'factorchi'); $checked = ($s['show_print_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_order_date'; $label = __('نمایش تاریخ سفارش', 'factorchi'); $checked = ($s['show_order_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_date_time'; $label = __('نمایش ساعت در تاریخ‌ها', 'factorchi'); $checked = ($s['show_date_time'] ?? 'yes') === 'yes';
$description = __('اگر خاموش باشد، فقط تاریخ (بدون ساعت) نمایش داده می‌شود.', 'factorchi');
include $partials . 'field-toggle.php';

$name = 'print_page_size'; $label = __('اندازه پیش‌فرض برگه', 'factorchi'); $value = (string) ($s['print_page_size'] ?? 'a4');
$options = ['a4' => 'A4', 'a5' => 'A5'];
$description = '';
include $partials . 'field-select.php';

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

$title = __('فاصله بین قسمت‌های فاکتور (پیکسل)', 'factorchi');
$description = __('فاصله بین بخش‌های فاکتور (سربرگ فروشگاه، اطلاعات خریدار، جدول اقلام، جمع/یادداشت/بارکد، نوار جداکننده) به‌صورت جداگانه برای سایز A4 و A5.', 'factorchi');
include $partials . 'card-section.php';

foreach (
    [
        'section_gap_a4' => __('فاصله بین قسمت های فاکتور در سایز A4', 'factorchi'),
        'section_gap_a5' => __('فاصله بین قسمت های فاکتور در سایز A5', 'factorchi'),
    ] as $key => $label
) {
    $name = $key;
    $value = (string) ($s[$key] ?? '12');
    $type = 'number';
    $description = '';
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

$name = 'show_barcode_top_text';
$label = __('نمایش اطلاعات بارکد در زیر آن', 'factorchi');
$description = __('عدد/متن بارکد (barcode-text) زیر بارکد کنار لوگو نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_top_text'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_under_title';
$label = __('نمایش بارکد زیر عنوان فروشگاه', 'factorchi');
$description = __('بارکد مستقیماً زیر نام فروشگاه در هدر فاکتور و پیش‌فاکتور نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_under_title'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_under_title_text';
$label = __('نمایش اطلاعات بارکد در زیر آن', 'factorchi');
$description = __('عدد/متن بارکد (barcode-text) زیر بارکد عنوان فروشگاه نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_under_title_text'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_bottom';
$label = __('نمایش بارکد در پایین فاکتور', 'factorchi');
$description = __('بارکد در ردیف پایین فاکتور و پیش‌فاکتور (کنار جمع و یادداشت) نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_bottom'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_barcode_bottom_text';
$label = __('نمایش اطلاعات بارکد در زیر آن', 'factorchi');
$description = __('عدد/متن بارکد (barcode-text) زیر بارکد پایین فاکتور نمایش داده می‌شود.', 'factorchi');
$checked = ($s['show_barcode_bottom_text'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'filter_product_name_codes';
$label = __('فیلتر نام محصولات', 'factorchi');
$description = __('کدهایی مثل G00927 را از انتهای نام محصول حذف می‌کند؛ مثلاً «پولوشرت نیهان G00927» → «پولوشرت نیهان».', 'factorchi');
$checked = ($s['filter_product_name_codes'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('اطلاعات خریدار', 'factorchi');
$description = __('نمایش یا مخفی‌کردن فیلدهای بلوک خریدار در فاکتور و پیش‌فاکتور.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_payment_method';
$label = __('روش پرداخت', 'factorchi');
$description = __('نمایش روش پرداخت در بخش اطلاعات خریدار.', 'factorchi');
$checked = ($s['show_payment_method'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_shipping_method';
$label = __('روش ارسال', 'factorchi');
$description = __('نمایش روش ارسال در بخش اطلاعات خریدار.', 'factorchi');
$checked = ($s['show_shipping_method'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_transaction_id';
$label = __('شماره تراکنش', 'factorchi');
$description = __('نمایش شماره تراکنش در بخش اطلاعات خریدار.', 'factorchi');
$checked = ($s['show_transaction_id'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('برگه جداشدنی (برش پایین)', 'factorchi');
$description = __('نوار پایین فاکتور برای بریدن و نگه‌داشتن؛ زیر ردیف جمع/یادداشت/بارکد نمایش داده می‌شود.', 'factorchi');
include $partials . 'card-section.php';

$name = 'show_tearoff';
$label = __('نمایش برگه جداشدنی', 'factorchi');
$description = __('کل نوار برش پایین فاکتور و پیش‌فاکتور.', 'factorchi');
$checked = ($s['show_tearoff'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_payment';
$label = __('روش پرداخت', 'factorchi');
$description = '';
$checked = ($s['show_tearoff_payment'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_tracking';
$label = __('شناسه پیگیری', 'factorchi');
$description = '';
$checked = ($s['show_tearoff_tracking'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_order_date';
$label = __('تاریخ سفارش', 'factorchi');
$description = '';
$checked = ($s['show_tearoff_order_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'show_tearoff_order_id';
$label = __('شناسه سفارش', 'factorchi');
$description = '';
$checked = ($s['show_tearoff_order_id'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';
