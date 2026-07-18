<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Shop
{
    private $order_id;
    private string $type;
    /** @var WC_Order|null */
    private $order;

    public function __construct($order_id, string $type = 'invoice')
    {
        $this->order_id = is_array($order_id) ? (int) reset($order_id) : (int) $order_id;
        $this->type     = $type;
        $this->order    = $this->order_id > 0 ? wc_get_order($this->order_id) : null;
    }

    public function get_order_id(): int
    {
        return $this->order_id;
    }

    public function label_line(string $label, string $value, string $class = ''): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $class_attr = $class !== '' ? ' class="' . esc_attr($class) . '"' : '';

        if ($label === '') {
            return '<p' . $class_attr . '>' . esc_html($value) . '</p>';
        }

        return '<p' . $class_attr . '><strong>' . esc_html($label) . '</strong> ' . esc_html($value) . '</p>';
    }

    public function title_holder(bool $html = false): string
    {
        $title = (string) factorchi_get_setting('shop_name', get_bloginfo('name'));
        return $html ? '<p class="shop-title">' . esc_html($title) . '</p>' : esc_html($title);
    }

    public function url_holder(bool $html = false): string
    {
        $url = (string) factorchi_get_setting('shop_url', home_url('/'));
        if ($url === '') {
            return '';
        }

        return $html
            ? $this->label_line(__('سایت:', 'factorchi'), $url, 'shop-url')
            : esc_html($url);
    }

    public function email_holder(bool $html = false): string
    {
        return '';
    }

    public function phone_holder(bool $html = false): string
    {
        $phone = (string) factorchi_get_setting('shop_phone', '');
        if ($phone === '') {
            return '';
        }

        return $html
            ? $this->label_line(__('تلفن:', 'factorchi'), $phone, 'shop-phone')
            : esc_html($phone);
    }

    public function logo_holder(bool $html = false): string
    {
        $logo = (string) factorchi_get_setting('shop_logo', '');
        if ($logo === '') {
            return '';
        }
        return $html
            ? '<img src="' . esc_url($logo) . '" alt="logo" class="shop-logo" />'
            : esc_url($logo);
    }

    public function address_holder(bool $html = false): string
    {
        $address = (string) factorchi_get_setting('shop_address', '');
        if ($address === '') {
            return '';
        }
        return $html
            ? $this->label_line(__('آدرس:', 'factorchi'), $address, 'shop-address')
            : esc_html($address);
    }

    public function postal_code_holder(bool $html = false): string
    {
        $code = (string) factorchi_get_setting('shop_postcode', '');
        if ($code === '') {
            return '';
        }
        return $html
            ? $this->label_line(__('کدپستی:', 'factorchi'), $code, 'shop-postcode')
            : esc_html($code);
    }

    public function economical_num_holder(bool $html = false): string
    {
        $num = (string) factorchi_get_setting('shop_economical', '');
        if ($num === '') {
            return '';
        }
        return $html
            ? $this->label_line(__('شماره اقتصادی:', 'factorchi'), $num, 'shop-economical')
            : esc_html($num);
    }

    public function reg_num_holder(bool $html = false): string
    {
        $num = (string) factorchi_get_setting('shop_reg', '');
        if ($num === '') {
            return '';
        }
        return $html
            ? $this->label_line(__('شماره ثبت:', 'factorchi'), $num, 'shop-reg')
            : esc_html($num);
    }

    public function print_date_holder(): string
    {
        if (factorchi_get_setting('show_print_date', 'yes') !== 'yes') {
            return '';
        }

        $date = Factorchi_Helper::date_format(time());
        return '<span class="print-date section print-date"><span class="title">' . esc_html__('تاریخ چاپ:', 'factorchi') . '</span> ' . esc_html($date) . '</span>';
    }

    public function transmission_date_holder(): string
    {
        if (factorchi_get_setting('show_order_date', 'yes') !== 'yes') {
            return '';
        }

        if (!$this->order) {
            return '';
        }
        $date = $this->order->get_date_created();
        if (!$date) {
            return '';
        }
        return '<span class="transmission-date section transmission-date"><span class="title">' . esc_html__('تاریخ سفارش:', 'factorchi') . '</span> ' . esc_html(Factorchi_Helper::date_format($date->getTimestamp())) . '</span>';
    }

    public function order_id_holder(): string
    {
        return '<span class="order-id section order-id"><span class="title">' . esc_html__('شناسه سفارش:', 'factorchi') . '</span> ' . esc_html((string) $this->order_id) . '</span>';
    }

    public function order_status_holder(): string
    {
        return '';
    }

    public function barcode_holder(int $type = 1, int $height = 80, bool $show_text = true): string
    {
        return Factorchi_Barcode::html((string) $this->order_id, $height, $show_text);
    }

    public function customer_note_holder(): string
    {
        if (
            in_array($this->type, ['invoice', 'pre-invoice'], true)
            && factorchi_get_setting('show_customer_note_footer', 'yes') !== 'yes'
        ) {
            return '';
        }

        if (!$this->order) {
            return '';
        }
        $note = trim((string) $this->order->get_customer_note());
        if ($note === '') {
            return '';
        }

        return $this->note_table(
            __('یادداشت', 'factorchi'),
            $note,
            'customer-note'
        );
    }

    public function order_note_holder(bool $html = false): string
    {
        $note = trim((string) factorchi_get_setting('shop_note', ''));
        if ($note === '') {
            return '';
        }
        if (!$html) {
            return esc_html($note);
        }

        return $this->note_table(
            __('یادداشت فروشگاه', 'factorchi'),
            $note,
            'order-note'
        );
    }

    /**
     * Note block styled like the products table (brand header + bordered body).
     */
    private function note_table(string $title, string $body, string $class): string
    {
        $classes = trim('factorchi-note-table fci-form-table ' . $class);

        return '<table class="' . esc_attr($classes) . '">'
            . '<thead><tr><th>' . esc_html($title) . '</th></tr></thead>'
            . '<tbody><tr><td>' . nl2br(esc_html($body)) . '</td></tr></tbody>'
            . '</table>';
    }

    public function shop_signature_holder(): string
    {
        return '';
    }

    public function customer_signature_holder(): string
    {
        return '';
    }

    public function deliver_date_holder(): string
    {
        return '';
    }

    public function deliver_time_holder(): string
    {
        return '';
    }

    public function get_mark_holder(): string
    {
        return '';
    }

    public function get_post_barcode(int $order_id): string
    {
        if (factorchi_get_setting('tapin_status', 'no') !== 'yes') {
            return '';
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return '';
        }

        $meta_key = (string) factorchi_get_setting('tapin_barcode_meta', '_tapin_barcode');
        $barcode  = (string) $order->get_meta($meta_key);

        return $barcode !== '' ? '<div class="post-barcode">' . esc_html($barcode) . '</div>' : '';
    }
}
