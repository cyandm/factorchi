<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Notify_Dispatcher
{
    private static ?Factorchi_Notify_Dispatcher $instance = null;

    /** @var array<int, Factorchi_Notify_Channel> */
    private array $channels = [];

    private bool $hooks_registered = false;

    public static function instance(): Factorchi_Notify_Dispatcher
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->channels = [
            new Factorchi_Channel_Email(),
            new Factorchi_Channel_Sms(),
            new Factorchi_Channel_Whatsapp(),
            new Factorchi_Channel_Socials(),
            new Factorchi_Channel_Telegram(),
            new Factorchi_Channel_Bale(),
        ];

        $this->register_hooks();
    }

    private function register_hooks(): void
    {
        if ($this->hooks_registered) {
            return;
        }

        add_action('woocommerce_order_status_changed', [$this, 'maybe_auto_send'], 20, 4);
        $this->hooks_registered = true;
    }

    /**
     * @param string[]|null $only_channels
     * @return array<string, bool>
     */
    public function send_invoice(int $order_id, bool $payment = false, ?array $only_channels = null): array
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            return [];
        }

        $invoice_url = factorchi_get_invoice_url($order_id, 'invoice', '', $payment);
        $context     = [
            'order_id'       => (string) $order_id,
            'customer_name'  => $order->get_formatted_billing_full_name(),
            'invoice_url'    => $invoice_url,
            'payment_url'    => $order->get_checkout_payment_url(),
            'shop_name'      => (string) factorchi_get_setting('shop_name', get_bloginfo('name')),
        ];

        $results = [];
        foreach ($this->channels as $channel) {
            if ($only_channels !== null && !in_array($channel->get_id(), $only_channels, true)) {
                continue;
            }
            if (!$channel->is_enabled()) {
                continue;
            }

            $ok = $channel->send($order_id, $context);
            $results[$channel->get_id()] = $ok;

            $order->add_order_note(
                sprintf(
                    __('Factorchi: ارسال %1$s %2$s', 'factorchi'),
                    $channel->get_id(),
                    $ok ? __('موفق', 'factorchi') : __('ناموفق', 'factorchi')
                ),
                false,
                true
            );
        }

        if (in_array(true, $results, true)) {
            Factorchi_Helper::mark_received($order_id);
        }

        return $results;
    }

    public function maybe_auto_send(int $order_id, string $old_status, string $new_status, WC_Order $order): void
    {
        $statuses = (array) factorchi_get_setting('auto_send_statuses', []);
        if (!in_array($new_status, $statuses, true)) {
            return;
        }

        $channels = (array) factorchi_get_setting('auto_send_channels', []);
        if ($channels === []) {
            return;
        }
        $this->send_invoice($order_id, false, $channels);
    }
}
