<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$title = __('اطلاعات فروشگاه', 'factorchi');
$description = '';
include $partials . 'card-section.php';

$name = 'shop_name'; $label = __('نام فروشگاه', 'factorchi'); $value = (string) ($s['shop_name'] ?? '');
include $partials . 'field-text.php';

$name = 'shop_url'; $label = __('آدرس سایت', 'factorchi'); $value = (string) ($s['shop_url'] ?? ''); $type = 'url';
include $partials . 'field-text.php';

$name = 'shop_email'; $label = __('ایمیل', 'factorchi'); $value = (string) ($s['shop_email'] ?? ''); $type = 'email';
include $partials . 'field-text.php';

$name = 'shop_phone'; $label = __('تلفن', 'factorchi'); $value = (string) ($s['shop_phone'] ?? '');
include $partials . 'field-text.php';

$name = 'shop_address'; $label = __('آدرس', 'factorchi'); $value = (string) ($s['shop_address'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';

$name = 'shop_postcode'; $label = __('کدپستی', 'factorchi'); $value = (string) ($s['shop_postcode'] ?? '');
include $partials . 'field-text.php';

$name = 'shop_economical'; $label = __('شماره اقتصادی', 'factorchi'); $value = (string) ($s['shop_economical'] ?? '');
include $partials . 'field-text.php';

$name = 'shop_reg'; $label = __('شماره ثبت', 'factorchi'); $value = (string) ($s['shop_reg'] ?? '');
include $partials . 'field-text.php';

$value = (string) ($s['shop_logo'] ?? '');
include $partials . 'field-logo.php';

$name = 'shop_note'; $label = __('یادداشت فاکتور', 'factorchi'); $value = (string) ($s['shop_note'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';

include $partials . 'card-section-end.php';

$title = __('چاپ و نمایش', 'factorchi');
$description = '';
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
