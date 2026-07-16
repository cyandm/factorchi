<?php

/**
 * Plugin Name: Factorchi
 * Description: ساخت، نمایش و ارسال فاکتور و برچسب برای ووکامرس
 * Version: 1.1.0
 * Author: Amirali Dizabadi
 * Author URI: https://amiralidz.ir
 * Text Domain: factorchi
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_plugin_data')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

define('FACTORCHI_VERSION', '1.1.0');
define('FACTORCHI_FILE', __FILE__);
define('FACTORCHI_DIR', plugin_dir_path(__FILE__));
define('FACTORCHI_URL', plugin_dir_url(__FILE__));
define('FACTORCHI_BASENAME', plugin_basename(__FILE__));
define('FACTORCHI_INCLUDES', FACTORCHI_DIR . 'includes/');
define('FACTORCHI_VIEW_PATH', FACTORCHI_DIR . 'views/');
define('FACTORCHI_CSS_URL', FACTORCHI_URL . 'assets/css/');
define('FACTORCHI_FONTS_URL', FACTORCHI_URL . 'assets/fonts/');
define('FACTORCHI_JS_URL', FACTORCHI_URL . 'assets/js/');

// Compatibility constants for ported Factori templates.
define('FCI_VIEW_PATH', FACTORCHI_VIEW_PATH);
define('FCI_CSS_URL', FACTORCHI_CSS_URL);
define('FCI_JS_URL', FACTORCHI_JS_URL);

require_once FACTORCHI_INCLUDES . 'helpers.php';
require_once FACTORCHI_INCLUDES . 'class-barcode.php';
require_once FACTORCHI_INCLUDES . 'data/class-date-convert.php';
require_once FACTORCHI_INCLUDES . 'data/class-labels.php';
require_once FACTORCHI_INCLUDES . 'data/class-shop-data.php';
require_once FACTORCHI_INCLUDES . 'data/class-customer-data.php';
require_once FACTORCHI_INCLUDES . 'data/class-order-detail.php';
require_once FACTORCHI_INCLUDES . 'data/class-products-table.php';
require_once FACTORCHI_INCLUDES . 'data/class-total-table.php';
require_once FACTORCHI_INCLUDES . 'class-view-render.php';
require_once FACTORCHI_INCLUDES . 'class-helper.php';
require_once FACTORCHI_INCLUDES . 'class-settings.php';
require_once FACTORCHI_INCLUDES . 'class-font-registry.php';
require_once FACTORCHI_INCLUDES . 'class-template-registry.php';
require_once FACTORCHI_INCLUDES . 'class-preview-sample.php';
require_once FACTORCHI_INCLUDES . 'class-assets.php';
require_once FACTORCHI_INCLUDES . 'class-invoice-view.php';
require_once FACTORCHI_INCLUDES . 'class-invoice-router.php';
require_once FACTORCHI_INCLUDES . 'notify/interface-channel.php';
require_once FACTORCHI_INCLUDES . 'notify/sms/class-sms-panels.php';
require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-email.php';
require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-sms.php';
require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-whatsapp.php';
require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-socials.php';
require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-telegram.php';
require_once FACTORCHI_INCLUDES . 'notify/channels/class-channel-bale.php';
require_once FACTORCHI_INCLUDES . 'notify/class-notify-dispatcher.php';
require_once FACTORCHI_INCLUDES . 'class-ajax.php';
require_once FACTORCHI_INCLUDES . 'class-woocommerce.php';
require_once FACTORCHI_INCLUDES . 'class-frontend.php';
require_once FACTORCHI_INCLUDES . 'survey/class-survey.php';
require_once FACTORCHI_INCLUDES . 'compatibility.php';
require_once FACTORCHI_INCLUDES . 'class-plugin.php';
require_once FACTORCHI_DIR . 'admin/class-admin.php';
require_once FACTORCHI_DIR . 'admin/class-order-metabox.php';

Factorchi_Plugin::instance();
