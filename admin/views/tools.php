<?php

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap factorchi-admin">
    <div class="fc-header">
        <h1><?php esc_html_e('ابزارهای فاکتورچی', 'factorchi'); ?></h1>
        <p><?php esc_html_e('لینک‌های سریع و شورت‌کدهای کاربردی', 'factorchi'); ?></p>
    </div>

    <div class="fc-card">
        <h2 class="fc-card-title"><?php esc_html_e('ابزارها', 'factorchi'); ?></h2>

        <div class="fc-field">
            <label><?php esc_html_e('شورت‌کد پیش‌فاکتور', 'factorchi'); ?></label>
            <code style="display:block;padding:12px;background:#f8fafc;border-radius:8px;font-size:14px;">[factorchi-pre-invoice]</code>
            <p class="fc-description"><?php esc_html_e('این شورت‌کد را در برگه یا ویجت قرار دهید.', 'factorchi'); ?></p>
        </div>
    </div>
</div>
