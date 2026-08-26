<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var array<string, mixed> $s */
/** @var string $partials */

$title = __('تایپوگرافی', 'factorchi');
$description = __('فونت، اعداد و تاریخ پایه اسناد.', 'factorchi');
include $partials . 'card-section.php';

$name = 'font_family';
$label = __('فونت', 'factorchi');
$value = Factorchi_Font_Registry::normalize_key((string) ($s['font_family'] ?? 'peyda'));
$options = Factorchi_Font_Registry::options();
$description = '';
include $partials . 'field-select.php';

$name = 'use_persian_number';
$label = __('اعداد فارسی', 'factorchi');
$checked = ($s['use_persian_number'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'use_jalali_date';
$label = __('تاریخ شمسی', 'factorchi');
$checked = ($s['use_jalali_date'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'print_page_size';
$label = __('اندازه پیش‌فرض برگه', 'factorchi');
$value = (string) ($s['print_page_size'] ?? 'a4');
$options = ['a4' => 'A4', 'a5' => 'A5'];
$description = '';
include $partials . 'field-select.php';

include $partials . 'card-section-end.php';

$title = __('فاصله و حاشیه', 'factorchi');
$description = __('پدینگ چاپ و فاصله بین بخش‌های فاکتور.', 'factorchi');
include $partials . 'card-section.php';

foreach (
    [
        'invoice_margin'     => __('حاشیه فاکتور', 'factorchi'),
        'pre_invoice_margin' => __('حاشیه پیش‌فاکتور', 'factorchi'),
        'post_label_margin'  => __('حاشیه برچسب پستی', 'factorchi'),
        'mini_label_margin'  => __('پدینگ برچسب چاپی', 'factorchi'),
    ] as $key => $label
) {
    $name = $key;
    $value = (string) ($s[$key] ?? ($key === 'mini_label_margin' ? '2' : '10'));
    $type = 'number';
    $description = '';
    include $partials . 'field-text.php';
}

foreach (
    [
        'section_gap_a4' => __('فاصله بخش‌ها — A4', 'factorchi'),
        'section_gap_a5' => __('فاصله بخش‌ها — A5', 'factorchi'),
    ] as $key => $label
) {
    $name = $key;
    $value = (string) ($s[$key] ?? '12');
    $type = 'number';
    $description = '';
    include $partials . 'field-text.php';
}

include $partials . 'card-section-end.php';

$title = __('سایز فونت', 'factorchi');
$description = __('پیکسل؛ فاکتور ۱۰–۲۴، برچسب چاپی ۷–۲۰.', 'factorchi');
include $partials . 'card-section.php';

foreach (
    [
        'font_size_invoice'     => __('فاکتور', 'factorchi'),
        'font_size_pre_invoice' => __('پیش‌فاکتور', 'factorchi'),
        'font_size_shop'        => __('قسمت فروشگاه', 'factorchi'),
        'font_size_buyer'       => __('قسمت خریدار', 'factorchi'),
        'font_size_post_label'  => __('برچسب پستی', 'factorchi'),
        'font_size_label'       => __('برچسب فروشگاه / مشتری / محصول', 'factorchi'),
        'font_size_mini_label'  => __('برچسب چاپی', 'factorchi'),
    ] as $key => $label
) {
    $defaults = [
        'font_size_invoice'     => '14',
        'font_size_pre_invoice' => '14',
        'font_size_shop'        => '12',
        'font_size_buyer'       => '14',
        'font_size_post_label'  => '12',
        'font_size_label'       => '12',
        'font_size_mini_label'  => '9',
    ];
    $name  = $key;
    $value = (string) ($s[$key] ?? ($defaults[$key] ?? '14'));
    $type  = 'number';
    $description = '';
    include $partials . 'field-text.php';
}

include $partials . 'card-section-end.php';

$title = __('ظاهر', 'factorchi');
$description = __('گوشه گرد، لوگو و فشرده‌سازی متون.', 'factorchi');
include $partials . 'card-section.php';

$name = 'enable_border_radius';
$label = __('گوشه‌های گرد قالب', 'factorchi');
$description = '';
$checked = ($s['enable_border_radius'] ?? 'yes') === 'yes';
include $partials . 'field-toggle.php';

$name = 'shop_logo_size';
$label = __('اندازه لوگو (ارتفاع، پیکسل)', 'factorchi');
$value = (string) ($s['shop_logo_size'] ?? '80');
$type = 'number';
$description = __('۲۴ تا ۳۰۰.', 'factorchi');
include $partials . 'field-text.php';

$name = 'product_image_size';
$label = __('اندازه تصویر محصول (پیکسل)', 'factorchi');
$value = (string) ($s['product_image_size'] ?? '70');
$type = 'number';
$description = __('۲۴ تا ۲۰۰.', 'factorchi');
include $partials . 'field-text.php';

$name = 'compact_party_texts';
$label = __('متون فشرده‌تر فروشگاه / خریدار', 'factorchi');
$description = '';
$checked = ($s['compact_party_texts'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';
