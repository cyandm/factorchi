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
 * @param WC_Order|int $order
 * @param string       $type
 * @param string       $view
 * @param bool         $payment
 * @param bool         $compact
 */
function factorchi_get_invoice_url($order, string $type = 'invoice', string $view = '', bool $payment = false, bool $compact = false): string
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

    if ($compact && in_array($type, ['invoice', 'post-label'], true)) {
        $args['mode']        = 'compact';
        $args['print-size']  = (string) factorchi_get_setting('print_page_size', 'a4');
        $args['per-page']    = (string) factorchi_get_setting('print_per_page', '1');
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
    $view = (string) factorchi_get_setting('invoice_default_view', 'view-1');

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
        'page_break',
    ];

    if (in_array($normalized, $boolean_keys, true)) {
        return $value === 'yes' || $value === true || $value === '1' || $value === 1;
    }

    return $value;
}
