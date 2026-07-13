<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Order_Metabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'register_metabox']);
        add_action('admin_head', [$this, 'output_nonce_meta']);
    }

    public function register_metabox(): void
    {
        $screens = ['shop_order', 'woocommerce_page_wc-orders'];
        foreach ($screens as $screen) {
            add_meta_box(
                'factorchi-order-actions',
                __('فاکتورچی', 'factorchi'),
                [$this, 'render_metabox'],
                $screen,
                'side',
                'high'
            );
        }
    }

    /**
     * @param WP_Post|WC_Order $post_or_order
     */
    public function render_metabox($post_or_order): void
    {
        $order = $post_or_order instanceof WC_Order ? $post_or_order : wc_get_order($post_or_order->ID);
        if (!$order) {
            return;
        }

        $this->render_buttons((int) $order->get_id(), $order);
    }

    private function render_buttons(int $order_id, WC_Order $order): void
    {
        $invoice_url    = factorchi_get_invoice_url($order);
        $post_label_url = factorchi_get_invoice_url($order, 'post-label');
        ?>
        <div class="factorchi-order-metabox" style="display:flex;flex-direction:column;gap:8px;">
            <?php if ($invoice_url !== '') : ?>
                <a class="button button-primary" target="_blank" href="<?php echo esc_url($invoice_url); ?>">
                    <?php esc_html_e('چاپ فاکتور', 'factorchi'); ?>
                </a>
            <?php endif; ?>
            <?php if ($post_label_url !== '') : ?>
                <a class="button" target="_blank" href="<?php echo esc_url($post_label_url); ?>">
                    <?php esc_html_e('چاپ برچسب پستی', 'factorchi'); ?>
                </a>
            <?php endif; ?>
            <button type="button" class="button" id="factorchi-send-invoice" data-id="<?php echo esc_attr((string) $order_id); ?>">
                <?php esc_html_e('ارسال فاکتور', 'factorchi'); ?>
            </button>
            <?php if ($order->needs_payment()) : ?>
                <button type="button" class="button" id="factorchi-send-invoice-payment" data-id="<?php echo esc_attr((string) $order_id); ?>">
                    <?php esc_html_e('ارسال لینک پرداخت', 'factorchi'); ?>
                </button>
            <?php endif; ?>
            <button type="button" class="button" id="factorchi-send-invoice-sms" data-id="<?php echo esc_attr((string) $order_id); ?>">
                <?php esc_html_e('ارسال پیامک', 'factorchi'); ?>
            </button>
            <button type="button" class="button" id="factorchi-send-invoice-wa" data-id="<?php echo esc_attr((string) $order_id); ?>">
                <?php esc_html_e('ارسال واتساپ', 'factorchi'); ?>
            </button>
        </div>
        <?php
    }

    public function output_nonce_meta(): void
    {
        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        if (!$screen || !in_array($screen->id, ['shop_order', 'woocommerce_page_wc-orders', 'edit-shop_order'], true)) {
            return;
        }

        echo '<meta name="factorchi-nonce" content="' . esc_attr(wp_create_nonce('factorchi_admin')) . '" />';
    }
}
