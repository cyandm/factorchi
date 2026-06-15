<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-base.php';

class Factorchi_Channel_Bale extends Factorchi_Notify_Channel_Base
{
    public function __construct()
    {
        parent::__construct('bale', 'channel_bale');
    }

    /**
     * @param array<string, string> $context
     */
    public function send(int $order_id, array $context): bool
    {
        $token  = (string) factorchi_get_setting('bale_bot_token', '');
        $chatId = (string) factorchi_get_setting('bale_chat_id', '');

        if ($token === '' || $chatId === '') {
            return false;
        }

        $message = $this->render_template((string) factorchi_get_setting('bale_message', ''), $context);
        $url     = 'https://tapi.bale.ai/bot' . $token . '/sendMessage';

        $response = wp_remote_post($url, [
            'timeout' => 30,
            'body'    => json_encode([
                'chat_id' => $chatId,
                'text'    => $message,
            ]),
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode((string) wp_remote_retrieve_body($response), true);
        return is_array($body) && !empty($body['ok']);
    }
}
