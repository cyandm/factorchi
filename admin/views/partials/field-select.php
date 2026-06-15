<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $name */
/** @var string $label */
/** @var string $value */
/** @var array<string, string> $options */
/** @var string $description */
/** @var string $id */
/** @var string $preview_url */

$id          = $name;
$description = $description ?? '';
$preview_url = $preview_url ?? '';
?>
<div class="fc-field">
    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
    <div class="fc-select-row">
        <select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" class="fc-input fc-select">
            <?php foreach ($options as $opt_value => $opt_label) : ?>
                <option value="<?php echo esc_attr($opt_value); ?>" <?php selected($value, $opt_value); ?>><?php echo esc_html($opt_label); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if ($preview_url !== '') : ?>
            <a href="<?php echo esc_url($preview_url); ?>" target="_blank" rel="noopener" class="button fc-preview-btn"><?php esc_html_e('پیش‌نمایش', 'factorchi'); ?></a>
        <?php endif; ?>
    </div>
    <?php if ($description !== '') : ?>
        <p class="fc-description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>
</div>
<?php
unset($name, $label, $value, $options, $description, $id, $preview_url);
