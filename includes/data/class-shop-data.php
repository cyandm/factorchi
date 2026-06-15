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
        return $html ? '<p class="shop-url">' . esc_html($url) . '</p>' : esc_html($url);
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
        return $html ? '<p class="shop-phone">' . esc_html($phone) . '</p>' : esc_html($phone);
    }

    public function logo_holder(bool $html = false): string
    {
        $logo = (string) factorchi_get_setting('shop_logo', '');
        if ($logo === '') {
            return '';
        }
        return $html
            ? '<img src="' . esc_url($logo) . '" alt="logo" class="shop-logo" style="max-height:80px;" />'
            : esc_url($logo);
    }

    public function address_holder(bool $html = false): string
    {
        $address = (string) factorchi_get_setting('shop_address', '');
        if ($address === '') {
            return '';
        }
        return $html ? '<p class="shop-address">' . esc_html($address) . '</p>' : esc_html($address);
    }

    public function postal_code_holder(bool $html = false): string
    {
        $code = (string) factorchi_get_setting('shop_postcode', '');
        if ($code === '') {
            return '';
        }
        return $html ? '<span class="shop-postcode">' . esc_html($code) . '</span>' : esc_html($code);
    }

    public function economical_num_holder(bool $html = false): string
    {
        $num = (string) factorchi_get_setting('shop_economical', '');
        if ($num === '') {
            return '';
        }
        return $html ? '<span class="shop-economical">' . esc_html($num) . '</span>' : esc_html($num);
    }

    public function reg_num_holder(bool $html = false): string
    {
        $num = (string) factorchi_get_setting('shop_reg', '');
        if ($num === '') {
            return '';
        }
        return $html ? '<span class="shop-reg">' . esc_html($num) . '</span>' : esc_html($num);
    }

    public function print_date_holder(): string
    {
        $date = Factorchi_Helper::date_format(time());
        return '<span class="print-date">' . esc_html__('تاریخ چاپ: ', 'factorchi') . esc_html($date) . '</span>';
    }

    public function transmission_date_holder(): string
    {
        if (!$this->order) {
            return '';
        }
        $date = $this->order->get_date_created();
        if (!$date) {
            return '';
        }
        return '<span class="transmission-date">' . esc_html__('تاریخ سفارش: ', 'factorchi') . esc_html(Factorchi_Helper::date_format($date->getTimestamp())) . '</span>';
    }

    public function order_id_holder(): string
    {
        return '<span class="order-id">' . esc_html__('شناسه سفارش: ', 'factorchi') . esc_html((string) $this->order_id) . '</span>';
    }

    public function order_status_holder(): string
    {
        return '';
    }

    public function barcode_holder(int $type = 1, int $height = 50): string
    {
        $code = (string) $this->order_id;
        $url  = 'https://barcode.tec-it.com/barcode.ashx?data=' . rawurlencode($code) . '&code=Code128&translate-esc=on&dpi=96';
        return '<div class="barcode"><img src="' . esc_url($url) . '" height="' . (int) $height . '" alt="" /></div>';
    }

    public function customer_note_holder(): string
    {
        if (!$this->order) {
            return '';
        }
        $note = $this->order->get_customer_note();
        return $note !== '' ? '<p class="customer-note">' . esc_html($note) . '</p>' : '';
    }

    public function order_note_holder(bool $html = false): string
    {
        $note = (string) factorchi_get_setting('shop_note', '');
        if ($note === '') {
            return '';
        }
        return $html ? '<p class="order-note">' . esc_html($note) . '</p>' : esc_html($note);
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
