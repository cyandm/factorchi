<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Invoice_Router
{
    public function __construct()
    {
        add_action('template_redirect', [$this, 'maybe_render']);
        add_filter('woocommerce_hidden_order_itemmeta', [$this, 'filter_hidden_itemmeta'], 10, 1);
    }

    public function maybe_render(): void
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'factorchi-show') {
            return;
        }

        $type     = isset($_GET['type']) ? sanitize_key(wp_unslash($_GET['type'])) : 'invoice';
        $order_id = isset($_GET['order-id']) ? wp_unslash($_GET['order-id']) : '';
        $view     = isset($_GET['view']) ? sanitize_file_name(wp_unslash($_GET['view'])) : '';
        $is_preview = $this->is_preview_request();

        if ($is_preview) {
            $this->render_admin_preview($type, $view);
            return;
        }

        if ($type === 'pre-invoice') {
            $this->render_pre_invoice($view);
            return;
        }

        if ($type === 'orders' || $type === 'order-label') {
            wp_die(esc_html__('این نوع سند حذف شده است.', 'factorchi'), '', ['response' => 404]);
        }

        $order_ids = $this->parse_order_ids($order_id);
        if ($order_ids === []) {
            wp_die(esc_html__('شناسه سفارش نامعتبر است.', 'factorchi'));
        }

        foreach ($order_ids as $id) {
            if (!$this->user_can_view_order($id)) {
                wp_die(esc_html__('دسترسی مجاز نیست.', 'factorchi'));
            }
        }

        $invoice_view = new Factorchi_Invoice_View($type, count($order_ids) === 1 ? $order_ids[0] : $order_ids, $view);
        $invoice_view->render();
        exit;
    }

    /**
     * Prefer fc_preview (avoids WP reserved "preview" query var). Accept legacy preview=1|true.
     */
    private function is_preview_request(): bool
    {
        if (isset($_GET['fc_preview'])) {
            return in_array((string) wp_unslash($_GET['fc_preview']), ['1', 'true'], true);
        }

        if (isset($_GET['preview'])) {
            return in_array((string) wp_unslash($_GET['preview']), ['1', 'true'], true);
        }

        return false;
    }

    private function render_admin_preview(string $type, string $view): void
    {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('دسترسی مجاز نیست.', 'factorchi'), '', ['response' => 403]);
        }

        $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
        if ($nonce === '' || !wp_verify_nonce($nonce, Factorchi_Preview_Sample::NONCE_ACTION)) {
            wp_die(esc_html__('دسترسی مجاز نیست.', 'factorchi'), '', ['response' => 403]);
        }

        if ($type === 'orders' || $type === 'order-label') {
            wp_die(esc_html__('این نوع سند حذف شده است.', 'factorchi'), '', ['response' => 404]);
        }

        Factorchi_Preview_Sample::render($type, $view);
        exit;
    }

    private function render_pre_invoice(string $view): void
    {
        if (!WC()->cart || WC()->cart->is_empty()) {
            wp_die(esc_html__('سبد خرید خالی است.', 'factorchi'));
        }

        $default = (string) factorchi_get_setting('pre_invoice_view', 'modern');
        $invoice_view = new Factorchi_Invoice_View(
            'pre-invoice',
            0,
            $view !== '' ? $view : $default
        );
        $invoice_view->render();
        exit;
    }

    /**
     * @return array<int, int>
     */
    private function parse_order_ids($raw): array
    {
        if (is_array($raw)) {
            return array_values(array_filter(array_map('intval', $raw)));
        }

        $parts = array_map('trim', explode(',', (string) $raw));
        return array_values(array_filter(array_map('intval', $parts)));
    }

    public function user_can_view_order(int $order_id): bool
    {
        if (current_user_can('manage_woocommerce')) {
            return true;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return false;
        }

        $allowed_statuses = (array) factorchi_get_setting('allowed_statuses', ['processing', 'completed']);
        if (!in_array($order->get_status(), $allowed_statuses, true)) {
            return false;
        }

        $user_id = get_current_user_id();
        if ($user_id > 0 && (int) $order->get_customer_id() === $user_id) {
            return true;
        }

        if (factorchi_get_setting('guest_access', 'yes') === 'yes') {
            $token = isset($_GET['token']) ? sanitize_text_field(wp_unslash($_GET['token'])) : '';
            if ($token !== '' && hash_equals(self::generate_access_token($order_id), $token)) {
                return true;
            }
        }

        return (bool) apply_filters('factorchi_can_view_invoice', false, $order_id, $order);
    }

    public static function generate_access_token(int $order_id): string
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            return '';
        }

        $key = wp_salt('auth') . '|' . $order_id . '|' . $order->get_order_key();
        return substr(hash_hmac('sha256', (string) $order_id, $key), 0, 32);
    }

    /**
     * @param array<int, string> $meta
     * @return array<int, string>
     */
    public function filter_hidden_itemmeta(array $meta): array
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'factorchi-show') {
            return $meta;
        }

        $delete = (string) factorchi_get_setting('line_items_delete', '');
        if ($delete === '') {
            return $meta;
        }

        foreach (explode(',', $delete) as $item) {
            $item = trim($item);
            if ($item !== '') {
                $meta[] = $item;
            }
        }

        return $meta;
    }
}
