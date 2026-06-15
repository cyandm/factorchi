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
        'order_label_view'       => 'order-label',
        'orders_view'            => 'orders',
    ];

    /**
     * @return array<string, string> slug => label
     */
    public static function get_options(string $folder): array
    {
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
            'view-1'     => __('قالب ۱', 'factorchi'),
            'view-2'     => __('قالب ۲', 'factorchi'),
            'view-3'     => __('قالب ۳', 'factorchi'),
            'view-4'     => __('قالب ۴', 'factorchi'),
            'view-5'     => __('قالب ۵', 'factorchi'),
            'view-6'     => __('قالب ۶', 'factorchi'),
            'view-7'     => __('قالب ۷', 'factorchi'),
            'view-8'     => __('قالب ۸', 'factorchi'),
            'view-mini'  => __('قالب مینی', 'factorchi'),
            'view-pdf'   => __('قالب PDF', 'factorchi'),
            'view-1-new' => __('قالب ۱ (جدید)', 'factorchi'),
        ];

        return $labels[$slug] ?? str_replace(['view-', '-'], ['قالب ', ' '], $slug);
    }

    public static function preview_url(string $setting_key, string $view_slug): string
    {
        $type_map = [
            'invoice_default_view' => 'invoice',
            'pre_invoice_view'     => 'pre-invoice',
            'post_label_view'      => 'post-label',
            'order_label_view'     => 'order-label',
            'orders_view'          => 'orders',
        ];

        $type = $type_map[$setting_key] ?? 'invoice';

        return add_query_arg([
            'action' => 'factorchi-show',
            'type'   => $type,
            'view'   => $view_slug,
        ], home_url('/'));
    }
}
