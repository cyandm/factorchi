<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-base.php';

class Factorchi_Channel_Whatsapp extends Factorchi_Notify_Channel_Base
{
    public function __construct()
    {
        parent::__construct('whatsapp', 'channel_whatsapp');
    }

    /**
     * @param array<string, string> $context
     */
    public function send(int $order_id, array $context): bool
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            return false;
        }

        $api_url = (string) factorchi_get_setting('whatsapp_api_url', '');
        if ($api_url === '') {
            $phone   = preg_replace('/\D+/', '', $order->get_billing_phone());
            $message = rawurlencode($this->render_template((string) factorchi_get_setting('whatsapp_message', ''), $context));
            if ($phone === '') {
                return false;
            }
            return true;
        }

        $message = $this->render_template((string) factorchi_get_setting('whatsapp_message', ''), $context);

        $response = wp_remote_post($api_url, [
            'timeout' => 30,
            'body'    => [
                'phone'   => $order->get_billing_phone(),
                'message' => $message,
                'order_id'=> $order_id,
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        return $code >= 200 && $code < 300;
    }
}
