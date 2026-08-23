<?php

if (!defined('ABSPATH')) {
    exit;
}

final class Factorchi_Plugin
{
    private static ?Factorchi_Plugin $instance = null;

    public static function instance(): Factorchi_Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
        register_activation_hook(FACTORCHI_FILE, [Factorchi_Settings::class, 'activate']);
        register_deactivation_hook(FACTORCHI_FILE, [Factorchi_Survey::class, 'deactivate']);
    }

    public function init(): void
    {
        load_plugin_textdomain('factorchi', false, dirname(FACTORCHI_BASENAME) . '/languages');

        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', [$this, 'woocommerce_notice']);
            return;
        }

        add_action('before_woocommerce_init', static function (): void {
            if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                    'custom_order_tables',
                    FACTORCHI_FILE,
                    true
                );
            }
        });

        new Factorchi_Assets();
        new Factorchi_Invoice_Router();
        Factorchi_Notify_Dispatcher::instance();
        new Factorchi_WooCommerce();
        new Factorchi_Ajax();
        new Factorchi_Frontend();
        new Factorchi_Survey();

        if (is_admin()) {
            new Factorchi_Admin();
            new Factorchi_Order_Metabox();
        }
    }

    public function woocommerce_notice(): void
    {
        echo '<div class="notice notice-error"><p>' . esc_html__(
            'افزونه Factorchi نیاز به نصب و فعال‌سازی ووکامرس دارد.',
            'factorchi'
        ) . '</p></div>';
    }
}
