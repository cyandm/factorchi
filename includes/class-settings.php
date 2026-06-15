<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Settings
{
    public const OPTION_KEY = 'factorchi_settings';

    /** @var array<string, mixed>|null */
    private static ?array $cache = null;

    public static function defaults(): array
    {
        return [
            'shop_name'              => get_bloginfo('name'),
            'shop_url'               => home_url('/'),
            'shop_email'             => get_bloginfo('admin_email'),
            'shop_phone'             => '',
            'shop_address'           => '',
            'shop_postcode'          => '',
            'shop_economical'        => '',
            'shop_reg'               => '',
            'shop_logo'              => '',
            'shop_note'              => '',
            'font_family'            => 'peyda',
            'use_persian_number'     => 'yes',
            'use_jalali_date'        => 'yes',
            'page_break'             => 'no',
            'print_page_size'        => 'a4',
            'print_per_page'         => '1',
            'bulk_use_compact'       => 'yes',
            'invoice_default_view'   => 'view-1',
            'pre_invoice_view'       => 'view-1',
            'post_label_view'        => 'view-1',
            'order_label_view'       => 'view-1',
            'orders_view'            => 'view-1',
            'invoice_margin'         => '10',
            'pre_invoice_margin'     => '10',
            'post_label_margin'      => '5',
            'font_size_invoice'      => '14',
            'font_size_pre_invoice'  => '14',
            'font_size_post_label'   => '12',
            'font_size_order_label'  => '12',
            'font_size_orders'       => '13',
            'font_size_label'        => '12',
            'show_product_image'     => 'no',
            'product_image_size'     => '70',
            'allowed_statuses'       => ['processing', 'completed'],
            'allowed_roles'          => ['customer', 'administrator', 'shop_manager'],
            'guest_access'           => 'yes',
            'channel_email'          => 'yes',
            'channel_sms'            => 'no',
            'channel_whatsapp'       => 'no',
            'channel_socials'        => 'no',
            'channel_telegram'       => 'no',
            'channel_bale'           => 'no',
            'email_subject'          => 'فاکتور سفارش {order_id}',
            'email_body'             => 'سلام {customer_name}، فاکتور سفارش شما: {invoice_url}',
            'sms_panel'              => 'smsir',
            'sms_username'           => '',
            'sms_password'           => '',
            'sms_sender'             => '',
            'sms_pattern_id'         => '',
            'sms_message'            => 'فاکتور سفارش {order_id}: {invoice_url}',
            'whatsapp_api_url'       => '',
            'whatsapp_message'       => 'فاکتور سفارش {order_id}: {invoice_url}',
            'socials_api_url'        => '',
            'socials_message'        => 'فاکتور سفارش {order_id}: {invoice_url}',
            'telegram_bot_token'     => '',
            'telegram_chat_id'       => '',
            'telegram_message'       => 'فاکتور سفارش {order_id}: {invoice_url}',
            'bale_bot_token'         => '',
            'bale_chat_id'           => '',
            'bale_message'           => 'فاکتور سفارش {order_id}: {invoice_url}',
            'auto_send_statuses'     => [],
            'auto_send_channels'     => ['email'],
            'show_on_thankyou'       => 'yes',
            'show_on_my_account'     => 'yes',
            'replace_view_order_url' => 'yes',
            'show_pre_invoice_cart'  => 'no',
            'tapin_status'           => 'no',
            'tapin_barcode_meta'     => '_tapin_barcode',
            'line_items_delete'      => '',
            'survey_enabled'         => 'no',
            'survey_status'          => 'completed',
            'survey_sms_delay_days'  => 3,
            'survey_email_delay_days'=> 3,
            'default_invoice_type'   => 'invoice',
            'sections'               => [
                'order-row',
                'order-id',
                'order-date',
                'order-full-name',
                'order-phone',
                'order-meli-code',
                'order-addr',
                'order-postcode',
                'order-shipping-method',
                'order-payment-method',
                'order-transaction-id',
                'order-customer-note',
                'order-note',
                'order-products',
                'order-price',
                'order-provider-price',
            ],
        ];
    }

    public static function activate(): void
    {
        $existing = get_option(self::OPTION_KEY);
        if (!is_array($existing)) {
            update_option(self::OPTION_KEY, self::defaults());
        }

        Factorchi_Survey::install_table();
        Factorchi_Survey::schedule_cron();
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = '')
    {
        if (self::$cache === null) {
            $stored = get_option(self::OPTION_KEY, []);
            self::$cache = is_array($stored) ? array_merge(self::defaults(), $stored) : self::defaults();
        }

        $legacy_key = str_replace('-', '_', $key);
        $dash_key   = str_replace('_', '-', $key);

        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        if (array_key_exists($legacy_key, self::$cache)) {
            return self::$cache[$legacy_key];
        }

        if (array_key_exists($dash_key, self::$cache)) {
            return self::$cache[$dash_key];
        }

        $defaults = self::defaults();
        if (array_key_exists($key, $defaults)) {
            return $defaults[$key];
        }

        return $default;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function update(array $settings): void
    {
        $merged = array_merge(self::defaults(), get_option(self::OPTION_KEY, []), $settings);
        update_option(self::OPTION_KEY, $merged);
        self::$cache = $merged;
    }

    public static function flush_cache(): void
    {
        self::$cache = null;
    }
}
