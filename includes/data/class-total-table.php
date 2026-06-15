<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Total_Table
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

    public function render_html(bool $html = true): string
    {
        if (!$this->order) {
            return self::from_cart_html();
        }

        $rows = [
            __('جمع جزء', 'factorchi') => $this->order->get_subtotal(),
            __('تخفیف', 'factorchi')   => $this->order->get_discount_total(),
            __('هزینه ارسال', 'factorchi') => $this->order->get_shipping_total(),
            __('مالیات', 'factorchi')  => $this->order->get_total_tax(),
            __('جمع کل', 'factorchi')  => $this->order->get_total(),
        ];

        $out = '<table class="factorchi-total-table fci-fix-table total-table">';
        foreach ($rows as $label => $amount) {
            if ((float) $amount === 0.0 && $label !== __('جمع کل', 'factorchi')) {
                continue;
            }
            $is_final = $label === __('جمع کل', 'factorchi');
            $out .= '<tr' . ($is_final ? ' class="final"' : '') . '>';
            $out .= '<td>' . esc_html($label) . '</td>';
            $out .= '<td class="fc-cell-price">' . Factorchi_Helper::format_price($amount) . '</td>';
            $out .= '</tr>';
        }
        $out .= '</table>';

        return $out;
    }

    public static function from_cart_html(): string
    {
        if (!WC()->cart) {
            return '';
        }

        $rows = [
            __('جمع جزء', 'factorchi') => WC()->cart->get_subtotal(),
            __('تخفیف', 'factorchi')   => WC()->cart->get_discount_total(),
            __('هزینه ارسال', 'factorchi') => WC()->cart->get_shipping_total(),
            __('مالیات', 'factorchi')  => WC()->cart->get_total_tax(),
            __('جمع کل', 'factorchi')  => WC()->cart->get_total('edit'),
        ];

        $out = '<table class="factorchi-total-table fci-fix-table total-table">';
        foreach ($rows as $label => $amount) {
            if ((float) $amount === 0.0 && $label !== __('جمع کل', 'factorchi')) {
                continue;
            }
            $is_final = $label === __('جمع کل', 'factorchi');
            $out .= '<tr' . ($is_final ? ' class="final"' : '') . '>';
            $out .= '<td>' . esc_html($label) . '</td>';
            $out .= '<td class="fc-cell-price">' . Factorchi_Helper::format_price($amount) . '</td>';
            $out .= '</tr>';
        }
        $out .= '</table>';

        return $out;
    }
}
