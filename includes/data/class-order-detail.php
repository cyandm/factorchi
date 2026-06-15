<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Order_Detail
{
    private int $order_id;
    private string $type;
    /** @var WC_Order|null */
    private $order;

    public function __construct($order_id, string $type = 'invoice')
    {
        $this->order_id = is_array($order_id) ? (int) reset($order_id) : (int) $order_id;
        $this->type     = $type;
        $this->order    = $this->order_id > 0 ? wc_get_order($this->order_id) : null;
    }

    public function get_order_id(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_order_number();
    }

    public function get_order_note(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_customer_note();
    }

    public function get_customer_note(): string
    {
        if (!$this->order) {
            return '';
        }

        $notes = wc_get_order_notes([
            'order_id' => $this->order->get_id(),
            'type'     => 'internal',
            'limit'    => 5,
        ]);

        if ($notes === []) {
            return '';
        }

        $parts = [];
        foreach ($notes as $note) {
            if ($note instanceof stdClass && isset($note->content)) {
                $parts[] = wp_strip_all_tags((string) $note->content);
            }
        }

        return implode(' | ', $parts);
    }
}
