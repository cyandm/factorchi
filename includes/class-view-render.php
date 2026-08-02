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

        $image_id = self::resolve_product_image_id($product);
        if ($image_id <= 0) {
            return '';
        }

        $size = self::get_product_image_size();
        $url  = wp_get_attachment_image_url($image_id, 'woocommerce_thumbnail');
        if (!$url) {
            $url = wp_get_attachment_image_url($image_id, 'thumbnail');
        }
        if (!$url) {
            $url = wp_get_attachment_image_url($image_id, 'medium');
        }
        if (!$url) {
            $url = wp_get_attachment_image_url($image_id, 'full');
        }
        if (!$url) {
            return '';
        }

        return '<img src="' . esc_url($url) . '" alt="" class="fc-product-thumb" width="'
            . (int) $size . '" height="' . (int) $size . '" loading="lazy" decoding="async" />';
    }

    /**
     * Resolve featured image (variation → parent → gallery).
     */
    private static function resolve_product_image_id(WC_Product $product): int
    {
        $image_id = (int) $product->get_image_id();
        if ($image_id > 0) {
            return $image_id;
        }

        $parent_id = (int) $product->get_parent_id();
        if ($parent_id > 0) {
            $parent = wc_get_product($parent_id);
            if ($parent) {
                $image_id = (int) $parent->get_image_id();
                if ($image_id > 0) {
                    return $image_id;
                }
                $gallery = $parent->get_gallery_image_ids();
                if (!empty($gallery[0])) {
                    return (int) $gallery[0];
                }
            }
        }

        $gallery = $product->get_gallery_image_ids();
        if (!empty($gallery[0])) {
            return (int) $gallery[0];
        }

        return 0;
    }

    /**
     * Strip trailing product codes like G00927 from a product name.
     */
    public static function filter_product_name_codes(string $name, string $sku = ''): string
    {
        if (factorchi_get_setting('filter_product_name_codes', 'no') !== 'yes') {
            return $name;
        }

        $name = trim($name);
        if ($name === '') {
            return $name;
        }

        // Remove embedded SKU as a whole token (if provided).
        if ($sku !== '') {
            $quoted = preg_quote($sku, '/');
            $name   = preg_replace('/(?:^|[\s\-–—|\/])' . $quoted . '(?=[\s\-–—|\/,]|$)/u', ' ', $name) ?? $name;
            $name   = trim(preg_replace('/\s{2,}/u', ' ', $name) ?? $name);
        }

        // Remove trailing codes: letter(s) + digits (+ optional alnum), e.g. G00927, AB12, XYZ001.
        do {
            $prev = $name;
            $name = preg_replace('/\s+[A-Za-z][A-Za-z0-9_-]*\d[A-Za-z0-9_-]*$/u', '', $name) ?? $name;
            $name = trim($name);
        } while ($name !== $prev && $name !== '');

        return $name;
    }

    /**
     * Normalize attribute/meta keys so stored settings and runtime keys match
     * (Persian attribute slugs are percent-encoded by WooCommerce).
     */
    private static function normalize_attr_key(string $key): string
    {
        return sanitize_title(urldecode(strtolower(trim($key))));
    }

    /**
     * Collect attribute label/value pairs from an order item or a variation product.
     *
     * @return list<array{key:string, label:string, value:string}>
     */
    private static function collect_attribute_pairs(?WC_Product $product, ?WC_Order_Item_Product $item = null): array
    {
        $pairs = [];

        if ($item instanceof WC_Order_Item_Product) {
            foreach ($item->get_formatted_meta_data('_', true) as $meta) {
                $value = trim(wp_strip_all_tags((string) $meta->display_value));
                if ($value === '') {
                    continue;
                }
                $pairs[] = [
                    'key'   => self::normalize_attr_key((string) $meta->key),
                    'label' => trim(wp_strip_all_tags((string) $meta->display_key)),
                    'value' => $value,
                ];
            }
            if ($pairs !== []) {
                return $pairs;
            }
        }

        if ($product && $product->is_type('variation')) {
            foreach ($product->get_attributes() as $taxonomy => $value) {
                if (!is_string($value) || $value === '') {
                    continue;
                }
                $display = $value;
                if (taxonomy_exists($taxonomy)) {
                    $term = get_term_by('slug', $value, $taxonomy);
                    if ($term && !is_wp_error($term)) {
                        $display = $term->name;
                    }
                }
                $pairs[] = [
                    'key'   => self::normalize_attr_key($taxonomy),
                    'label' => wc_attribute_label($taxonomy, $product),
                    'value' => trim($display),
                ];
            }
        }

        return $pairs;
    }

    /**
     * Apply the "product_attrs_mode" setting: all / selected (in admin list order) / none.
     *
     * @param list<array{key:string, label:string, value:string}> $pairs
     * @return list<array{key:string, label:string, value:string}>
     */
    private static function filter_attribute_pairs(array $pairs): array
    {
        $mode = (string) factorchi_get_setting('product_attrs_mode', 'all');

        if ($mode === 'none') {
            return [];
        }

        if ($mode !== 'selected') {
            return $pairs;
        }

        $selected = factorchi_get_setting('product_attrs_selected', []);
        if (!is_array($selected) || $selected === []) {
            return [];
        }

        $ordered = [];
        foreach ($selected as $selected_key) {
            $selected_key = self::normalize_attr_key((string) $selected_key);
            foreach ($pairs as $pair) {
                if ($pair['key'] === $selected_key) {
                    $ordered[] = $pair;
                    break;
                }
            }
        }

        return $ordered;
    }

    /**
     * Build the label segments: product name, attribute strings, SKU.
     *
     * @return array{name:string, attrs:list<string>, sku:string}
     */
    public static function build_product_label_parts(string $item_name, ?WC_Product $product, ?WC_Order_Item_Product $item = null): array
    {
        $base = trim($item_name);
        $sku  = $product ? trim((string) $product->get_sku()) : '';

        if ($product && $product->is_type('variation')) {
            $parent = wc_get_product($product->get_parent_id());
            if ($parent) {
                $base = trim($parent->get_name());
            }
        }

        $pairs      = self::filter_attribute_pairs(self::collect_attribute_pairs($product, $item));
        $show_label = factorchi_get_setting('product_attrs_show_label', 'yes') === 'yes';

        $attrs = [];
        foreach ($pairs as $pair) {
            $attrs[] = ($show_label && $pair['label'] !== '')
                ? $pair['label'] . ': ' . $pair['value']
                : $pair['value'];
        }

        return [
            'name'  => self::filter_product_name_codes($base, $sku),
            'attrs' => $attrs,
            'sku'   => $sku,
        ];
    }

    /**
     * Plain-text label: "Product name، attr: value، attr: value - SKU".
     * RLM marks keep mixed LTR/RTL segments in visual order.
     */
    public static function build_product_label(string $item_name, ?WC_Product $product, ?WC_Order_Item_Product $item = null): string
    {
        $parts = self::build_product_label_parts($item_name, $product, $item);
        $rlm   = "\u{200F}";

        $label = implode($rlm . '، ', array_merge([$parts['name']], $parts['attrs']));
        if ($parts['sku'] !== '') {
            $label .= $rlm . ' - ' . $parts['sku'];
        }

        return $label;
    }

    public static function format_product_name_cell(string $label, ?WC_Product $product, bool $with_image): string
    {
        $thumb = $with_image ? self::get_product_thumbnail_html($product) : '';

        $html  = '<td class="fc-product-cell"><div class="fc-product-row">';
        if ($thumb !== '') {
            $html .= $thumb;
        }
        $html .= '<span class="fc-product-text">' . esc_html($label) . '</span>';
        $html .= '</div></td>';

        return $html;
    }

    /**
     * Product cell rendered from label parts. Each segment is bidi-isolated
     * with <bdi> so Latin names/codes never reorder in the RTL layout.
     *
     * @param array{name:string, attrs:list<string>, sku:string} $parts
     */
    public static function format_product_name_cell_parts(array $parts, ?WC_Product $product, bool $with_image): string
    {
        $thumb = $with_image ? self::get_product_thumbnail_html($product) : '';

        $text = '<bdi>' . esc_html($parts['name']) . '</bdi>';
        foreach ($parts['attrs'] as $attr) {
            $text .= '، <bdi>' . esc_html($attr) . '</bdi>';
        }
        if ($parts['sku'] !== '') {
            $text .= ' - <bdi>' . esc_html($parts['sku']) . '</bdi>';
        }

        $html  = '<td class="fc-product-cell"><div class="fc-product-row">';
        if ($thumb !== '') {
            $html .= $thumb;
        }
        $html .= '<span class="fc-product-text" dir="rtl">' . $text . '</span>';
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
            'customer_note_line'  => $customer->customer_note_line_holder(true),
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
            'shop_barcode_render' => $shop->barcode_holder(1, 60),
            'tearoff'             => $is_post_label ? '' : self::build_tearoff_html($customer, $shop),
        ];

        if ($is_post_label) {
            $data = self::format_post_label_shop_fields($shop, $data);
            $data = self::strip_post_label_order_fields($data);
        }

        return $data;
    }

    /**
     * Tear-off strip under the invoice footer for cutting and keeping.
     */
    public static function build_tearoff_html(Factorchi_Customer_Data $customer, Factorchi_Shop $shop): string
    {
        if (factorchi_get_setting('show_tearoff', 'yes') !== 'yes') {
            return '';
        }

        $fields = [];

        if (factorchi_get_setting('show_tearoff_recipient', 'no') === 'yes') {
            $name = $customer->get_full_name();
            if ($name !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-name"><strong>' . esc_html__('نام:', 'factorchi') . '</strong> ' . esc_html($name) . '</p>';
            }

            $phone = $customer->get_phone();
            if ($phone !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-phone"><strong>' . esc_html__('تلفن:', 'factorchi') . '</strong> ' . esc_html($phone) . '</p>';
            }

            $address = $customer->get_address();
            if ($address !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-address"><strong>' . esc_html__('آدرس:', 'factorchi') . '</strong> ' . esc_html($address) . '</p>';
            }

            $postcode = $customer->get_postal_code();
            if ($postcode !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-postcode"><strong>' . esc_html__('کدپستی:', 'factorchi') . '</strong> ' . esc_html($postcode) . '</p>';
            }
        }

        if (factorchi_get_setting('show_tearoff_payment', 'yes') === 'yes') {
            $value = $customer->get_payment_method();
            if ($value !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-payment"><strong>' . esc_html__('روش پرداخت:', 'factorchi') . '</strong> ' . esc_html($value) . '</p>';
            }
        }

        if (factorchi_get_setting('show_tearoff_tracking', 'yes') === 'yes') {
            $value = $customer->get_gateway_tracking_code();
            if ($value !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-tracking"><strong>' . esc_html__('کدپیگیری درگاه:', 'factorchi') . '</strong> ' . esc_html($value) . '</p>';
            }
        }

        if (factorchi_get_setting('show_tearoff_order_date', 'yes') === 'yes') {
            $value = $customer->get_order_date();
            if ($value !== '') {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-order-date"><strong>' . esc_html__('تاریخ سفارش:', 'factorchi') . '</strong> ' . esc_html($value) . '</p>';
            }
        }

        if (factorchi_get_setting('show_tearoff_order_id', 'yes') === 'yes') {
            $order_id = $shop->get_order_id();
            if ($order_id > 0) {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-order-id"><strong>' . esc_html__('شناسه سفارش:', 'factorchi') . '</strong> ' . esc_html((string) $order_id) . '</p>';
            }
        }

        if ($fields === []) {
            return '';
        }

        return '<div class="fc-invoice-tearoff">'
            . '<div class="fc-invoice-tearoff-cut" aria-hidden="true"></div>'
            . '<div class="fc-invoice-tearoff-fields">' . implode('', $fields) . '</div>'
            . '</div>';
    }

    /**
     * Preview helper with sample tear-off values.
     *
     * @param array{
     *     payment?:string,
     *     tracking?:string,
     *     order_date?:string,
     *     order_id?:string,
     *     recipient_name?:string,
     *     recipient_phone?:string,
     *     recipient_address?:string,
     *     recipient_postcode?:string
     * } $sample
     */
    public static function build_tearoff_html_from_values(array $sample): string
    {
        if (factorchi_get_setting('show_tearoff', 'yes') !== 'yes') {
            return '';
        }

        $fields = [];

        if (factorchi_get_setting('show_tearoff_recipient', 'no') === 'yes') {
            if (!empty($sample['recipient_name'])) {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-name"><strong>' . esc_html__('نام:', 'factorchi') . '</strong> ' . esc_html((string) $sample['recipient_name']) . '</p>';
            }
            if (!empty($sample['recipient_phone'])) {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-phone"><strong>' . esc_html__('تلفن:', 'factorchi') . '</strong> ' . esc_html((string) $sample['recipient_phone']) . '</p>';
            }
            if (!empty($sample['recipient_address'])) {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-address"><strong>' . esc_html__('آدرس:', 'factorchi') . '</strong> ' . esc_html((string) $sample['recipient_address']) . '</p>';
            }
            if (!empty($sample['recipient_postcode'])) {
                $fields[] = '<p class="fc-tearoff-item fc-tearoff-recipient-postcode"><strong>' . esc_html__('کدپستی:', 'factorchi') . '</strong> ' . esc_html((string) $sample['recipient_postcode']) . '</p>';
            }
        }

        if (factorchi_get_setting('show_tearoff_payment', 'yes') === 'yes' && !empty($sample['payment'])) {
            $fields[] = '<p class="fc-tearoff-item fc-tearoff-payment"><strong>' . esc_html__('روش پرداخت:', 'factorchi') . '</strong> ' . esc_html((string) $sample['payment']) . '</p>';
        }

        if (factorchi_get_setting('show_tearoff_tracking', 'yes') === 'yes' && !empty($sample['tracking'])) {
            $fields[] = '<p class="fc-tearoff-item fc-tearoff-tracking"><strong>' . esc_html__('کدپیگیری درگاه:', 'factorchi') . '</strong> ' . esc_html((string) $sample['tracking']) . '</p>';
        }

        if (factorchi_get_setting('show_tearoff_order_date', 'yes') === 'yes' && !empty($sample['order_date'])) {
            $fields[] = '<p class="fc-tearoff-item fc-tearoff-order-date"><strong>' . esc_html__('تاریخ سفارش:', 'factorchi') . '</strong> ' . esc_html((string) $sample['order_date']) . '</p>';
        }

        if (factorchi_get_setting('show_tearoff_order_id', 'yes') === 'yes' && !empty($sample['order_id'])) {
            $fields[] = '<p class="fc-tearoff-item fc-tearoff-order-id"><strong>' . esc_html__('شناسه سفارش:', 'factorchi') . '</strong> ' . esc_html((string) $sample['order_id']) . '</p>';
        }

        if ($fields === []) {
            return '';
        }

        return '<div class="fc-invoice-tearoff">'
            . '<div class="fc-invoice-tearoff-cut" aria-hidden="true"></div>'
            . '<div class="fc-invoice-tearoff-fields">' . implode('', $fields) . '</div>'
            . '</div>';
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
        foreach (
            [
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
                'tearoff',
            ] as $key
        ) {
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
            $parts   = self::build_product_label_parts($item->get_name(), $product instanceof WC_Product ? $product : null, $item);
            $rows   .= '<tr>';
            $rows   .= self::format_product_name_cell_parts($parts, $product instanceof WC_Product ? $product : null, $show_image);
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
