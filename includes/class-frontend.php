<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Frontend
{
    public function __construct()
    {
        add_filter('woocommerce_get_view_order_url', [$this, 'filter_view_order_url'], 20, 2);

        if (factorchi_get_setting('show_on_my_account', 'yes') === 'yes') {
            add_filter('woocommerce_my_account_my_orders_actions', [$this, 'my_account_actions'], 10, 2);
            add_action('woocommerce_view_order', [$this, 'render_order_details_link'], 15);
        }

        if (factorchi_get_setting('show_on_thankyou', 'yes') === 'yes') {
            add_action('woocommerce_thankyou', [$this, 'thankyou_link'], 20);
        }

        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets(): void
    {
        if (!is_account_page() && !is_checkout() && !is_wc_endpoint_url('view-order')) {
            return;
        }

        wp_enqueue_style(
            'factorchi-frontend',
            FACTORCHI_CSS_URL . 'frontend.css',
            [],
            FACTORCHI_VERSION
        );
    }

    /**
     * Replace WooCommerce view-order URL with invoice URL on the storefront.
     * Works with any theme that uses $order->get_view_order_url() — no theme edits required.
     */
    public function filter_view_order_url(string $url, WC_Order $order): string
    {
        if (is_admin() || factorchi_get_setting('replace_view_order_url', 'yes') !== 'yes') {
            return $url;
        }

        if (!$this->can_show_invoice_link($order)) {
            return $url;
        }

        $invoice_url = factorchi_get_invoice_url_for_order($order);

        return $invoice_url !== '' ? $invoice_url : $url;
    }

    /**
     * @param array<string, array<string, string>> $actions
     * @return array<string, array<string, string>>
     */
    public function my_account_actions(array $actions, WC_Order $order): array
    {
        if (!$this->can_show_invoice_link($order)) {
            return $actions;
        }

        $url = factorchi_get_invoice_url_for_order($order);
        if ($url === '') {
            return $actions;
        }

        $actions['factorchi-invoice'] = [
            'url'  => $url,
            'name' => __('مشاهده فاکتور', 'factorchi'),
        ];

        return $actions;
    }

    public function thankyou_link(int $order_id): void
    {
        if (factorchi_get_setting('replace_view_order_url', 'yes') === 'yes') {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order || !$this->can_show_invoice_link($order)) {
            return;
        }

        $url = factorchi_get_invoice_url_for_order($order);
        if ($url === '') {
            return;
        }

        echo '<p class="factorchi-thankyou-invoice"><a class="button factorchi-invoice-btn" href="'
            . esc_url($url) . '" target="_blank" rel="noopener">'
            . esc_html__('مشاهده فاکتور', 'factorchi') . '</a></p>';
    }

    public function render_order_details_link(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if (!$order || !$this->can_show_invoice_link($order)) {
            return;
        }

        $url = factorchi_get_invoice_url_for_order($order);
        if ($url === '') {
            return;
        }

        echo '<p class="factorchi-order-invoice-link">'
            . '<a class="button factorchi-invoice-btn" href="' . esc_url($url) . '" target="_blank" rel="noopener">'
            . esc_html__('مشاهده فاکتور', 'factorchi')
            . '</a></p>';
    }

    private function can_show_invoice_link(WC_Order $order): bool
    {
        if (current_user_can('manage_woocommerce')) {
            return true;
        }

        $allowed_statuses = (array) factorchi_get_setting('allowed_statuses', ['processing', 'completed']);
        if (!in_array($order->get_status(), $allowed_statuses, true)) {
            return false;
        }

        $user_id = get_current_user_id();
        if ($user_id > 0 && (int) $order->get_customer_id() === $user_id) {
            return true;
        }

        if (factorchi_get_setting('guest_access', 'yes') === 'yes' && $user_id === 0) {
            return true;
        }

        return (bool) apply_filters('factorchi_can_show_invoice_link', false, $order);
    }
}
