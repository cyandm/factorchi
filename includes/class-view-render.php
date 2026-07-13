<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_View_Render
{
    public static function should_show_product_image(string $type): bool
    {
        if (!in_array($type, ['invoice', 'pre-invoice'], true)) {
            return false;
        }

        return factorchi_get_setting('show_product_image', 'no') === 'yes';
    }

    public static function get_product_image_size(): int
    {
        return max(24, min(200, (int) factorchi_get_setting('product_image_size', 70)));
    }

    public static function get_product_thumbnail_html(?WC_Product $product): string
    {
        if (!$product) {
            return '';
        }

        $image_id = $product->get_image_id();
        if (!$image_id) {
            return '';
        }

        $size = self::get_product_image_size();
        $url  = wp_get_attachment_image_url($image_id, [$size, $size]);
        if ($url === false || $url === '') {
            $url = wp_get_attachment_image_url($image_id, 'thumbnail');
        }
        if ($url === false || $url === '') {
            return '';
        }

        return '<img src="' . esc_url($url) . '" alt="" class="fc-product-thumb" width="'
            . (int) $size . '" height="' . (int) $size . '" loading="lazy" decoding="async" />';
    }

    public static function format_product_name_cell(string $name, string $sku, ?WC_Product $product, bool $with_image): string
    {
        $thumb = $with_image ? self::get_product_thumbnail_html($product) : '';

        $html  = '<td class="fc-product-cell"><div class="fc-product-row">';
        $html .= '<span class="fc-product-text">' . esc_html($name);
        if ($sku !== '') {
            $html .= '<br><small>' . esc_html($sku) . '</small>';
        }
        $html .= '</span>';
        if ($thumb !== '') {
            $html .= $thumb;
        }
        $html .= '</div></td>';

        return $html;
    }

    public static function build_data(int $order_id, string $type): array
    {
        $shop     = new Factorchi_Shop($order_id, $type);
        $customer = new Factorchi_Customer_Data($order_id, $type);
        $is_post_label = $type === 'post-label';
        $total    = $is_post_label ? null : new Factorchi_Total_Table($order_id, $type);
        $products = new Factorchi_Products_Table($order_id, $type);

        $data = [
            'title'               => $shop->title_holder(true),
            'url'                 => $shop->url_holder(true),
            'email'               => '',
            'phone'               => $shop->phone_holder(true),
            'logo'                => $shop->logo_holder(true),
            'print_date'          => $shop->print_date_holder(),
            'transmission_date'   => $shop->transmission_date_holder(),
            'order_id_html'       => $shop->order_id_holder(),
            'barcode'             => $shop->barcode_holder(),
            'sender'              => $shop->address_holder(true),
            'postcode'            => $shop->postal_code_holder(true),
            'economical'          => $shop->economical_num_holder(true),
            'reg'                 => $shop->reg_num_holder(true),
            'recipient'           => $customer->address_holder(true),
            'full_name'           => $customer->full_name_holder(true),
            'r_postcode'          => $customer->postal_code_holder(true),
            'r_phone'             => $customer->phone_holder(true),
            'r_email'             => '',
            'order_date'          => $customer->order_date_holder(true),
            'pay_method'          => $customer->payment_method_holder(true),
            'trans_id'            => $customer->transaction_id_holder(true),
            'national_id'         => $customer->national_id_holder(true),
            'shipping'            => $customer->shipping_method_holder(true),
            'user_meta'           => $customer->user_meta_holder(),
            'order_meta'          => $customer->order_meta_holder(),
            'delivery_date'       => $customer->delivery_date_holder(),
            'customer_note'       => $shop->customer_note_holder(),
            'order_note'          => $shop->order_note_holder(true),
            'shop_sign'           => $shop->shop_signature_holder(),
            'customer_sign'       => $shop->customer_signature_holder(),
            'deliver_date'        => $shop->deliver_date_holder(),
            'deliver_time'        => $shop->deliver_time_holder(),
            'watermark'           => $shop->get_mark_holder(),
            'products_table'      => $is_post_label ? '' : self::get_products_html($order_id, $type),
            'products_list'       => $products->render_list_html(),
            'total_table'         => $is_post_label ? '' : ($total?->render_html(true) ?? ''),
            'postbarcode'         => $shop->get_post_barcode($shop->get_order_id()),
            'shop_order_id'       => $shop->get_order_id(),
            'shop_barcode_render' => $shop->barcode_holder(2, 70),
        ];

        if ($is_post_label) {
            $data = self::format_post_label_shop_fields($shop, $data);
            $data = self::strip_post_label_order_fields($data);
        }

        return $data;
    }

    /**
     * Use the same labeled line format as recipient fields on post labels.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private static function format_post_label_shop_fields(Factorchi_Shop $shop, array $data): array
    {
        $data['title'] = $shop->label_line(
            __('نام:', 'factorchi'),
            (string) factorchi_get_setting('shop_name', get_bloginfo('name')),
            'shop-name'
        );
        $data['sender'] = $shop->label_line(
            __('آدرس:', 'factorchi'),
            (string) factorchi_get_setting('shop_address', ''),
            'shop-address'
        );
        $data['postcode'] = $shop->label_line(
            __('کدپستی:', 'factorchi'),
            (string) factorchi_get_setting('shop_postcode', ''),
            'shop-postcode'
        );
        $data['phone'] = $shop->label_line(
            __('تلفن:', 'factorchi'),
            (string) factorchi_get_setting('shop_phone', ''),
            'shop-phone'
        );
        $data['economical'] = $shop->label_line(
            __('شماره اقتصادی:', 'factorchi'),
            (string) factorchi_get_setting('shop_economical', ''),
            'shop-economical'
        );
        $data['reg'] = $shop->label_line(
            __('شماره ثبت:', 'factorchi'),
            (string) factorchi_get_setting('shop_reg', ''),
            'shop-reg'
        );
        $data['url'] = '';

        return $data;
    }

    /**
     * Post labels only need sender/recipient addressing — not order contents or commerce meta.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private static function strip_post_label_order_fields(array $data): array
    {
        foreach ([
            'products_table',
            'total_table',
            'order_date',
            'transmission_date',
            'pay_method',
            'trans_id',
            'order_meta',
            'order_note',
            'delivery_date',
            'deliver_date',
            'deliver_time',
            'shop_sign',
            'customer_sign',
            'watermark',
            'barcode',
            'postbarcode',
            'shop_barcode_render',
        ] as $key) {
            $data[$key] = '';
        }

        return $data;
    }

    public static function get_products_html(int $order_id, string $type, bool $pdf = false, bool $mini = false): string
    {
        if ($type === 'pre-invoice') {
            return Factorchi_Products_Table::from_cart();
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return '';
        }

        $rows      = '';
        $show_image = self::should_show_product_image($type);
        foreach ($order->get_items() as $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }
            $product = $item->get_product();
            $sku     = $product ? $product->get_sku() : '';
            $rows   .= '<tr>';
            $rows   .= self::format_product_name_cell($item->get_name(), $sku, $product, $show_image);
            $rows   .= '<td class="fc-cell-qty">' . esc_html((string) $item->get_quantity()) . '</td>';
            $rows   .= '<td class="fc-cell-price">' . Factorchi_Helper::format_price($item->get_total()) . '</td>';
            $rows   .= '</tr>';
        }

        if ($rows === '') {
            return '';
        }

        $html  = '<table class="factorchi-products-table fci-fix-table products-table">';
        $html .= '<thead><tr>';
        $html .= '<th>' . esc_html__('محصول', 'factorchi') . '</th>';
        $html .= '<th>' . esc_html__('تعداد', 'factorchi') . '</th>';
        $html .= '<th>' . esc_html__('مبلغ', 'factorchi') . '</th>';
        $html .= '</tr></thead><tbody>' . $rows . '</tbody></table>';

        return $html;
    }

    /**
     * @return array<string, mixed>
     */
    public static function header(int $order_id, string $type, string $font = '', bool $pdf = false, bool $mini = false): array
    {
        return self::build_data($order_id, $type);
    }

    public static function product_label_view_1(int $order_id, string $type): string
    {
        $products = new Factorchi_Products_Table($order_id, $type);
        $html     = '';
        foreach ($products->get_list() as $item) {
            $html .= '<div class="product-label-item" style="border:1px dashed #999;padding:8px;margin:6px 0;">';
            $html .= '<strong>' . esc_html($item['name']) . '</strong>';
            if ($item['sku'] !== '') {
                $html .= '<br>SKU: ' . esc_html($item['sku']);
            }
            $html .= '<br>' . esc_html__('تعداد', 'factorchi') . ': ' . esc_html((string) $item['qty']);
            $html .= '</div>';
        }
        return $html;
    }

    public static function footer_js(): string
    {
        return '<script src="' . esc_url(FACTORCHI_JS_URL . 'persianumber.min.js') . '"></script>'
            . '<script>if(typeof persianNumber==="function"){persianNumber();}</script>';
    }

    public static function footer_action_btn($order_id, string $type, bool $is_email = false): string
    {
        if ($is_email) {
            return '';
        }

        $order_id = is_array($order_id) ? implode(',', array_map('intval', $order_id)) : (int) $order_id;

        return '<div class="print">'
            . '<a href="#" class="button" onclick="window.print();return false;">' . esc_html__('چاپ', 'factorchi') . '</a>'
            . '</div>';
    }
}
