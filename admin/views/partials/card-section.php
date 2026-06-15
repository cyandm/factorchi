<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $title */
/** @var string $description */
/** @var string $class */

$description = $description ?? '';
$class       = $class ?? '';
?>
<div class="fc-section <?php echo esc_attr($class); ?>">
    <?php if ($title !== '') : ?>
        <div class="fc-section-head">
            <h3><?php echo esc_html($title); ?></h3>
            <?php if ($description !== '') : ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="fc-section-body">
