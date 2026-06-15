<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $name */
/** @var string $label */
/** @var bool $checked */
/** @var string $description */
/** @var string $class */

$description = $description ?? '';
$class       = $class ?? '';
?>
<div class="fc-toggle-row <?php echo esc_attr($class); ?>">
    <div class="fc-toggle-label">
        <span><?php echo esc_html($label); ?></span>
        <?php if ($description !== '') : ?>
            <p class="fc-description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </div>
    <label class="fc-switch">
        <input type="checkbox" name="<?php echo esc_attr($name); ?>" value="1" <?php checked($checked); ?> />
        <span class="fc-slider"></span>
    </label>
</div>
<?php
unset($name, $label, $checked, $description, $class);
