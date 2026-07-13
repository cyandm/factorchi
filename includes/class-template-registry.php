<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Template_Registry
{
    /** @var array<string, string> */
    private const SETTING_FOLDER_MAP = [
        'invoice_default_view' => 'invoice',
        'pre_invoice_view'       => 'invoice',
        'post_label_view'        => 'post-label',
    ];

    /**
     * @return array<string, string> slug => label
     */
    public static function get_options(string $folder): array
    {
        if ($folder === 'invoice') {
            return [
                'modern'  => __('مدرن', 'factorchi'),
                'classic' => __('کلاسیک', 'factorchi'),
            ];
        }

        if ($folder === 'post-label') {
            return [
                'modern-a4'  => __('مدرن سایز A4', 'factorchi'),
                'modern-a5'  => __('مدرن سایز A5', 'factorchi'),
                'classic-a4' => __('کلاسیک سایز A4', 'factorchi'),
                'classic-a5' => __('کلاسیک سایز A5', 'factorchi'),
            ];
        }

        $path = FACTORCHI_VIEW_PATH . 'front/' . $folder;
        if (!is_dir($path)) {
            return [];
        }

        $files = glob($path . '/view*.php') ?: [];
        $options = [];

        foreach ($files as $file) {
            $slug = basename($file, '.php');
            $options[$slug] = self::label_for_slug($slug);
        }

        ksort($options);

        return $options;
    }

    /**
     * @return array<string, string>
     */
    public static function get_options_for_setting(string $setting_key): array
    {
        $folder = self::SETTING_FOLDER_MAP[$setting_key] ?? 'invoice';

        return self::get_options($folder);
    }

    public static function label_for_slug(string $slug): string
    {
        $labels = [
            'modern'     => __('مدرن', 'factorchi'),
            'classic'    => __('کلاسیک', 'factorchi'),
            'modern-a4'  => __('مدرن سایز A4', 'factorchi'),
            'modern-a5'  => __('مدرن سایز A5', 'factorchi'),
            'classic-a4' => __('کلاسیک سایز A4', 'factorchi'),
            'classic-a5' => __('کلاسیک سایز A5', 'factorchi'),
        ];

        return $labels[$slug] ?? $slug;
    }

    public static function preview_url(string $setting_key, string $view_slug): string
    {
        if (!current_user_can('manage_woocommerce')) {
            return '';
        }

        $type_map = [
            'invoice_default_view' => 'invoice',
            'pre_invoice_view'     => 'pre-invoice',
            'post_label_view'      => 'post-label',
        ];

        $type = $type_map[$setting_key] ?? 'invoice';

        $args = [
            'action'     => 'factorchi-show',
            'type'       => $type,
            'view'       => $view_slug,
            'fc_preview' => '1',
            '_wpnonce'   => wp_create_nonce(Factorchi_Preview_Sample::NONCE_ACTION),
        ];

        if ($type === 'post-label') {
            $args['print-size'] = Factorchi_Settings::post_label_size_from_view($view_slug);
        }

        return add_query_arg($args, home_url('/'));
    }

    /**
     * Preview URL without view — JS appends/replaces view on select change.
     */
    public static function preview_base_url(string $setting_key): string
    {
        if (!current_user_can('manage_woocommerce')) {
            return '';
        }

        $type_map = [
            'invoice_default_view' => 'invoice',
            'pre_invoice_view'     => 'pre-invoice',
            'post_label_view'      => 'post-label',
        ];

        $type = $type_map[$setting_key] ?? 'invoice';

        return add_query_arg([
            'action'     => 'factorchi-show',
            'type'       => $type,
            'fc_preview' => '1',
            '_wpnonce'   => wp_create_nonce(Factorchi_Preview_Sample::NONCE_ACTION),
        ], home_url('/'));
    }
}
