<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Orders_Table
{
    private ?string $state;
    private ?string $status;
    private ?string $start;
    private ?string $end;
    private Factorchi_Labels $labels;
    /** @var array<int, string> */
    private array $sections;

    public float $total_amount = 0.0;
    public float $total_provider_price = 0.0;

    /**
     * @param array<int, string> $sections
     */
    public function __construct(
        ?string $state,
        ?string $status,
        ?string $start,
        ?string $end,
        Factorchi_Labels $labels,
        array $sections = []
    ) {
        $this->state    = $state;
        $this->status   = $status;
        $this->start    = $start;
        $this->end      = $end;
        $this->labels   = $labels;
        $this->sections = $sections;
    }

    public function is_status(string $key): bool
    {
        if ($key === 'order-status' || $key === 'order-email') {
            return false;
        }

        if ($this->sections === []) {
            return true;
        }

        return in_array($key, $this->sections, true);
    }

    public function get_products(WC_Order $order): string
    {
        $parts = [];

        foreach ($order->get_items() as $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }
            $parts[] = esc_html($item->get_name()) . ' &times; ' . esc_html((string) $item->get_quantity());
        }

        return implode('<br>', $parts);
    }

    public function get_price(WC_Order $order): string
    {
        $amount = (float) $order->get_total();
        $this->total_amount += $amount;

        return Factorchi_Helper::format_price($amount);
    }

    public function get_provider_price(WC_Order $order): string
    {
        $amount = $this->calculate_provider_cost($order);
        $this->total_provider_price += $amount;

        return Factorchi_Helper::format_price($amount);
    }

    private function calculate_provider_cost(WC_Order $order): float
    {
        $total = 0.0;

        foreach ($order->get_items() as $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }

            $product = $item->get_product();
            if (!$product) {
                continue;
            }

            $cost = (float) $product->get_meta('_cost');
            if ($cost <= 0) {
                $cost = (float) get_post_meta($product->get_id(), '_cost', true);
            }
            if ($cost <= 0) {
                $cost = (float) get_post_meta($product->get_id(), '_purchase_price', true);
            }

            $total += $cost * (float) $item->get_quantity();
        }

        return $total;
    }

    /**
     * @param array<int, int> $order_ids
     */
    public function render_rows(array $order_ids): string
    {
        $html = '';
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                continue;
            }
            $html .= '<tr>';
            $html .= '<td>' . esc_html((string) $order->get_id()) . '</td>';
            $html .= '<td>' . esc_html($order->get_formatted_billing_full_name()) . '</td>';
            $html .= '<td>' . Factorchi_Helper::format_price($order->get_total()) . '</td>';
            $html .= '<td>' . esc_html(Factorchi_Helper::date_format($order->get_date_created() ? $order->get_date_created()->getTimestamp() : time())) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
}
