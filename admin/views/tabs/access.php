<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$status_options = [];
foreach (wc_get_order_statuses() as $key => $label) {
    $status_options[str_replace('wc-', '', $key)] = $label;
}

$title = __('دسترسی به فاکتور', 'factorchi');
$description = '';
include $partials . 'card-section.php';

$label = __('وضعیت‌های مجاز نمایش فاکتور', 'factorchi');
$name = 'allowed_statuses';
$options = $status_options;
$selected = (array) ($s['allowed_statuses'] ?? []);
include $partials . 'field-checkbox-group.php';

$name = 'guest_access';
$label = __('دسترسی مهمان با توکن', 'factorchi');
$checked = ($s['guest_access'] ?? 'yes') === 'yes';
$description = __('مهمانان با لینک دارای token می‌توانند فاکتور را ببینند.', 'factorchi');
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';

$title = __('نمایش در فرانت‌اند', 'factorchi');
$description = __('بدون نیاز به ویرایش قالب تم.', 'factorchi');
include $partials . 'card-section.php';

$name = 'replace_view_order_url';
$label = __('جایگزینی لینک مشاهده سفارش', 'factorchi');
$checked = ($s['replace_view_order_url'] ?? 'yes') === 'yes';
$description = __('لینک get_view_order_url() به فاکتور Factorchi اشاره کند.', 'factorchi');
include $partials . 'field-toggle.php';

$name = 'show_on_thankyou';
$label = __('نمایش در صفحه تشکر', 'factorchi');
$checked = ($s['show_on_thankyou'] ?? 'yes') === 'yes';
$description = '';
include $partials . 'field-toggle.php';

$name = 'show_on_my_account';
$label = __('نمایش در حساب کاربری', 'factorchi');
$checked = ($s['show_on_my_account'] ?? 'yes') === 'yes';
$description = '';
include $partials . 'field-toggle.php';

$name = 'show_pre_invoice_cart';
$label = __('پیش‌فاکتور در سبد خرید', 'factorchi');
$checked = ($s['show_pre_invoice_cart'] ?? 'no') === 'yes';
$description = '';
include $partials . 'field-toggle.php';

include $partials . 'card-section-end.php';
