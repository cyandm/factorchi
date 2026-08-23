<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_WooCommerce
{
    public function __construct()
    {
        add_filter('woocommerce_admin_order_actions', [$this, 'order_actions'], 20, 2);
        add_filter('bulk_actions-edit-shop_order', [$this, 'bulk_actions']);
        add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'bulk_actions']);
        add_action('manage_shop_order_posts_custom_column', [$this, 'order_column_links'], 20, 2);
        add_action('woocommerce_shop_order_list_table_custom_column', [$this, 'order_column_links_hpos'], 20, 2);
        add_filter('post_class', [$this, 'dashboard_class']);
        add_shortcode('factorchi-pre-invoice', [$this, 'pre_invoice_shortcode']);

        if (factorchi_get_setting('show_pre_invoice_cart', 'no') === 'yes') {
            add_action('woocommerce_after_cart_table', [$this, 'render_cart_pre_invoice_link']);
        }
    }

    /**
     * @param array<string, array<string, string>> $actions
     * @return array<string, array<string, string>>
     */
    public function order_actions(array $actions, WC_Order $order): array
    {
        $url = factorchi_get_invoice_url($order);
        if ($url !== '') {
            $actions['factorchi_invoice'] = [
                'url'    => $url,
                'name'   => __('چاپ فاکتور', 'factorchi'),
                'action' => 'factorchi-invoice',
            ];
        }
        return $actions;
    }

    /**
     * @param array<string, string> $actions
     * @return array<string, string>
     */
    public function bulk_actions(array $actions): array
    {
        $actions['factorchi_bulk_print_invoice']    = __('چاپ فاکتور', 'factorchi');
        $actions['factorchi_bulk_print_post_label'] = __('چاپ برچسب پستی', 'factorchi');
        return $actions;
    }

    public function order_column_links(string $column, $post_id): void
    {
        if ($column !== 'order_number') {
            return;
        }

        $this->render_order_column_links((int) $post_id);
    }

    public function order_column_links_hpos(string $column, $order): void
    {
        if ($column !== 'order_number') {
            return;
        }

        $order_id = $order instanceof WC_Order ? (int) $order->get_id() : (int) $order;
        $this->render_order_column_links($order_id);
    }

    private function render_order_column_links(int $order_id): void
    {
        if ($order_id <= 0) {
            return;
        }

        $labels = [
            'invoice'    => __('فاکتور', 'factorchi'),
            'post-label' => __('برچسب پستی', 'factorchi'),
        ];

        $links = [];
        foreach ($labels as $type => $label) {
            $url = factorchi_get_invoice_url($order_id, $type);
            if ($url !== '') {
                $links[] = '<a class="factorchi-' . esc_attr(str_replace('-', '_', $type)) . '" href="' . esc_url($url) . '" target="_blank">' . esc_html($label) . '</a>';
            }
        }

        if ($links !== []) {
            echo '<div class="factorchi-order-links">' . implode(' | ', $links) . '</div>';
        }
    }

    /**
     * @param string[] $classes
     * @return string[]
     */
    public function dashboard_class(array $classes): array
    {
        global $post;
        if (is_admin() && $post && Factorchi_Helper::received((int) $post->ID) && isset($_GET['post_type']) && $_GET['post_type'] === 'shop_order') {
            $classes[] = 'factorchi-invoice-received';
        }
        return $classes;
    }

    public function pre_invoice_shortcode(): string
    {
        $url = add_query_arg([
            'action' => 'factorchi-show',
            'type'   => 'pre-invoice',
        ], home_url('/'));

        return '<a class="factorchi-pre-invoice-link" href="' . esc_url($url) . '" target="_blank">' . esc_html__('مشاهده پیش‌فاکتور', 'factorchi') . '</a>';
    }

    public function render_cart_pre_invoice_link(): void
    {
        echo do_shortcode('[factorchi-pre-invoice]');
    }
}
