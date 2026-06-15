<?php

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap factorchi-admin">
    <div class="fc-header">
        <h1><?php esc_html_e('صف نظرسنجی', 'factorchi'); ?></h1>
        <p><?php esc_html_e('۱۰۰ درخواست اخیر نظرسنجی پس از خرید', 'factorchi'); ?></p>
    </div>

    <div class="fc-card">
        <h2 class="fc-card-title"><?php esc_html_e('صف ارسال', 'factorchi'); ?></h2>
        <table class="fc-status-table fc-data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?php esc_html_e('سفارش', 'factorchi'); ?></th>
                    <th><?php esc_html_e('موبایل', 'factorchi'); ?></th>
                    <th><?php esc_html_e('ایمیل', 'factorchi'); ?></th>
                    <th>SMS</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)) : ?>
                    <tr><td colspan="6"><?php esc_html_e('موردی یافت نشد.', 'factorchi'); ?></td></tr>
                <?php else : ?>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td><?php echo esc_html((string) $row->id); ?></td>
                            <td><?php echo esc_html((string) $row->order_number); ?></td>
                            <td><?php echo esc_html((string) $row->phone); ?></td>
                            <td><?php echo esc_html((string) $row->email); ?></td>
                            <td><?php echo esc_html((string) $row->sms_status); ?></td>
                            <td><?php echo esc_html((string) $row->email_status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
