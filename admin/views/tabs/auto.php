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

$channel_options = [
    'email'    => __('ایمیل', 'factorchi'),
    'sms'      => __('پیامک', 'factorchi'),
    'whatsapp' => __('واتساپ', 'factorchi'),
    'socials'  => __('شبکه‌های اجتماعی', 'factorchi'),
    'telegram' => __('تلگرام', 'factorchi'),
    'bale'     => __('بله', 'factorchi'),
];

$title = __('ارسال خودکار', 'factorchi');
$description = __('با تغییر وضعیت سفارش، فاکتور از کانال‌های انتخابی ارسال می‌شود.', 'factorchi');
include $partials . 'card-section.php';

$label = __('ارسال در وضعیت‌های', 'factorchi');
$name = 'auto_send_statuses';
$options = $status_options;
$selected = (array) ($s['auto_send_statuses'] ?? []);
include $partials . 'field-checkbox-group.php';

$label = __('کانال‌های ارسال خودکار', 'factorchi');
$name = 'auto_send_channels';
$options = $channel_options;
$selected = (array) ($s['auto_send_channels'] ?? []);
include $partials . 'field-checkbox-group.php';

include $partials . 'card-section-end.php';
