<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * One full modern/classic document per order (same templates + margin as single print).
 *
 * @var Factorchi_Invoice_View $this
 * @var array<int, int>        $order_ids
 */

$template = $this->resolve_template_path_public();
if (!file_exists($template)) {
    wp_die(esc_html__('قالب فاکتور یافت نشد.', 'factorchi'));
}

$saved_order_id = $this->order_id;
$margin_key     = str_replace('-', '_', $this->type) . '_margin';
$margin         = (string) factorchi_get_setting($margin_key, '10');
$doc_pad        = max(0, (int) $margin);
?>
<div class="fc-print-grid">
    <?php foreach ($order_ids as $order_id) : ?>
        <div class="fc-print-sheet fc-print-sheet--solo">
            <div class="fc-document-frame" style="--fc-doc-pad: <?php echo esc_attr((string) $doc_pad); ?>px;">
                <?php
                $this->order_id    = (int) $order_id;
                $fc_document_embed = true;
                include $template;
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$this->order_id = $saved_order_id;
