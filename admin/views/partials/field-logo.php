<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var string $value */
?>
<div class="fc-field fc-logo-field">
    <label for="shop_logo"><?php esc_html_e('لوگوی فروشگاه', 'factorchi'); ?></label>
    <p class="fc-description"><?php esc_html_e('لوگو در بالای فاکتور نمایش داده می‌شود.', 'factorchi'); ?></p>
    <div class="fc-logo-row">
        <input type="url" id="shop_logo" name="shop_logo" value="<?php echo esc_attr($value); ?>" class="fc-input" placeholder="https://..." />
        <button type="button" class="button" id="fc-pick-logo"><?php esc_html_e('انتخاب از رسانه', 'factorchi'); ?></button>
    </div>
    <div class="fc-logo-preview" id="fc-logo-preview" <?php echo $value === '' ? 'style="display:none"' : ''; ?>>
        <?php if ($value !== '') : ?>
            <img src="<?php echo esc_url($value); ?>" alt="" />
        <?php endif; ?>
    </div>
</div>