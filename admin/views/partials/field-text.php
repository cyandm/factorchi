<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $name */
/** @var string $label */
/** @var string $value */
/** @var string $type */
/** @var string $description */
/** @var string $id */
/** @var string $class */
/** @var string $placeholder */

$type        = $type ?? 'text';
$id          = $name;
$class       = $class ?? '';
$description = $description ?? '';
$placeholder = $placeholder ?? '';

$allowed_types = ['text', 'email', 'url', 'number', 'tel', 'password'];
if (!in_array($type, $allowed_types, true)) {
    $type = 'text';
}
?>
<div class="fc-field <?php echo esc_attr($class); ?>">
    <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
    <input
        type="<?php echo esc_attr($type); ?>"
        id="<?php echo esc_attr($id); ?>"
        name="<?php echo esc_attr($name); ?>"
        value="<?php echo esc_attr($value); ?>"
        placeholder="<?php echo esc_attr($placeholder); ?>"
        class="fc-input"
    />
    <?php if ($description !== '') : ?>
        <p class="fc-description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>
</div>
<?php
unset($name, $label, $value, $type, $description, $id, $class, $placeholder);
