<?php

if (!defined('ABSPATH')) {
    exit;
}

$status_labels = [
    0 => __('در انتظار', 'factorchi'),
    1 => __('موفق', 'factorchi'),
    2 => __('ناموفق', 'factorchi'),
    3 => __('در حال ارسال', 'factorchi'),
];
?>
<div class="wrap factorchi-admin">
    <div class="fc-header">
        <h1><?php esc_html_e('اطلاع رسانی (ارسال فاکتور)', 'factorchi'); ?></h1>
        <p><?php esc_html_e('۱۰۰ مورد اخیر صف اطلاع‌رسانی و ارسال فاکتور با پیامک', 'factorchi'); ?></p>
    </div>
    <hr class="wp-header-end" />

    <div class="fc-card">
        <h2 class="fc-card-title"><?php esc_html_e('صف اطلاع رسانی (SMS)', 'factorchi'); ?></h2>
        <table class="fc-status-table fc-data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php esc_html_e('سفارش', 'factorchi'); ?></th>
                    <th><?php esc_html_e('موبایل', 'factorchi'); ?></th>
                    <th><?php esc_html_e('زمان ارسال', 'factorchi'); ?></th>
                    <th><?php esc_html_e('وضعیت SMS', 'factorchi'); ?></th>
                    <th><?php esc_html_e('تلاش‌ها', 'factorchi'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)) : ?>
                    <tr><td colspan="6"><?php esc_html_e('موردی یافت نشد.', 'factorchi'); ?></td></tr>
                <?php else : ?>
                    <?php foreach ($rows as $row) : ?>
                        <?php
                        $sms_status = (int) $row->sms_status;
                        $status_text = $status_labels[$sms_status] ?? (string) $sms_status;
                        $scheduled = (int) $row->sent_date > 0
                            ? Factorchi_Helper::date_format((int) $row->sent_date)
                            : '—';
                        $attempts = $sms_status === 1
                            ? '—'
                            : (string) (int) $row->sent_time;
                        ?>
                        <tr>
                            <td><?php echo esc_html((string) $row->id); ?></td>
                            <td><?php echo esc_html((string) $row->order_number); ?></td>
                            <td><?php echo esc_html((string) $row->phone); ?></td>
                            <td><?php echo esc_html($scheduled); ?></td>
                            <td><?php echo esc_html($status_text); ?></td>
                            <td><?php echo esc_html($attempts); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
