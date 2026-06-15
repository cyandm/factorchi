<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$channels = [
    'channel_email'    => ['id' => 'email', 'label' => __('ایمیل', 'factorchi')],
    'channel_sms'      => ['id' => 'sms', 'label' => __('پیامک', 'factorchi')],
    'channel_whatsapp' => ['id' => 'whatsapp', 'label' => __('واتساپ', 'factorchi')],
    'channel_socials'  => ['id' => 'socials', 'label' => __('شبکه‌های اجتماعی', 'factorchi')],
    'channel_telegram' => ['id' => 'telegram', 'label' => __('ربات تلگرام', 'factorchi')],
    'channel_bale'     => ['id' => 'bale', 'label' => __('ربات بله', 'factorchi')],
];

$title = __('فعال‌سازی کانال‌ها', 'factorchi');
$description = __('هر کانال را می‌توانید جداگانه فعال کنید.', 'factorchi');
include $partials . 'card-section.php';
?>
<div class="fc-channel-grid">
    <?php foreach ($channels as $name => $channel) : ?>
        <?php $checked = ($s[$name] ?? 'no') === 'yes'; ?>
        <div class="fc-channel-card <?php echo $checked ? 'is-enabled' : ''; ?>" data-channel="<?php echo esc_attr($channel['id']); ?>">
            <?php
            $label = $channel['label'];
            include $partials . 'field-toggle.php';
            ?>
        </div>
    <?php endforeach; ?>
</div>
<?php
include $partials . 'card-section-end.php';

$title = __('تنظیمات ایمیل', 'factorchi');
include $partials . 'card-section.php';
?>
<div class="fc-channel-fields <?php echo ($s['channel_email'] ?? 'no') === 'yes' ? 'is-visible' : ''; ?>" data-channel="email">
<?php
$name = 'email_subject'; $label = __('موضوع ایمیل', 'factorchi'); $value = (string) ($s['email_subject'] ?? '');
include $partials . 'field-text.php';

$name = 'email_body'; $label = __('متن ایمیل', 'factorchi'); $value = (string) ($s['email_body'] ?? ''); $rows = 4;
include $partials . 'field-textarea.php';
?>
</div>
<?php
include $partials . 'card-section-end.php';

$title = __('تنظیمات واتساپ و شبکه‌های اجتماعی', 'factorchi');
include $partials . 'card-section.php';
?>
<div class="fc-channel-fields <?php echo ($s['channel_whatsapp'] ?? 'no') === 'yes' ? 'is-visible' : ''; ?>" data-channel="whatsapp">
<?php
$name = 'whatsapp_api_url'; $label = __('API واتساپ', 'factorchi'); $value = (string) ($s['whatsapp_api_url'] ?? ''); $type = 'url';
include $partials . 'field-text.php';

$name = 'whatsapp_message'; $label = __('متن واتساپ', 'factorchi'); $value = (string) ($s['whatsapp_message'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';
?>
</div>
<div class="fc-channel-fields <?php echo ($s['channel_socials'] ?? 'no') === 'yes' ? 'is-visible' : ''; ?>" data-channel="socials">
<?php
$name = 'socials_api_url'; $label = __('API شبکه‌های اجتماعی', 'factorchi'); $value = (string) ($s['socials_api_url'] ?? ''); $type = 'url';
include $partials . 'field-text.php';

$name = 'socials_message'; $label = __('متن شبکه‌های اجتماعی', 'factorchi'); $value = (string) ($s['socials_message'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';
?>
</div>
<?php
include $partials . 'card-section-end.php';
?>
<p class="fc-hint"><?php esc_html_e('متغیرها: {order_id} {customer_name} {invoice_url} {payment_url} {shop_name}', 'factorchi'); ?></p>
