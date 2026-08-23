<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Ajax
{
    public function __construct()
    {
        $actions = [
            'factorchi_send_invoice',
            'factorchi_send_invoice_payment',
            'factorchi_send_invoice_sms',
            'factorchi_send_invoice_sms_payment',
            'factorchi_send_invoice_wa',
        ];

        foreach ($actions as $action) {
            add_action('wp_ajax_' . $action, [$this, 'handle_send']);
        }
    }

    public function handle_send(): void
    {
        check_ajax_referer('factorchi_admin', 'factorchiNonce');

        if (!current_user_can('manage_woocommerce')) {
            wp_send_json(['result' => false, 'message' => 'unauthorized'], 403);
        }

        $order_id = isset($_POST['orderID']) ? (int) $_POST['orderID'] : 0;
        if ($order_id <= 0) {
            wp_send_json(['result' => false, 'message' => 'invalid_order']);
        }

        $action  = current_action();
        $payment = str_contains($action, 'payment');

        $only = null;
        if (str_contains($action, 'sms')) {
            $only = ['sms'];
        } elseif (str_contains($action, 'wa')) {
            $only = ['whatsapp'];
        } elseif (str_contains($action, 'email') || $action === 'wp_ajax_factorchi_send_invoice' || $action === 'wp_ajax_factorchi_send_invoice_payment') {
            $only = null;
        }

        $results = Factorchi_Notify_Dispatcher::instance()->send_invoice($order_id, $payment, $only);
        $success = $results === [] ? false : in_array(true, $results, true);

        wp_send_json(['result' => $success, 'channels' => $results]);
    }
}
