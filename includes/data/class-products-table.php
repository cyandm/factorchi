<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Products_Table
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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get_list(): array
    {
        $items = [];
        if (!$this->order) {
            return $items;
        }

        foreach ($this->order->get_items() as $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }
            $product = $item->get_product();
            $label   = Factorchi_View_Render::build_product_label(
                $item->get_name(),
                $product instanceof WC_Product ? $product : null,
                $item
            );
            $items[] = [
                'name'     => $label,
                'sku'      => $product instanceof WC_Product ? (string) $product->get_sku() : '',
                'qty'      => $item->get_quantity(),
                'total'    => $item->get_total(),
                'subtotal' => $item->get_subtotal(),
            ];
        }

        return $items;
    }

    public function render_list_html(): string
    {
        $items = $this->get_list();
        if ($items === []) {
            return '';
        }

        $html = '<ul>';
        foreach ($items as $item) {
            $html .= '<li>' . esc_html((string) $item['name']) . ' &times; ' . esc_html((string) $item['qty']) . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Build products table from cart for pre-invoice.
     */
    public static function from_cart(): string
    {
        if (!WC()->cart) {
            return '';
        }

        $rows = '';
        $show_image = Factorchi_View_Render::should_show_product_image('pre-invoice');
        $show_row   = Factorchi_View_Render::should_show_product_row_number();
        $index      = 0;
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product = $cart_item['data'] ?? null;
            if (!$product instanceof WC_Product) {
                continue;
            }
            $index++;
            $qty   = (int) ($cart_item['quantity'] ?? 1);
            $price = (float) $product->get_price() * $qty;
            $parts = Factorchi_View_Render::build_product_label_parts($product->get_name(), $product);
            $rows .= '<tr>';
            if ($show_row) {
                $rows .= Factorchi_View_Render::format_product_row_number_cell($index);
            }
            $rows .= Factorchi_View_Render::format_product_name_cell_parts($parts, $product, $show_image);
            $rows .= '<td class="fc-cell-qty">' . esc_html((string) $qty) . '</td>';
            $rows .= '<td class="fc-cell-price">' . Factorchi_Helper::format_price($price) . '</td>';
            $rows .= '</tr>';
        }

        if ($rows === '') {
            return '';
        }

        return self::wrap_table($rows);
    }

    public static function wrap_table(string $rows): string
    {
        $html  = '<table class="factorchi-products-table fci-form-table products-table">';
        $html .= Factorchi_View_Render::products_table_thead_html(__('قیمت', 'factorchi'));
        $html .= '<tbody>' . $rows . '</tbody></table>';
        return $html;
    }
}
