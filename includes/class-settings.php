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
            'show_print_date'        => 'yes',
            'show_order_date'        => 'yes',
            'show_date_time'         => 'yes',
            'print_page_size'        => 'a4',
            'invoice_default_view'   => 'modern',
            'pre_invoice_view'       => 'modern',
            'post_label_view'        => 'modern-a4',
            'invoice_margin'         => '10',
            'pre_invoice_margin'     => '10',
            'post_label_margin'      => '5',
            'section_gap_a4'         => '12',
            'section_gap_a5'         => '12',
            'font_size_invoice'      => '14',
            'font_size_pre_invoice'  => '14',
            'font_size_post_label'   => '12',
            'font_size_label'        => '12',
            'font_size_shop'         => '12',
            'font_size_buyer'        => '14',
            'show_product_image'     => 'no',
            'shop_logo_size'         => '80',
            'show_barcode_top'       => 'no',
            'show_barcode_top_text'  => 'yes',
            'show_barcode_under_title' => 'no',
            'show_barcode_under_title_text' => 'no',
            'show_barcode_bottom'    => 'yes',
            'show_barcode_bottom_text' => 'yes',
            'show_payment_method'    => 'yes',
            'show_shipping_method'   => 'yes',
            'show_transaction_id'    => 'yes',
            'show_customer_note_buyer'  => 'yes',
            'show_customer_note_footer' => 'yes',
            'filter_product_name_codes' => 'no',
            'product_attrs_mode'        => 'all',
            'product_attrs_show_label'  => 'yes',
            'product_attrs_selected'    => [],
            'compact_party_texts'       => 'no',
            'show_tearoff'              => 'yes',
            'show_tearoff_recipient'    => 'no',
            'show_tearoff_payment'      => 'yes',
            'show_tearoff_tracking'     => 'yes',
            'use_payzito_gateway_tracking' => 'no',
            'show_tearoff_order_date'   => 'yes',
            'show_tearoff_order_id'     => 'yes',
            'product_image_size'     => '70',
            'enable_border_radius'   => 'yes',
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
            'survey_email_delay_days' => 3,
            'default_invoice_type'   => 'invoice',
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
            self::$cache = self::normalize_cached_settings(self::$cache);
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
     * Map legacy invoice/pre-invoice template slugs to modern|classic.
     */
    public static function normalize_invoice_view(string $view): string
    {
        $view = sanitize_file_name($view);

        if ($view === 'view-2' || $view === 'classic') {
            return 'classic';
        }

        if ($view === 'modern' || $view === 'view-1') {
            return 'modern';
        }

        // view-3+, view-mini, view-pdf, empty, unknown → modern
        return 'modern';
    }

    /**
     * Post-label templates combine style + page size.
     */
    public static function normalize_post_label_view(string $view): string
    {
        $view = sanitize_file_name($view);

        $allowed = ['modern-a4', 'modern-a5', 'classic-a4', 'classic-a5'];
        if (in_array($view, $allowed, true)) {
            return $view;
        }

        $style = (strpos($view, 'classic') !== false || $view === 'view-2') ? 'classic' : 'modern';
        $size  = (strpos($view, 'a5') !== false) ? 'a5' : 'a4';

        return $style . '-' . $size;
    }

    public static function post_label_style_from_view(string $view): string
    {
        $view = self::normalize_post_label_view($view);

        return strpos($view, 'classic') === 0 ? 'classic' : 'modern';
    }

    public static function post_label_size_from_view(string $view): string
    {
        $view = self::normalize_post_label_view($view);

        return substr($view, -2) === 'a5' ? 'a5' : 'a4';
    }

    /**
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    private static function normalize_cached_settings(array $settings): array
    {
        foreach (['invoice_default_view', 'pre_invoice_view'] as $key) {
            if (isset($settings[$key]) && is_string($settings[$key])) {
                $settings[$key] = self::normalize_invoice_view($settings[$key]);
            }
        }

        if (isset($settings['post_label_view']) && is_string($settings['post_label_view'])) {
            $settings['post_label_view'] = self::normalize_post_label_view($settings['post_label_view']);
        }

        return $settings;
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function update(array $settings): void
    {
        foreach (['invoice_default_view', 'pre_invoice_view'] as $key) {
            if (isset($settings[$key]) && is_string($settings[$key])) {
                $settings[$key] = self::normalize_invoice_view($settings[$key]);
            }
        }

        if (isset($settings['post_label_view']) && is_string($settings['post_label_view'])) {
            $settings['post_label_view'] = self::normalize_post_label_view($settings['post_label_view']);
        }

        $merged = array_merge(self::defaults(), get_option(self::OPTION_KEY, []), $settings);
        $merged = self::normalize_cached_settings($merged);
        update_option(self::OPTION_KEY, $merged);
        self::$cache = $merged;
    }

    public static function flush_cache(): void
    {
        self::$cache = null;
    }
}
