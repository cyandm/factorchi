<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$title = __('اطلاع رسانی (ارسال فاکتور)', 'factorchi');
$description = __('پس از رسیدن سفارش به وضعیت مشخص، فاکتور از طریق پیامک اطلاع‌رسانی و ارسال می‌شود (همان قالب و کانال SMS تنظیمات اعلان).', 'factorchi');
include $partials . 'card-section.php';

$name = 'survey_enabled';
$label = __('فعال‌سازی اطلاع رسانی', 'factorchi');
$checked = ($s['survey_enabled'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'survey_status';
$label = __('وضعیت سفارش برای صف ارسال', 'factorchi');
$value = (string) ($s['survey_status'] ?? 'completed');
$description = __('مثال: completed یا processing', 'factorchi');
include $partials . 'field-text.php';

$name = 'survey_sms_delay_days';
$label = __('تأخیر ارسال پیامک (روز)', 'factorchi');
$value = (string) ($s['survey_sms_delay_days'] ?? '3');
$type = 'number';
$description = __('تعداد روز بعد از تغییر وضعیت تا اطلاع‌رسانی و ارسال فاکتور با SMS.', 'factorchi');
include $partials . 'field-text.php';

include $partials . 'card-section-end.php';
