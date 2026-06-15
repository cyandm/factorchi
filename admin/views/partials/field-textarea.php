<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $name */
/** @var string $label */
/** @var string $value */
/** @var int $rows */
/** @var string $description */
/** @var string $id */

$rows        = $rows ?? 4;
$id          = $name;
$description = $description ?? '';
?>
<div class="fc-field">
    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
    <textarea id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" rows="<?php echo (int) $rows; ?>" class="fc-input fc-textarea"><?php echo esc_textarea($value); ?></textarea>
    <?php if ($description !== '') : ?>
        <p class="fc-description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>
</div>
<?php
unset($name, $label, $value, $rows, $description, $id);
