<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-base.php';

class Factorchi_Channel_Sms extends Factorchi_Notify_Channel_Base
{
    public function __construct()
    {
        parent::__construct('sms', 'channel_sms');
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

        $phone = $order->get_billing_phone();
        if ($phone === '') {
            return false;
        }

        $panels = new Factorchi_SMS_Panels(
            (string) factorchi_get_setting('sms_username', ''),
            (string) factorchi_get_setting('sms_password', ''),
            (string) factorchi_get_setting('sms_sender', '')
        );
        $panels->pattern_id = (string) factorchi_get_setting('sms_pattern_id', '');

        $message = $this->render_template((string) factorchi_get_setting('sms_message', ''), $context);
        $panel   = (string) factorchi_get_setting('sms_panel', 'smsir');

        return $panels->send($panel, $phone, $message);
    }
}
