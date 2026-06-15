<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$title = __('نظرسنجی', 'factorchi');
$description = __('ارسال خودکار درخواست نظر پس از تکمیل سفارش.', 'factorchi');
include $partials . 'card-section.php';

$name = 'survey_enabled';
$label = __('فعال‌سازی نظرسنجی', 'factorchi');
$checked = ($s['survey_enabled'] ?? 'no') === 'yes';
include $partials . 'field-toggle.php';

$name = 'survey_status';
$label = __('وضعیت ثبت نظرسنجی', 'factorchi');
$value = (string) ($s['survey_status'] ?? 'completed');
$description = __('مثال: completed, processing', 'factorchi');
include $partials . 'field-text.php';

$name = 'survey_sms_delay_days';
$label = __('تأخیر SMS (روز)', 'factorchi');
$value = (string) ($s['survey_sms_delay_days'] ?? '3');
$type = 'number';
include $partials . 'field-text.php';

$name = 'survey_email_delay_days';
$label = __('تأخیر ایمیل (روز)', 'factorchi');
$value = (string) ($s['survey_email_delay_days'] ?? '3');
$type = 'number';
include $partials . 'field-text.php';

include $partials . 'card-section-end.php';
