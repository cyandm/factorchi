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
