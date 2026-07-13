<?php

if (!defined('ABSPATH')) {
    exit;
}

$labels = new Factorchi_Labels();
$render = new Factorchi_View_Render();

$id   = $this->get_order_id();
$type = $this->type;
$font = $this->get_font_family();

$shop     = new Factorchi_Shop($id, $type);
$customer = new Factorchi_Customer_Data($id, $type);
$total    = new Factorchi_Total_Table($id, $type);
$products = new Factorchi_Products_Table($id, $type);

$data = $this->is_preview()
    ? Factorchi_Preview_Sample::get_data($type)
    : Factorchi_View_Render::build_data($id, $type);

$margin = get_fci_settings($type . '-margin', get_fci_settings(str_replace('-', '_', $type) . '_margin', '10'));
$print_size = method_exists($this, 'get_print_size') ? $this->get_print_size() : 'a4';

/** @var bool $fc_document_embed When true, only prepare data (batch print). */
if (!empty($fc_document_embed)) {
    return;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php echo esc_html($this->type); ?>(<?php echo esc_html((string) $data['shop_order_id']); ?>)</title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
    <?php echo $this->append_styles(); ?>
    <style>
        <?php if ($this->type === 'post-label') : ?>
        @page {
            size: <?php echo $print_size === 'a5' ? 'A5' : 'A4'; ?> portrait;
            margin: 0;
        }
        <?php else : ?>
        @page { size: auto; margin: 0; }
        <?php endif; ?>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="<?php echo esc_attr($this->append_body_class()); ?>">
<div style="<?php echo $margin ? 'margin:' . esc_attr((string) $margin) . 'px' : ''; ?>">
