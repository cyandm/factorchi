<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Assets
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
    }

    public function admin_assets(string $hook): void
    {
        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        $is_order = $hook === 'post.php' || $hook === 'post-new.php' || $hook === 'woocommerce_page_wc-orders';
        $is_orders_list = $screen && $screen->id === 'edit-shop_order';

        if (!$is_order && !$is_orders_list && (!$screen || !in_array($screen->id, ['shop_order', 'woocommerce_page_wc-orders', 'toplevel_page_factorchi'], true))) {
            if ($hook !== 'toplevel_page_factorchi') {
                return;
            }
        }

        wp_enqueue_style(
            'factorchi-admin',
            FACTORCHI_CSS_URL . 'admin.css',
            [],
            FACTORCHI_VERSION
        );

        wp_enqueue_script(
            'factorchi-admin',
            FACTORCHI_JS_URL . 'admin-scripts.js',
            ['jquery'],
            FACTORCHI_VERSION,
            true
        );

        wp_localize_script('factorchi-admin', 'FACTORCHI_JS_DATA', [
            'base_url'           => home_url('/'),
            'waiting'            => __('در حال ارسال...', 'factorchi'),
            'send_invoice'       => __('ارسال فاکتور', 'factorchi'),
            'send_payment_link'  => __('ارسال لینک پرداخت', 'factorchi'),
            'send_invoice_sms'   => __('ارسال پیامک', 'factorchi'),
            'invoice_send'       => __('فاکتور ارسال شد.', 'factorchi'),
            'payment_link_send'  => __('لینک پرداخت ارسال شد.', 'factorchi'),
            'error_happend'      => __('خطایی رخ داد.', 'factorchi'),
            'print_mode'         => factorchi_get_setting('bulk_use_compact', 'yes') === 'yes' ? 'compact' : '',
            'print_size'         => (string) factorchi_get_setting('print_page_size', 'a4'),
            'print_per_page'     => (string) factorchi_get_setting('print_per_page', '1'),
        ]);
    }
}
