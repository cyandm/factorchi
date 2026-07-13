<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Customer_Data
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

    private function format_plain_address(string $formatted_html): string
    {
        $plain = wp_strip_all_tags(str_replace(['<br/>', '<br />', '<br>'], ' - ', $formatted_html));
        $plain = preg_replace('/\s*-\s*-\s*/', ' - ', $plain) ?? $plain;
        $plain = trim(preg_replace('/\s+/', ' ', $plain) ?? $plain);

        return $plain;
    }

    private function append_plaque_unit_if_missing(string $address, string $prefix): string
    {
        if (!$this->order || $address === '') {
            return $address;
        }

        $plaque = trim((string) $this->order->get_meta($prefix . 'plaque'));
        $unit   = trim((string) $this->order->get_meta($prefix . 'unit'));

        if ($plaque !== '' && !preg_match('/پلاک\s*[:：]?\s*' . preg_quote($plaque, '/') . '/u', $address)) {
            $address .= ($address !== '' ? ' - ' : '') . __('پلاک', 'factorchi') . ': ' . $plaque;
        }

        if ($unit !== '' && !preg_match('/واحد\s*[:：]?\s*' . preg_quote($unit, '/') . '/u', $address)) {
            $address .= ($address !== '' ? ' - ' : '') . __('واحد', 'factorchi') . ': ' . $unit;
        }

        return $address;
    }

    private function field_line(string $label, string $value, bool $html, string $class = ''): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (!$html) {
            return $label !== '' ? $label . ' ' . $value : $value;
        }

        $class_attr = $class !== '' ? ' class="' . esc_attr($class) . '"' : '';

        if ($label === '') {
            return '<p' . $class_attr . '>' . esc_html($value) . '</p>';
        }

        return '<p' . $class_attr . '><strong>' . esc_html($label) . '</strong> ' . esc_html($value) . '</p>';
    }

    public function address_holder(bool $html = false): string
    {
        return $this->field_line(__('آدرس:', 'factorchi'), $this->get_address(), $html, 'customer-address');
    }

    public function get_address(): string
    {
        if (!$this->order) {
            return '';
        }

        $formatted = $this->order->get_formatted_shipping_address();
        $prefix    = '_shipping_';

        if ($formatted === '') {
            $formatted = $this->order->get_formatted_billing_address();
            $prefix    = '_billing_';
        }

        $plain = $this->format_plain_address($formatted);

        return $this->append_plaque_unit_if_missing($plain, $prefix);
    }

    public function full_name_holder(bool $html = false): string
    {
        return $this->field_line(__('نام:', 'factorchi'), $this->get_full_name(), $html, 'customer-name');
    }

    public function get_full_name(): string
    {
        if (!$this->order) {
            return '';
        }

        $name = trim($this->order->get_formatted_shipping_full_name());
        if ($name === '') {
            $name = trim($this->order->get_formatted_billing_full_name());
        }

        return $name;
    }

    public function postal_code_holder(bool $html = false): string
    {
        return $this->field_line(__('کدپستی:', 'factorchi'), $this->get_postal_code(), $html, 'customer-postcode');
    }

    public function get_postal_code(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) ($this->order->get_shipping_postcode() ?: $this->order->get_billing_postcode());
    }

    public function phone_holder(bool $html = false): string
    {
        return $this->field_line(__('تلفن:', 'factorchi'), $this->get_phone(), $html, 'customer-phone');
    }

    public function get_phone(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_billing_phone();
    }

    public function email_holder(bool $html = false): string
    {
        return '';
    }

    public function get_email(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_billing_email();
    }

    public function order_date_holder(bool $html = false): string
    {
        if (factorchi_get_setting('show_order_date', 'yes') !== 'yes') {
            return '';
        }

        return $this->field_line(__('تاریخ سفارش:', 'factorchi'), $this->get_order_date(), $html, 'order-date');
    }

    public function get_order_date(): string
    {
        if (!$this->order) {
            return '';
        }

        $date = $this->order->get_date_created();
        if (!$date) {
            return '';
        }

        return Factorchi_Helper::date_format($date->getTimestamp());
    }

    public function payment_method_holder(bool $html = false): string
    {
        return $this->field_line(__('روش پرداخت:', 'factorchi'), $this->get_payment_method(), $html, 'pay-method');
    }

    public function get_payment_method(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_payment_method_title();
    }

    public function transaction_id_holder(bool $html = false): string
    {
        return $this->field_line(__('شماره تراکنش:', 'factorchi'), $this->get_transaction_id(), $html, 'trans-id');
    }

    public function get_transaction_id(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_transaction_id();
    }

    public function national_id_holder(bool $html = false): string
    {
        return $this->field_line(__('کد ملی:', 'factorchi'), $this->get_national_id(), $html, 'national-id');
    }

    public function get_national_id(): string
    {
        if (!$this->order) {
            return '';
        }

        $id = (string) $this->order->get_meta('_billing_national_id');
        if ($id === '') {
            $id = (string) $this->order->get_meta('billing_national_id');
        }

        return $id;
    }

    public function shipping_method_holder(bool $html = false): string
    {
        return $this->field_line(__('روش ارسال:', 'factorchi'), $this->get_shipping_method(), $html, 'shipping-method');
    }

    public function get_shipping_method(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_shipping_method();
    }

    public function user_meta_holder(): string
    {
        return '';
    }

    public function order_meta_holder(): string
    {
        return '';
    }

    public function delivery_date_holder(): string
    {
        return '';
    }
}
