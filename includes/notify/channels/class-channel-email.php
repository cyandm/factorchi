<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-base.php';

class Factorchi_Channel_Email extends Factorchi_Notify_Channel_Base
{
    public function __construct()
    {
        parent::__construct('email', 'channel_email');
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

        $to = $order->get_billing_email();
        if ($to === '') {
            return false;
        }

        $subject = sanitize_text_field($this->render_template((string) factorchi_get_setting('email_subject', ''), $context));
        $body    = $this->render_template((string) factorchi_get_setting('email_body', ''), $context);
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        $html = '<div dir="rtl" style="font-family:Tahoma,sans-serif;">' . nl2br(esc_html($body)) . '</div>';

        return wp_mail($to, $subject, $html, $headers);
    }
}
