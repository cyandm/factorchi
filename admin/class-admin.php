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
        add_action('wp_ajax_factorchi_save_toggle', [$this, 'ajax_save_toggle']);
        add_action('admin_enqueue_scripts', [$this, 'settings_assets']);
    }

    /**
     * Toggle keys grouped by settings tab.
     *
     * @return array<string, list<string>>
     */
    private function checkboxes_by_tab(): array
    {
        return [
            'general'   => [],
            'templates' => [
                'use_persian_number',
                'use_jalali_date',
                'show_print_date',
                'show_order_date',
                'show_date_time',
                'enable_border_radius',
                'show_product_image',
                'show_product_row_number',
                'show_barcode_top',
                'show_barcode_top_text',
                'show_barcode_under_title',
                'show_barcode_under_title_text',
                'show_barcode_bottom',
                'show_barcode_bottom_text',
                'show_payment_method',
                'show_shipping_method',
                'show_transaction_id',
                'show_customer_note_buyer',
                'show_customer_note_footer',
                'address_enter_spacing_below',
                'filter_product_name_codes',
                'product_attrs_show_label',
                'compact_party_texts',
                'show_tearoff',
                'show_tearoff_recipient',
                'show_tearoff_payment',
                'show_tearoff_tracking',
                'use_payzito_gateway_tracking',
                'show_tearoff_order_date',
                'show_tearoff_order_id',
                'show_tearoff_customer_note',
            ],
            'access'    => [
                'guest_access',
                'show_on_thankyou',
                'show_on_my_account',
                'replace_view_order_url',
                'show_pre_invoice_cart',
            ],
            'notify'    => [
                'channel_email',
                'channel_sms',
                'channel_whatsapp',
                'channel_socials',
                'channel_telegram',
                'channel_bale',
            ],
            'tapin'     => [
                'tapin_status',
            ],
            'survey'    => [
                'survey_enabled',
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private function allowed_toggle_keys(): array
    {
        $keys = [];
        foreach ($this->checkboxes_by_tab() as $tab_keys) {
            foreach ($tab_keys as $key) {
                $keys[] = $key;
            }
        }

        return $keys;
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
            wp_localize_script(
                'factorchi-admin-settings',
                'factorchiSettings',
                [
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'nonce'   => wp_create_nonce('factorchi_save_toggle'),
                    'i18n'    => [
                        'saving' => __('در حال ذخیره…', 'factorchi'),
                        'saved'  => __('ذخیره شد', 'factorchi'),
                        'error'  => __('خطا در ذخیره سوئیچ', 'factorchi'),
                    ],
                ]
            );
            wp_enqueue_media();
        }
    }

    public function render_settings(): void
    {
        $settings = array_merge(Factorchi_Settings::defaults(), get_option(Factorchi_Settings::OPTION_KEY, []));
        $tab      = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'general';
        $allowed  = [
            'general',
            'templates',
            'access',
            'notify',
            'sms',
            'bots',
            'auto',
            'survey',
            'tapin',
        ];
        if (!in_array($tab, $allowed, true)) {
            $tab = 'general';
        }
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

        $tab = sanitize_key(wp_unslash($_POST['factorchi_tab'] ?? 'general'));

        // Only update toggles belonging to the submitted tab. Missing checkboxes
        // from other tabs must not be forced to "no".
        $data = [];
        foreach ($this->checkboxes_by_tab()[$tab] ?? [] as $key) {
            $data[$key] = !empty($_POST[$key]) ? 'yes' : 'no';
        }

        $text_fields = [
            'shop_name',
            'shop_url',
            'shop_email',
            'shop_phone',
            'shop_address',
            'shop_postcode',
            'shop_economical',
            'shop_reg',
            'shop_logo',
            'font_family',
            'invoice_default_view',
            'pre_invoice_view',
            'post_label_view',
            'invoice_margin',
            'pre_invoice_margin',
            'post_label_margin',
            'section_gap_a4',
            'section_gap_a5',
            'font_size_invoice',
            'font_size_pre_invoice',
            'font_size_post_label',
            'font_size_label',
            'font_size_shop',
            'font_size_buyer',
            'product_image_size',
            'shop_logo_size',
            'print_page_size',
            'email_subject',
            'sms_panel',
            'sms_username',
            'sms_password',
            'sms_sender',
            'sms_pattern_id',
            'whatsapp_api_url',
            'socials_api_url',
            'telegram_bot_token',
            'telegram_chat_id',
            'bale_bot_token',
            'bale_chat_id',
            'tapin_barcode_meta',
            'line_items_delete',
            'survey_status',
            'default_invoice_type',
            'product_attrs_mode',
            'customer_address_source',
        ];

        foreach ($text_fields as $key) {
            if (isset($_POST[$key])) {
                $data[$key] = sanitize_text_field(wp_unslash($_POST[$key]));
            }
        }

        $textarea_fields = [
            'shop_note',
            'email_body',
            'sms_message',
            'whatsapp_message',
            'socials_message',
            'telegram_message',
            'bale_message',
        ];

        foreach ($textarea_fields as $key) {
            if (isset($_POST[$key])) {
                $data[$key] = sanitize_textarea_field(wp_unslash($_POST[$key]));
            }
        }

        if (isset($data['customer_address_source'])
            && !in_array($data['customer_address_source'], ['woocommerce', 'shipping', 'billing'], true)
        ) {
            $data['customer_address_source'] = 'woocommerce';
        }

        if (isset($data['font_family'])) {
            $data['font_family'] = Factorchi_Font_Registry::normalize_key($data['font_family']);
        }

        if (isset($_POST['allowed_statuses']) && is_array($_POST['allowed_statuses'])) {
            $data['allowed_statuses'] = array_map('sanitize_key', wp_unslash($_POST['allowed_statuses']));
        } elseif ($tab === 'access') {
            $data['allowed_statuses'] = [];
        }

        if (isset($_POST['auto_send_statuses']) && is_array($_POST['auto_send_statuses'])) {
            $data['auto_send_statuses'] = array_map('sanitize_key', wp_unslash($_POST['auto_send_statuses']));
        } elseif ($tab === 'auto') {
            $data['auto_send_statuses'] = [];
        }

        if (isset($_POST['auto_send_channels']) && is_array($_POST['auto_send_channels'])) {
            $data['auto_send_channels'] = array_map('sanitize_key', wp_unslash($_POST['auto_send_channels']));
        } elseif ($tab === 'auto') {
            $data['auto_send_channels'] = [];
        }

        if ($tab === 'survey') {
            $data['survey_sms_delay_days'] = max(0, (int) ($_POST['survey_sms_delay_days'] ?? 3));
        }

        if (isset($data['print_page_size']) && !in_array($data['print_page_size'], ['a4', 'a5'], true)) {
            $data['print_page_size'] = 'a4';
        }

        if (isset($data['product_attrs_mode']) && !in_array($data['product_attrs_mode'], ['all', 'selected', 'none'], true)) {
            $data['product_attrs_mode'] = 'all';
        }

        // sanitize_title (not sanitize_key) keeps percent-encoded Persian attribute slugs intact.
        if (isset($_POST['product_attrs_selected']) && is_array($_POST['product_attrs_selected'])) {
            $data['product_attrs_selected'] = array_values(array_filter(array_map(
                static fn($key) => sanitize_title(sanitize_text_field((string) $key)),
                wp_unslash($_POST['product_attrs_selected'])
            )));
        } elseif ($tab === 'templates') {
            $data['product_attrs_selected'] = [];
        }

        $font_size_keys = [
            'font_size_invoice',
            'font_size_pre_invoice',
            'font_size_post_label',
            'font_size_label',
            'font_size_shop',
            'font_size_buyer',
        ];
        foreach ($font_size_keys as $font_key) {
            if (isset($data[$font_key])) {
                $data[$font_key] = (string) max(10, min(24, (int) $data[$font_key]));
            }
        }

        if (isset($data['product_image_size'])) {
            $data['product_image_size'] = (string) max(24, min(200, (int) $data['product_image_size']));
        }

        if (isset($data['shop_logo_size'])) {
            $data['shop_logo_size'] = (string) max(24, min(300, (int) $data['shop_logo_size']));
        }

        $section_gap_keys = ['section_gap_a4', 'section_gap_a5'];
        foreach ($section_gap_keys as $gap_key) {
            if (isset($data[$gap_key])) {
                $data[$gap_key] = (string) max(0, min(60, (int) $data[$gap_key]));
            }
        }

        if (isset($data['whatsapp_api_url']) && $data['whatsapp_api_url'] !== '' && !factorchi_is_safe_remote_url($data['whatsapp_api_url'])) {
            $data['whatsapp_api_url'] = '';
        }
        if (isset($data['socials_api_url']) && $data['socials_api_url'] !== '' && !factorchi_is_safe_remote_url($data['socials_api_url'])) {
            $data['socials_api_url'] = '';
        }

        Factorchi_Settings::update($data);

        wp_safe_redirect(add_query_arg(['page' => 'factorchi', 'tab' => $tab, 'updated' => '1'], admin_url('admin.php')));
        exit;
    }

    public function ajax_save_toggle(): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(['message' => __('Unauthorized', 'factorchi')], 403);
        }

        check_ajax_referer('factorchi_save_toggle', 'nonce');

        $key   = sanitize_key(wp_unslash($_POST['key'] ?? ''));
        $value = (isset($_POST['value']) && (string) wp_unslash($_POST['value']) === 'yes') ? 'yes' : 'no';

        if ($key === '' || !in_array($key, $this->allowed_toggle_keys(), true)) {
            wp_send_json_error(['message' => __('کلید نامعتبر است.', 'factorchi')], 400);
        }

        Factorchi_Settings::update([$key => $value]);

        wp_send_json_success([
            'key'   => $key,
            'value' => $value,
        ]);
    }
}
