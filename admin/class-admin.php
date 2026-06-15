<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_post_factorchi_save_settings', [$this, 'save_settings']);
        add_action('admin_enqueue_scripts', [$this, 'settings_assets']);
    }

    public function register_menu(): void
    {
        add_menu_page(
            __('Factorchi', 'factorchi'),
            __('فاکتورچی', 'factorchi'),
            'manage_woocommerce',
            'factorchi',
            [$this, 'render_settings'],
            'dashicons-media-text',
            57
        );

        add_submenu_page(
            'factorchi',
            __('تنظیمات', 'factorchi'),
            __('تنظیمات', 'factorchi'),
            'manage_woocommerce',
            'factorchi',
            [$this, 'render_settings']
        );

        add_submenu_page(
            'factorchi',
            __('ابزارها', 'factorchi'),
            __('ابزارها', 'factorchi'),
            'manage_woocommerce',
            'factorchi-tools',
            [$this, 'render_tools']
        );

        add_submenu_page(
            'factorchi',
            __('وضعیت', 'factorchi'),
            __('وضعیت', 'factorchi'),
            'manage_woocommerce',
            'factorchi-status',
            [$this, 'render_status']
        );
    }

    public function settings_assets(string $hook): void
    {
        if (!str_contains($hook, 'factorchi')) {
            return;
        }

        wp_enqueue_style(
            'factorchi-admin-settings',
            FACTORCHI_CSS_URL . 'admin-settings.css',
            [],
            FACTORCHI_VERSION
        );

        if ($hook === 'toplevel_page_factorchi') {
            wp_enqueue_script(
                'factorchi-admin-settings',
                FACTORCHI_JS_URL . 'admin-settings.js',
                ['jquery'],
                FACTORCHI_VERSION,
                true
            );
            wp_enqueue_media();
        }
    }

    public function render_settings(): void
    {
        $settings = array_merge(Factorchi_Settings::defaults(), get_option(Factorchi_Settings::OPTION_KEY, []));
        $tab      = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'general';
        include FACTORCHI_DIR . 'admin/views/settings.php';
    }

    public function render_tools(): void
    {
        include FACTORCHI_DIR . 'admin/views/tools.php';
    }

    public function render_status(): void
    {
        include FACTORCHI_DIR . 'admin/views/status.php';
    }

    public function save_settings(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('Unauthorized', 'factorchi'));
        }

        check_admin_referer('factorchi_save_settings');

        $checkboxes = [
            'use_persian_number', 'use_jalali_date', 'page_break', 'bulk_use_compact', 'show_product_image', 'guest_access',
            'channel_email', 'channel_sms', 'channel_whatsapp', 'channel_socials', 'channel_telegram', 'channel_bale',
            'show_on_thankyou', 'show_on_my_account', 'replace_view_order_url', 'show_pre_invoice_cart', 'tapin_status', 'survey_enabled',
        ];

        $data = [];
        foreach ($checkboxes as $key) {
            $data[$key] = !empty($_POST[$key]) ? 'yes' : 'no';
        }

        $text_fields = [
            'shop_name', 'shop_url', 'shop_email', 'shop_phone', 'shop_address', 'shop_postcode',
            'shop_economical', 'shop_reg', 'shop_logo', 'shop_note', 'font_family',
            'invoice_default_view', 'pre_invoice_view', 'post_label_view', 'order_label_view', 'orders_view',
            'invoice_margin', 'pre_invoice_margin', 'post_label_margin',
            'font_size_invoice', 'font_size_pre_invoice', 'font_size_post_label',
            'font_size_order_label', 'font_size_orders', 'font_size_label',
            'product_image_size',
            'print_page_size', 'print_per_page',
            'email_subject', 'email_body', 'sms_panel', 'sms_username', 'sms_password', 'sms_sender',
            'sms_pattern_id', 'sms_message', 'whatsapp_api_url', 'whatsapp_message',
            'socials_api_url', 'socials_message', 'telegram_bot_token', 'telegram_chat_id', 'telegram_message',
            'bale_bot_token', 'bale_chat_id', 'bale_message', 'tapin_barcode_meta', 'line_items_delete',
            'survey_status', 'default_invoice_type',
        ];

        foreach ($text_fields as $key) {
            if (isset($_POST[$key])) {
                $data[$key] = sanitize_text_field(wp_unslash($_POST[$key]));
            }
        }

        if (isset($data['font_family'])) {
            $data['font_family'] = Factorchi_Font_Registry::normalize_key($data['font_family']);
        }

        if (isset($_POST['allowed_statuses']) && is_array($_POST['allowed_statuses'])) {
            $data['allowed_statuses'] = array_map('sanitize_key', wp_unslash($_POST['allowed_statuses']));
        }

        if (isset($_POST['auto_send_statuses']) && is_array($_POST['auto_send_statuses'])) {
            $data['auto_send_statuses'] = array_map('sanitize_key', wp_unslash($_POST['auto_send_statuses']));
        }

        if (isset($_POST['auto_send_channels']) && is_array($_POST['auto_send_channels'])) {
            $data['auto_send_channels'] = array_map('sanitize_key', wp_unslash($_POST['auto_send_channels']));
        }

        $data['survey_sms_delay_days']   = max(0, (int) ($_POST['survey_sms_delay_days'] ?? 3));
        $data['survey_email_delay_days'] = max(0, (int) ($_POST['survey_email_delay_days'] ?? 3));

        if (isset($data['print_per_page']) && !in_array($data['print_per_page'], ['1', '2', '4'], true)) {
            $data['print_per_page'] = '1';
        }
        if (isset($data['print_page_size']) && !in_array($data['print_page_size'], ['a4', 'a5'], true)) {
            $data['print_page_size'] = 'a4';
        }

        $font_size_keys = [
            'font_size_invoice',
            'font_size_pre_invoice',
            'font_size_post_label',
            'font_size_order_label',
            'font_size_orders',
            'font_size_label',
        ];
        foreach ($font_size_keys as $font_key) {
            if (isset($data[$font_key])) {
                $data[$font_key] = (string) max(10, min(24, (int) $data[$font_key]));
            }
        }

        if (isset($data['product_image_size'])) {
            $data['product_image_size'] = (string) max(24, min(200, (int) $data['product_image_size']));
        }

        Factorchi_Settings::update($data);

        wp_safe_redirect(add_query_arg(['page' => 'factorchi', 'tab' => sanitize_key(wp_unslash($_POST['factorchi_tab'] ?? 'general')), 'updated' => '1'], admin_url('admin.php')));
        exit;
    }
}
