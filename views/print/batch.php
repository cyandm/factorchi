<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @var Factorchi_Invoice_View $this
 * @var array<int, int>        $order_ids
 * @var int                    $per_page
 */

$compact_template = $this->get_compact_template_path();
$chunks           = array_chunk($order_ids, $per_page > 1 ? $per_page : 1);
?>
<div class="fc-print-grid">
    <?php if ($per_page === 1) : ?>
        <?php foreach ($order_ids as $order_id) : ?>
            <div class="fc-print-sheet fc-print-sheet--solo">
                <?php
                $data = Factorchi_View_Render::build_data((int) $order_id, $this->type);
                include $compact_template;
                ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <?php foreach ($chunks as $chunk) : ?>
            <div class="fc-print-page">
                <?php foreach ($chunk as $order_id) : ?>
                    <div class="fc-print-sheet">
                        <?php
                        $data = Factorchi_View_Render::build_data((int) $order_id, $this->type);
                        include $compact_template;
                        ?>
                    </div>
                <?php endforeach; ?>
                <?php
                $missing = $per_page - count($chunk);
                for ($i = 0; $i < $missing; $i++) :
                    ?>
                    <div class="fc-print-sheet fc-print-sheet--empty" aria-hidden="true"></div>
                <?php endfor; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
