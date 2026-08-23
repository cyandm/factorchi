<?php

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', Factorchi_Survey::table_name())) === Factorchi_Survey::table_name();
$hpos         = class_exists('\Automattic\WooCommerce\Utilities\OrderUtil')
    && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
$cron_next    = wp_next_scheduled(Factorchi_Survey::CRON_HOOK);
?>
<div class="wrap factorchi-admin">
    <div class="fc-header">
        <h1><?php esc_html_e('وضعیت سیستم', 'factorchi'); ?></h1>
        <p><?php esc_html_e('بررسی سلامت افزونه و وابستگی‌ها', 'factorchi'); ?></p>
    </div>
    <hr class="wp-header-end" />

    <div class="fc-card">
        <h2 class="fc-card-title"><?php esc_html_e('وضعیت', 'factorchi'); ?></h2>
        <table class="fc-status-table">
            <tbody>
                <tr>
                    <th><?php esc_html_e('نسخه افزونه', 'factorchi'); ?></th>
                    <td><?php echo esc_html(FACTORCHI_VERSION); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('ووکامرس', 'factorchi'); ?></th>
                    <td><?php echo class_exists('WooCommerce') ? esc_html(WC()->version) : '—'; ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('HPOS', 'factorchi'); ?></th>
                    <td><?php echo $hpos ? esc_html__('فعال', 'factorchi') : esc_html__('غیرفعال', 'factorchi'); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('جدول اطلاع رسانی', 'factorchi'); ?></th>
                    <td><?php echo $table_exists ? esc_html__('موجود', 'factorchi') : esc_html__('ناموجود', 'factorchi'); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Cron اطلاع رسانی', 'factorchi'); ?></th>
                    <td><?php echo $cron_next ? esc_html(date_i18n('Y-m-d H:i', $cron_next)) : esc_html__('زمان‌بندی نشده', 'factorchi'); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('قالب‌های فاکتور', 'factorchi'); ?></th>
                    <td><?php echo esc_html((string) count(Factorchi_Template_Registry::get_options('invoice'))); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
