<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$title = __('پنل پیامک', 'factorchi');
$description = __('تنظیمات ارسال SMS از طریق پنل‌های ایرانی.', 'factorchi');
include $partials . 'card-section.php';

$panel_options = [];
foreach (['smsir', 'melipayamak', 'farapayamak', 'ippanel', 'farazsms', 'maxsms', 'modirpayamak'] as $panel) {
    $panel_options[$panel] = $panel;
}

$name = 'sms_panel'; $label = __('پنل SMS', 'factorchi'); $value = (string) ($s['sms_panel'] ?? 'smsir'); $options = $panel_options;
include $partials . 'field-select.php';

$name = 'sms_username'; $label = __('نام کاربری', 'factorchi'); $value = (string) ($s['sms_username'] ?? '');
include $partials . 'field-text.php';

$name = 'sms_password'; $label = __('رمز / API Key', 'factorchi'); $value = (string) ($s['sms_password'] ?? '');
include $partials . 'field-text.php';

$name = 'sms_sender'; $label = __('شماره ارسال', 'factorchi'); $value = (string) ($s['sms_sender'] ?? '');
include $partials . 'field-text.php';

$name = 'sms_pattern_id'; $label = __('شناسه پترن', 'factorchi'); $value = (string) ($s['sms_pattern_id'] ?? '');
include $partials . 'field-text.php';

$name = 'sms_message'; $label = __('متن پیامک', 'factorchi'); $value = (string) ($s['sms_message'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';

include $partials . 'card-section-end.php';
