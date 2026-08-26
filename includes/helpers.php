<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @param string $key
 * @param mixed  $default
 * @return mixed
 */
function factorchi_get_setting(string $key, $default = '')
{
    return Factorchi_Settings::get($key, $default);
}

/**
 * Document types allowed for public/admin invoice routes.
 *
 * @return string[]
 */
function factorchi_allowed_document_types(): array
{
    return (array) apply_filters('factorchi_allowed_document_types', [
        'invoice',
        'pre-invoice',
        'post-label',
        'shop-label',
        'customer-label',
        'product-label',
        'mini-label',
    ]);
}

/**
 * Max orders in one batch print request (DoS / memory guard).
 */
function factorchi_max_batch_orders(): int
{
    return max(1, min(200, (int) apply_filters('factorchi_max_batch_orders', 50)));
}

/**
 * Validate admin-configured outbound webhook URLs (SSRF guard).
 */
function factorchi_is_safe_remote_url(string $url): bool
{
    $url = trim($url);
    if ($url === '') {
        return false;
    }

    if (function_exists('wp_http_validate_url')) {
        $validated = wp_http_validate_url($url);
        if (!$validated) {
            return false;
        }
        $url = $validated;
    }

    $parts = wp_parse_url($url);
    if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
        return false;
    }

    $scheme = strtolower((string) $parts['scheme']);
    if (!in_array($scheme, ['http', 'https'], true)) {
        return false;
    }

    $host = strtolower((string) $parts['host']);
    if ($host === 'localhost' || str_ends_with($host, '.local') || str_ends_with($host, '.localhost')) {
        return false;
    }

    if (filter_var($host, FILTER_VALIDATE_IP)) {
        $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        if (!filter_var($host, FILTER_VALIDATE_IP, $flags)) {
            return false;
        }
    }

    return true;
}

/**
 * One blank line (Enter) below recipient address when the setting is enabled.
 */
function factorchi_address_enter_spacing_html(): string
{
    if (factorchi_get_setting('address_enter_spacing_below', 'no') !== 'yes') {
        return '';
    }

    return '<p class="fc-address-enter-spacer" aria-hidden="true"></p>';
}

/**
 * @param WC_Order|int $order
 * @param string       $type
 * @param string       $view
 * @param bool         $payment
 */
function factorchi_get_invoice_url($order, string $type = 'invoice', string $view = '', bool $payment = false): string
{
    $order_id = $order instanceof WC_Order ? $order->get_id() : (int) $order;

    if ($order_id <= 0) {
        return '';
    }

    $args = [
        'action'   => 'factorchi-show',
        'type'     => $type,
        'order-id' => $order_id,
    ];

    if ($view !== '') {
        $args['view'] = $view;
    }

    if ($payment) {
        $args['payment'] = '1';
    }

    if ($type === 'post-label') {
        $label_view = $view !== '' ? $view : (string) factorchi_get_setting('post_label_view', 'modern-a4');
        $args['print-size'] = Factorchi_Settings::post_label_size_from_view($label_view);
        if ($view === '') {
            $args['view'] = Factorchi_Settings::normalize_post_label_view($label_view);
        }
    }

    if ($type === 'mini-label') {
        $label_view = $view !== '' ? $view : (string) factorchi_get_setting('mini_label_view', '50x80');
        $args['print-size'] = Factorchi_Settings::mini_label_size_from_view($label_view);
        if ($view === '') {
            $args['view'] = Factorchi_Settings::normalize_mini_label_view($label_view);
        }
    }

    $token = Factorchi_Invoice_Router::generate_access_token($order_id);
    if ($token !== '') {
        $args['token'] = $token;
    }

    $url = add_query_arg($args, home_url('/'));

    return (string) apply_filters('factorchi_invoice_url', $url, $order, $type, $view);
}

/**
 * @param WC_Order|int $order
 */
function factorchi_get_invoice_url_for_order($order): string
{
    $type = (string) factorchi_get_setting('default_invoice_type', 'invoice');
    $view = (string) factorchi_get_setting('invoice_default_view', 'modern');

    return factorchi_get_invoice_url($order, $type, $view);
}

/** Backward compatibility for ported Factori templates. */
function get_fci_settings(string $key, $default = '')
{
    $normalized = str_replace('-', '_', $key);
    $value      = factorchi_get_setting($normalized, factorchi_get_setting($key, $default));

    $boolean_keys = [
        'tapin_status',
        'use_persian_number',
    ];

    if (in_array($normalized, $boolean_keys, true)) {
        return $value === 'yes' || $value === true || $value === '1' || $value === 1;
    }

    return $value;
}
