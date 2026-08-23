<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-base.php';

class Factorchi_Channel_Socials extends Factorchi_Notify_Channel_Base
{
    public function __construct()
    {
        parent::__construct('socials', 'channel_socials');
    }

    /**
     * @param array<string, string> $context
     */
    public function send(int $order_id, array $context): bool
    {
        $api_url = (string) factorchi_get_setting('socials_api_url', '');
        if ($api_url === '' || !factorchi_is_safe_remote_url($api_url)) {
            return false;
        }

        $order   = wc_get_order($order_id);
        $message = $this->render_template((string) factorchi_get_setting('socials_message', ''), $context);

        $response = wp_remote_post($api_url, [
            'timeout' => 30,
            'body'    => [
                'message'  => $message,
                'order_id' => $order_id,
                'phone'    => $order ? $order->get_billing_phone() : '',
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        return $code >= 200 && $code < 300;
    }
}
