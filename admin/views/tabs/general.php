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

$name = 'page_break'; $label = __('صفحه‌بندی چاپ', 'factorchi'); $checked = ($s['page_break'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'print_page_size'; $label = __('اندازه پیش‌فرض برگه', 'factorchi'); $value = (string) ($s['print_page_size'] ?? 'a4');
$options = ['a4' => 'A4', 'a5' => 'A5'];
include $partials . 'field-select.php';

$name = 'print_per_page'; $label = __('تعداد در هر برگه (چاپ جمع‌وجور)', 'factorchi'); $value = (string) ($s['print_per_page'] ?? '1');
$options = ['1' => __('۱ فاکتور در برگه', 'factorchi'), '2' => __('۲ در A4', 'factorchi'), '4' => __('۴ در A4', 'factorchi')];
include $partials . 'field-select.php';

$name = 'bulk_use_compact'; $label = __('چاپ همگانی با قالب جمع‌وجور', 'factorchi'); $checked = ($s['bulk_use_compact'] ?? 'yes') === 'yes';
$description = __('در لیست سفارشات، عملیات همگانی «چاپ فاکتور» و «چاپ برچسب پستی» از قالب compact استفاده می‌کند.', 'factorchi');
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';
