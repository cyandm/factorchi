<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$title = __('ربات تلگرام', 'factorchi');
include $partials . 'card-section.php';
?>
<div class="fc-channel-fields is-visible" data-channel="telegram">
<?php
$name = 'telegram_bot_token'; $label = __('توکن ربات تلگرام', 'factorchi'); $value = (string) ($s['telegram_bot_token'] ?? '');
include $partials . 'field-text.php';

$name = 'telegram_chat_id'; $label = __('Chat ID تلگرام', 'factorchi'); $value = (string) ($s['telegram_chat_id'] ?? '');
include $partials . 'field-text.php';

$name = 'telegram_message'; $label = __('متن تلگرام', 'factorchi'); $value = (string) ($s['telegram_message'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';
?>
</div>
<?php
include $partials . 'card-section-end.php';

$title = __('ربات بله', 'factorchi');
include $partials . 'card-section.php';
?>
<div class="fc-channel-fields is-visible" data-channel="bale">
<?php
$name = 'bale_bot_token'; $label = __('توکن ربات بله', 'factorchi'); $value = (string) ($s['bale_bot_token'] ?? '');
include $partials . 'field-text.php';

$name = 'bale_chat_id'; $label = __('Chat ID بله', 'factorchi'); $value = (string) ($s['bale_chat_id'] ?? '');
include $partials . 'field-text.php';

$name = 'bale_message'; $label = __('متن بله', 'factorchi'); $value = (string) ($s['bale_message'] ?? ''); $rows = 3;
include $partials . 'field-textarea.php';
?>
</div>
<?php
include $partials . 'card-section-end.php';
