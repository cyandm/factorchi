<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$title = __('تاپین', 'factorchi');
$description = __('نمایش بارکد پستی از متای سفارش در برچسب پستی.', 'factorchi');
include $partials . 'card-section.php';

$name = 'tapin_status';
$label = __('یکپارچگی تاپین', 'factorchi');
$checked = ($s['tapin_status'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'tapin_barcode_meta';
$label = __('کلید متا بارکد پستی', 'factorchi');
$value = (string) ($s['tapin_barcode_meta'] ?? '_tapin_barcode');
include $partials . 'field-text.php';

$name = 'line_items_delete';
$label = __('مخفی کردن متا آیتم‌ها', 'factorchi');
$value = (string) ($s['line_items_delete'] ?? '');
$description = __('کلیدهای متا با ویرگول جدا شوند.', 'factorchi');
include $partials . 'field-text.php';

include $partials . 'card-section-end.php';
