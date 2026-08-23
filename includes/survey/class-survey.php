<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Delayed invoice notify queue (SMS).
 * Settings keys keep the legacy "survey_*" names for backwards compatibility.
 */
class Factorchi_Survey
{
    public const CRON_HOOK = 'factorchi_survey_send_task';
    public const META_QUEUED = '_factorchi_surveyed';

    /** sms_status: 0=pending, 1=success, 2=failed, 3=processing */
    public const STATUS_PENDING    = 0;
    public const STATUS_SUCCESS    = 1;
    public const STATUS_FAILED     = 2;
    public const STATUS_PROCESSING = 3;

    public function __construct()
    {
        // Custom schedule must stay registered even when the feature is off,
        // otherwise WP cannot resolve events scheduled on activation.
        add_filter('cron_schedules', [$this, 'cron_schedules']);

        if (factorchi_get_setting('survey_enabled', 'no') !== 'yes') {
            return;
        }

        self::schedule_cron();
        self::install_table();

        add_action('woocommerce_order_status_changed', [$this, 'register_send'], 30, 4);
        add_action(self::CRON_HOOK, [$this, 'process_queue']);

        if (is_admin()) {
            add_action('admin_menu', [$this, 'register_admin_page']);
        }
    }

    public static function table_name(): string
    {
        global $wpdb;
        return $wpdb->prefix . 'factorchi_survey';
    }

    public static function install_table(): void
    {
        global $wpdb;

        $table   = self::table_name();
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            order_number BIGINT UNSIGNED NOT NULL,
            phone VARCHAR(50) NOT NULL DEFAULT '',
            email VARCHAR(190) NOT NULL DEFAULT '',
            sent_date BIGINT UNSIGNED NOT NULL DEFAULT 0,
            sent_time INT UNSIGNED NOT NULL DEFAULT 0,
            sent_date_email BIGINT UNSIGNED NOT NULL DEFAULT 0,
            sent_time_email INT UNSIGNED NOT NULL DEFAULT 0,
            sms_status TINYINT NOT NULL DEFAULT 0,
            email_status TINYINT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY order_number (order_number),
            KEY sms_queue (sms_status, sent_date)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public static function schedule_cron(): void
    {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time() + 300, 'factorchi_thirty_minutes', self::CRON_HOOK);
        }
    }

    public static function deactivate(): void
    {
        wp_clear_scheduled_hook(self::CRON_HOOK);
    }

    /**
     * @param array<string, array<string, int|string>> $schedules
     * @return array<string, array<string, int|string>>
     */
    public function cron_schedules(array $schedules): array
    {
        $schedules['factorchi_thirty_minutes'] = [
            'interval' => 1800,
            'display'  => __('هر ۳۰ دقیقه (Factorchi)', 'factorchi'),
        ];
        return $schedules;
    }

    public function register_send(int $order_id, string $old_status, string $new_status, WC_Order $order): void
    {
        $target = (string) factorchi_get_setting('survey_status', 'completed');
        if ($new_status !== $target || $order->get_meta(self::META_QUEUED) === 'yes') {
            return;
        }

        global $wpdb;
        $sms_delay = (int) factorchi_get_setting('survey_sms_delay_days', 3);
        $sms_time  = time() + ($sms_delay * DAY_IN_SECONDS);

        // Mark first to reduce duplicate inserts under concurrent status hooks.
        $order->update_meta_data(self::META_QUEUED, 'yes');
        $order->save();

        $inserted = $wpdb->insert(
            self::table_name(),
            [
                'order_number'    => $order_id,
                'phone'           => $order->get_billing_phone(),
                'email'           => '',
                'sent_date'       => $sms_time,
                'sent_time'       => 0,
                'sent_date_email' => 0,
                'sent_time_email' => 0,
                'sms_status'      => self::STATUS_PENDING,
                'email_status'    => 1,
            ],
            ['%d', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d']
        );

        if (!$inserted) {
            // Unique key collision or DB error — leave meta set so we do not retry forever.
            return;
        }
    }

    public function process_queue(): void
    {
        global $wpdb;
        $table     = self::table_name();
        $now       = time();
        $deadline  = time() + 20;
        $batch     = (int) apply_filters('factorchi_notify_queue_batch', 40);

        // Reclaim stale "processing" rows (crashed workers) after 15 minutes.
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$table} SET sms_status = %d WHERE sms_status = %d AND sent_date <= %d",
                self::STATUS_PENDING,
                self::STATUS_PROCESSING,
                $now - (15 * MINUTE_IN_SECONDS)
            )
        );

        while (time() < $deadline) {
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$table} WHERE sms_status = %d AND sent_date <= %d ORDER BY id ASC LIMIT %d",
                    self::STATUS_PENDING,
                    $now,
                    max(1, min(100, $batch))
                )
            );

            if (!is_array($rows) || $rows === []) {
                return;
            }

            $dispatcher = Factorchi_Notify_Dispatcher::instance();

            foreach ($rows as $row) {
                if (time() >= $deadline) {
                    return;
                }

                $id = (int) $row->id;

                // Atomic claim — prevents double SMS under overlapping cron/spawns.
                $claimed = $wpdb->update(
                    $table,
                    [
                        'sms_status' => self::STATUS_PROCESSING,
                        'sent_date'  => time(),
                    ],
                    [
                        'id'         => $id,
                        'sms_status' => self::STATUS_PENDING,
                    ],
                    ['%d', '%d'],
                    ['%d', '%d']
                );

                if (!$claimed) {
                    continue;
                }

                $order_id = (int) $row->order_number;
                $results  = $dispatcher->send_invoice($order_id, false, ['sms']);
                $ok       = !empty($results['sms']);

                if ($ok) {
                    $wpdb->update(
                        $table,
                        [
                            'sms_status' => self::STATUS_SUCCESS,
                            'sent_time'  => time(),
                        ],
                        ['id' => $id],
                        ['%d', '%d'],
                        ['%d']
                    );
                    continue;
                }

                $attempts = (int) $row->sent_time + 1;
                if ($attempts >= 3) {
                    $wpdb->update(
                        $table,
                        [
                            'sms_status' => self::STATUS_FAILED,
                            'sent_time'  => $attempts,
                        ],
                        ['id' => $id],
                        ['%d', '%d'],
                        ['%d']
                    );
                    continue;
                }

                $wpdb->update(
                    $table,
                    [
                        'sms_status' => self::STATUS_PENDING,
                        'sent_date'  => time() + HOUR_IN_SECONDS,
                        'sent_time'  => $attempts,
                    ],
                    ['id' => $id],
                    ['%d', '%d', '%d'],
                    ['%d']
                );
            }
        }
    }

    public function register_admin_page(): void
    {
        add_submenu_page(
            'factorchi',
            __('اطلاع رسانی (ارسال فاکتور)', 'factorchi'),
            __('اطلاع رسانی (ارسال فاکتور)', 'factorchi'),
            'manage_woocommerce',
            'factorchi-survey',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('Unauthorized', 'factorchi'), '', ['response' => 403]);
        }

        global $wpdb;
        $rows = $wpdb->get_results(
            'SELECT * FROM ' . self::table_name() . ' ORDER BY id DESC LIMIT 100'
        );
        include FACTORCHI_DIR . 'admin/views/survey-list.php';
    }
}
