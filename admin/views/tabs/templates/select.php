<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var array<string, mixed> $s */
/** @var string $partials */

$template_fields = [
    'invoice_default_view' => __('قالب پیش‌فرض فاکتور', 'factorchi'),
    'pre_invoice_view'     => __('قالب پیش‌فاکتور', 'factorchi'),
    'post_label_view'      => __('قالب برچسب پستی', 'factorchi'),
    'mini_label_view'      => __('قالب برچسب چاپی', 'factorchi'),
];

$title = __('انتخاب قالب', 'factorchi');
$description = __('قالب هر نوع سند را انتخاب کنید و با پیش‌نمایش بررسی کنید.', 'factorchi');
include $partials . 'card-section.php';

foreach ($template_fields as $key => $label) {
    $options = Factorchi_Template_Registry::get_options_for_setting($key);
    $default = 'modern';
    if ($key === 'post_label_view') {
        $default = 'modern-a4';
    } elseif ($key === 'mini_label_view') {
        $default = '50x80';
    }
    $current = (string) ($s[$key] ?? $default);

    if (in_array($key, ['invoice_default_view', 'pre_invoice_view'], true)) {
        $current = Factorchi_Settings::normalize_invoice_view($current);
    } elseif ($key === 'post_label_view') {
        $current = Factorchi_Settings::normalize_post_label_view($current);
    } elseif ($key === 'mini_label_view') {
        $current = Factorchi_Settings::normalize_mini_label_view($current);
    }

    if (!isset($options[$current]) && $current !== '') {
        $options[$current] = Factorchi_Template_Registry::label_for_slug($current);
    }

    $preview_base = Factorchi_Template_Registry::preview_base_url($key);
    $preview_href = Factorchi_Template_Registry::preview_url($key, $current);
?>
    <div class="fc-field" data-preview-base="<?php echo esc_attr($preview_base); ?>">
        <label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
        <div class="fc-select-row">
            <select id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" class="fc-input fc-select fc-template-select">
                <?php foreach ($options as $opt_value => $opt_label) : ?>
                    <option value="<?php echo esc_attr($opt_value); ?>" <?php selected($current, $opt_value); ?>><?php echo esc_html($opt_label); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($preview_href !== '') : ?>
                <a href="<?php echo esc_url($preview_href); ?>" target="_blank" rel="noopener" class="button fc-preview-btn"><?php esc_html_e('پیش‌نمایش', 'factorchi'); ?></a>
            <?php endif; ?>
        </div>
    </div>
<?php
}

include $partials . 'card-section-end.php';
