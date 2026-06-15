<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @var Factorchi_Invoice_View $this
 * @var array<int, int>        $order_ids
 * @var string                 $print_size
 * @var int                    $per_page
 */

$body_classes = $this->get_print_body_classes();
$type_label   = $this->type === 'post-label' ? __('برچسب پستی', 'factorchi') : __('فاکتور', 'factorchi');
$first_id     = $order_ids[0] ?? 0;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($type_label); ?> — <?php echo esc_html((string) $first_id); ?></title>
    <?php echo $this->append_print_styles(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <style>
        @media print {
            @page {
                size: <?php echo $print_size === 'a5' ? 'A5' : 'A4'; ?> portrait;
                margin: 6mm;
            }
        }
    </style>
</head>
<body class="<?php echo esc_attr($body_classes); ?>">
    <div class="fc-print-toolbar" role="toolbar" aria-label="<?php esc_attr_e('تنظیمات چاپ', 'factorchi'); ?>">
        <label>
            <?php esc_html_e('اندازه برگه', 'factorchi'); ?>
            <select id="fc-print-size" data-fc-print-size>
                <option value="a4" <?php selected($print_size, 'a4'); ?>><?php esc_html_e('A4', 'factorchi'); ?></option>
                <option value="a5" <?php selected($print_size, 'a5'); ?>><?php esc_html_e('A5', 'factorchi'); ?></option>
            </select>
        </label>
        <label>
            <?php esc_html_e('تعداد در برگه', 'factorchi'); ?>
            <select id="fc-print-per-page" data-fc-print-per-page>
                <option value="1" <?php selected($per_page, 1); ?>><?php esc_html_e('۱ فاکتور در برگه', 'factorchi'); ?></option>
                <option value="2" <?php selected($per_page, 2); ?>><?php esc_html_e('۲ در A4', 'factorchi'); ?></option>
                <option value="4" <?php selected($per_page, 4); ?>><?php esc_html_e('۴ در A4', 'factorchi'); ?></option>
            </select>
        </label>
        <button type="button" class="fc-print-btn" id="fc-print-trigger"><?php esc_html_e('چاپ', 'factorchi'); ?></button>
    </div>

    <div class="fc-print-root">
        <?php include FACTORCHI_VIEW_PATH . 'print/batch.php'; ?>
    </div>

    <?php if (factorchi_get_setting('use_persian_number', 'yes') === 'yes' && !$this->get_check_email()) : ?>
        <?php echo Factorchi_View_Render::footer_js(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endif; ?>

    <script>
        window.FACTORCHI_PRINT = <?php echo wp_json_encode([
            'printSize' => $print_size,
            'perPage'   => $per_page,
            'storageKey' => 'factorchi_print_prefs',
        ]); ?>;
    </script>
    <script src="<?php echo esc_url(FACTORCHI_JS_URL . 'print-toolbar.js?ver=' . FACTORCHI_VERSION); ?>"></script>
</body>
</html>
