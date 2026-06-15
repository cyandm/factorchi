<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Survey
{
    public const CRON_HOOK = 'factorchi_survey_send_task';

    public function __construct()
    {
        if (factorchi_get_setting('survey_enabled', 'no') !== 'yes') {
            return;
        }

        add_action('woocommerce_order_status_changed', [$this, 'register_send'], 30, 4);
        add_action(self::CRON_HOOK, [$this, 'process_queue']);
        add_filter('cron_schedules', [$this, 'cron_schedules']);
        add_action('wp_ajax_factorchi_survey_register_comment', [$this, 'ajax_register_comment']);
        add_action('wp_ajax_nopriv_factorchi_survey_register_comment', [$this, 'ajax_register_comment']);

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
            KEY order_number (order_number)
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
     * @param array<string, int|string> $schedules
     * @return array<string, int|string>
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
        if ($new_status !== $target || $order->get_meta('_factorchi_surveyed') === 'yes') {
            return;
        }

        global $wpdb;
        $sms_delay   = (int) factorchi_get_setting('survey_sms_delay_days', 3);
        $email_delay = (int) factorchi_get_setting('survey_email_delay_days', 3);
        $sms_time    = time() + ($sms_delay * DAY_IN_SECONDS);
        $email_time  = time() + ($email_delay * DAY_IN_SECONDS);

        $wpdb->insert(
            self::table_name(),
            [
                'order_number'    => $order_id,
                'phone'           => $order->get_billing_phone(),
                'email'           => $order->get_billing_email(),
                'sent_date'       => $sms_time,
                'sent_time'       => 0,
                'sent_date_email' => $email_time,
                'sent_time_email' => 0,
                'sms_status'      => 0,
                'email_status'    => 0,
            ],
            ['%d', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d']
        );
    }

    public function process_queue(): void
    {
        global $wpdb;
        $table = self::table_name();
        $now   = time();

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table} WHERE (sms_status = 0 AND sent_date <= %d) OR (email_status = 0 AND sent_date_email <= %d) LIMIT 20",
                $now,
                $now
            )
        );

        if (!is_array($rows)) {
            return;
        }

        $dispatcher = new Factorchi_Notify_Dispatcher();

        foreach ($rows as $row) {
            $order_id = (int) $row->order_number;

            if ((int) $row->sms_status === 0 && (int) $row->sent_date <= $now) {
                $dispatcher->send_invoice($order_id, false, ['sms']);
                $wpdb->update(self::table_name(), ['sms_status' => 1], ['id' => (int) $row->id], ['%d'], ['%d']);
            }

            if ((int) $row->email_status === 0 && (int) $row->sent_date_email <= $now) {
                $dispatcher->send_invoice($order_id, false, ['email']);
                $wpdb->update(self::table_name(), ['email_status' => 1], ['id' => (int) $row->id], ['%d'], ['%d']);
            }
        }
    }

    public function ajax_register_comment(): void
    {
        check_ajax_referer('factorchi_survey', 'nonce');

        $order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
        $rating   = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
        $comment  = isset($_POST['comment']) ? sanitize_textarea_field(wp_unslash($_POST['comment'])) : '';

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_send_json_error(['message' => 'invalid_order']);
        }

        $product_id = 0;
        foreach ($order->get_items() as $item) {
            if ($item instanceof WC_Order_Item_Product) {
                $product_id = (int) $item->get_product_id();
                break;
            }
        }

        if ($product_id <= 0) {
            wp_send_json_error(['message' => 'no_product']);
        }

        $comment_id = wp_insert_comment([
            'comment_post_ID'      => $product_id,
            'comment_author'       => $order->get_formatted_billing_full_name(),
            'comment_author_email' => $order->get_billing_email(),
            'comment_content'      => $comment,
            'comment_type'         => 'review',
            'comment_approved'     => 0,
            'user_id'              => (int) $order->get_customer_id(),
        ]);

        if (!$comment_id) {
            wp_send_json_error(['message' => 'insert_failed']);
        }

        add_comment_meta($comment_id, 'rating', $rating);
        add_comment_meta($comment_id, 'fbcommenttype', 'survey');
        $order->update_meta_data('_psurvey_' . $product_id, 'yes');
        $order->update_meta_data('_factorchi_surveyed', 'yes');
        $order->save();

        wp_send_json_success(['comment_id' => $comment_id]);
    }

    public function register_admin_page(): void
    {
        add_submenu_page(
            'factorchi',
            __('نظرسنجی', 'factorchi'),
            __('نظرسنجی', 'factorchi'),
            'manage_woocommerce',
            'factorchi-survey',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page(): void
    {
        global $wpdb;
        $rows = $wpdb->get_results('SELECT * FROM ' . self::table_name() . ' ORDER BY id DESC LIMIT 100');
        include FACTORCHI_DIR . 'admin/views/survey-list.php';
    }
}
