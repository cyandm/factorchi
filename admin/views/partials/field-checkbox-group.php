<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $label */
/** @var string $name */
/** @var array<string, string> $options */
/** @var array<int, string> $selected */
/** @var string $description */

$description = $description ?? '';
$name        = $name ?? '';
$selected    = $selected ?? [];
?>
<div class="fc-field">
    <?php if ($label !== '') : ?>
        <label class="fc-group-label"><?php echo esc_html($label); ?></label>
    <?php endif; ?>
    <div class="fc-checkbox-grid">
        <?php foreach ($options as $opt_value => $opt_label) : ?>
            <label class="fc-checkbox-item">
                <input
                    type="checkbox"
                    name="<?php echo esc_attr($name); ?>[]"
                    value="<?php echo esc_attr((string) $opt_value); ?>"
                    <?php checked(in_array((string) $opt_value, array_map('strval', $selected), true)); ?>
                />
                <span><?php echo esc_html($opt_label); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
    <?php if ($description !== '') : ?>
        <p class="fc-description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>
</div>
