<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Customer_Data
{
    private int $order_id;
    private string $type;
    /** @var WC_Order|null */
    private $order;

    public function __construct($order_id, string $type = 'invoice')
    {
        $this->order_id = is_array($order_id) ? (int) reset($order_id) : (int) $order_id;
        $this->type     = $type;
        $this->order    = $this->order_id > 0 ? wc_get_order($this->order_id) : null;
    }

    private function format_plain_address(string $formatted_html): string
    {
        $plain = wp_strip_all_tags(str_replace(['<br/>', '<br />', '<br>'], ' - ', $formatted_html));
        $plain = preg_replace('/\s*-\s*-\s*/', ' - ', $plain) ?? $plain;
        $plain = trim(preg_replace('/\s+/', ' ', $plain) ?? $plain);

        return $plain;
    }

    /**
     * Remove customer name from address text (name is shown separately).
     */
    private function strip_name_from_address(string $formatted_html): string
    {
        $name = $this->get_full_name();
        if ($name === '' || $formatted_html === '') {
            return $this->format_plain_address($formatted_html);
        }

        $lines = preg_split('/<br\s*\/?>/i', $formatted_html) ?: [$formatted_html];
        $lines = array_values(array_filter(array_map(static function ($line) {
            return trim(wp_strip_all_tags($line));
        }, $lines)));

        if (isset($lines[0]) && $lines[0] === $name) {
            array_shift($lines);
        }

        $plain = implode(' - ', $lines);
        $plain = preg_replace('/\s*-\s*-\s*/', ' - ', $plain) ?? $plain;
        $plain = trim(preg_replace('/\s+/', ' ', $plain) ?? $plain);

        // Fallback if WC joins name without a line break.
        $quoted = preg_quote($name, '/');
        $plain  = preg_replace('/^' . $quoted . '\s*[-–,]\s*/u', '', $plain) ?? $plain;
        $plain  = preg_replace('/^' . $quoted . '\s+/u', '', $plain) ?? $plain;

        return trim($plain);
    }

    private function append_plaque_unit_if_missing(string $address, string $prefix): string
    {
        if (!$this->order || $address === '') {
            return $address;
        }

        $plaque = trim((string) $this->order->get_meta($prefix . 'plaque'));
        $unit   = trim((string) $this->order->get_meta($prefix . 'unit'));

        if ($plaque !== '' && !preg_match('/پلاک\s*[:：]?\s*' . preg_quote($plaque, '/') . '/u', $address)) {
            $address .= ($address !== '' ? ' - ' : '') . __('پلاک', 'factorchi') . ': ' . $plaque;
        }

        if ($unit !== '' && !preg_match('/واحد\s*[:：]?\s*' . preg_quote($unit, '/') . '/u', $address)) {
            $address .= ($address !== '' ? ' - ' : '') . __('واحد', 'factorchi') . ': ' . $unit;
        }

        return $address;
    }

    private function field_line(string $label, string $value, bool $html, string $class = ''): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (!$html) {
            return $label !== '' ? $label . ' ' . $value : $value;
        }

        $class_attr = $class !== '' ? ' class="' . esc_attr($class) . '"' : '';

        if ($label === '') {
            return '<p' . $class_attr . '>' . esc_html($value) . '</p>';
        }

        return '<p' . $class_attr . '><strong>' . esc_html($label) . '</strong> ' . esc_html($value) . '</p>';
    }

    public function address_holder(bool $html = false): string
    {
        return $this->field_line(__('آدرس:', 'factorchi'), $this->get_address(), $html, 'customer-address');
    }

    public function get_address(): string
    {
        if (!$this->order) {
            return '';
        }

        $formatted = $this->order->get_formatted_shipping_address();
        $prefix    = '_shipping_';

        if ($formatted === '') {
            $formatted = $this->order->get_formatted_billing_address();
            $prefix    = '_billing_';
        }

        $plain = $this->strip_name_from_address($formatted);

        return $this->append_plaque_unit_if_missing($plain, $prefix);
    }

    public function full_name_holder(bool $html = false): string
    {
        return $this->field_line(__('نام:', 'factorchi'), $this->get_full_name(), $html, 'customer-name');
    }

    public function get_full_name(): string
    {
        if (!$this->order) {
            return '';
        }

        $name = trim($this->order->get_formatted_shipping_full_name());
        if ($name === '') {
            $name = trim($this->order->get_formatted_billing_full_name());
        }

        return $name;
    }

    public function postal_code_holder(bool $html = false): string
    {
        return $this->field_line(__('کدپستی:', 'factorchi'), $this->get_postal_code(), $html, 'customer-postcode');
    }

    public function get_postal_code(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) ($this->order->get_shipping_postcode() ?: $this->order->get_billing_postcode());
    }

    public function phone_holder(bool $html = false): string
    {
        return $this->field_line(__('تلفن:', 'factorchi'), $this->get_phone(), $html, 'customer-phone');
    }

    public function get_phone(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_billing_phone();
    }

    public function email_holder(bool $html = false): string
    {
        return '';
    }

    public function get_email(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_billing_email();
    }

    public function order_date_holder(bool $html = false): string
    {
        if (factorchi_get_setting('show_order_date', 'yes') !== 'yes') {
            return '';
        }

        return $this->field_line(__('تاریخ سفارش:', 'factorchi'), $this->get_order_date(), $html, 'order-date');
    }

    public function get_order_date(): string
    {
        if (!$this->order) {
            return '';
        }

        $date = $this->order->get_date_created();
        if (!$date) {
            return '';
        }

        return Factorchi_Helper::date_format($date->getTimestamp());
    }

    public function payment_method_holder(bool $html = false): string
    {
        if (factorchi_get_setting('show_payment_method', 'yes') !== 'yes') {
            return '';
        }

        return $this->field_line(__('روش پرداخت:', 'factorchi'), $this->get_payment_method(), $html, 'pay-method');
    }

    public function get_payment_method(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_payment_method_title();
    }

    public function transaction_id_holder(bool $html = false): string
    {
        if (factorchi_get_setting('show_transaction_id', 'yes') !== 'yes') {
            return '';
        }

        return $this->field_line(__('شماره تراکنش:', 'factorchi'), $this->get_transaction_id(), $html, 'trans-id');
    }

    public function get_transaction_id(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_transaction_id();
    }

    /**
     * Gateway tracking code returned by the payment provider (e.g. PayZito "کدپیگیری درگاه").
     */
    public function get_gateway_tracking_code(): string
    {
        if (!$this->order) {
            return '';
        }

        $code = $this->resolve_gateway_tracking_code();

        return (string) apply_filters('factorchi_gateway_tracking_code', $code, $this->order, $this->order_id);
    }

    private function resolve_gateway_tracking_code(): string
    {
        if ($this->should_use_payzito_gateway_tracking() && $this->is_payzito_installed()) {
            return $this->resolve_payzito_gateway_tracking_code();
        }

        return $this->resolve_woocommerce_gateway_tracking_code();
    }

    private function should_use_payzito_gateway_tracking(): bool
    {
        return factorchi_get_setting('use_payzito_gateway_tracking', 'no') === 'yes';
    }

    private function is_payzito_installed(): bool
    {
        static $active = null;

        if ($active !== null) {
            return $active;
        }

        if (defined('PAYZITO_VERSION') || class_exists('Payzito', false)) {
            $active = true;

            return $active;
        }

        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        foreach (['payzito/payzito.php', 'payzito-pro/payzito.php'] as $plugin) {
            if (is_plugin_active($plugin)) {
                $active = true;

                return $active;
            }
        }

        global $wpdb;
        static $table_exists = null;

        if ($table_exists === null) {
            $table_exists = $this->get_payzito_transactions_table() !== '';
        }

        $active = $table_exists;

        return $active;
    }

    private function resolve_payzito_gateway_tracking_code(): string
    {
        $from_payzito = $this->get_payzito_gateway_ref();
        if ($from_payzito !== '') {
            return $from_payzito;
        }

        foreach ($this->get_payzito_gateway_tracking_meta_keys() as $meta_key) {
            $value = trim((string) $this->order->get_meta($meta_key));
            if ($value !== '' && !$this->looks_like_payzito_invoice($value)) {
                return $value;
            }
        }

        $from_notes = $this->parse_gateway_ref_from_notes();
        if ($from_notes !== '') {
            return $from_notes;
        }

        $transaction_id = trim((string) $this->order->get_transaction_id());
        if ($transaction_id !== '' && !$this->looks_like_payzito_invoice($transaction_id)) {
            return $transaction_id;
        }

        return '';
    }

    private function resolve_woocommerce_gateway_tracking_code(): string
    {
        $transaction_id = trim((string) $this->order->get_transaction_id());
        if ($transaction_id !== '') {
            return $transaction_id;
        }

        foreach ($this->get_woocommerce_gateway_tracking_meta_keys() as $meta_key) {
            $value = trim((string) $this->order->get_meta($meta_key));
            if ($value !== '') {
                return $value;
            }
        }

        foreach ($this->order->get_meta_data() as $meta) {
            $key = strtolower((string) $meta->key);
            if (
                (str_contains($key, 'gateway') && (str_contains($key, 'ref') || str_contains($key, 'track')))
                || str_contains($key, 'ref_num')
                || str_contains($key, 'refnum')
                || str_contains($key, 'transaction')
                || $key === '_rrn'
            ) {
                $value = trim((string) $meta->value);
                if ($value !== '') {
                    return $value;
                }
            }
        }

        return $this->parse_gateway_ref_from_notes();
    }

    /**
     * @return string[]
     */
    private function get_payzito_gateway_tracking_meta_keys(): array
    {
        return [
            '_payzito_gateway_ref_num',
            '_payzito_gateway_ref',
            '_payzito_ref_num',
            'payzito_gateway_ref_num',
        ];
    }

    /**
     * @return string[]
     */
    private function get_woocommerce_gateway_tracking_meta_keys(): array
    {
        return [
            '_gateway_ref_num',
            '_gateway_tracking_code',
            'gateway_ref_num',
            '_ref_num',
            '_RRN',
            'RefNum',
            '_payment_reference',
            '_transaction_id',
        ];
    }

    private function looks_like_payzito_invoice(string $value): bool
    {
        return (bool) preg_match('/^[A-Z]{1,5}-\d+$/u', $value);
    }

    private function get_payzito_gateway_ref(): string
    {
        global $wpdb;

        $table = $this->get_payzito_transactions_table();
        if ($table === '') {
            return '';
        }

        $order_id = (string) $this->order->get_id();
        $row      = $this->get_payzito_transaction_row($table, $order_id);
        if ($row === null) {
            return '';
        }

        return $this->extract_gateway_ref_from_payzito_row($row, $order_id);
    }

    private function get_payzito_transactions_table(): string
    {
        global $wpdb;

        static $table = null;
        if ($table !== null) {
            return $table;
        }

        foreach (
            [
                $wpdb->prefix . 'payzito_transactions',
                $wpdb->prefix . 'payzito_transctions',
            ] as $candidate
        ) {
            if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $candidate)) === $candidate) {
                $table = $candidate;

                return $table;
            }
        }

        $found = $wpdb->get_col("SHOW TABLES LIKE '%payzito%trans%'");
        $table = !empty($found[0]) ? (string) $found[0] : '';

        return $table;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function get_payzito_transaction_row(string $table, string $order_id): ?array
    {
        global $wpdb;

        $columns = $wpdb->get_col("SHOW COLUMNS FROM `{$table}`");
        if (!is_array($columns) || $columns === []) {
            return null;
        }

        $columns = array_map('strval', $columns);
        $like    = '%' . $wpdb->esc_like($order_id) . '%';

        $exact_candidates = [
            'order_id',
            'app_order_id',
            'extension_id',
            'extension_order_id',
            'wc_order_id',
            'woocommerce_order_id',
            'factor_id',
            'ref_id',
        ];

        foreach ($exact_candidates as $column) {
            if (!in_array($column, $columns, true)) {
                continue;
            }

            $row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM `{$table}` WHERE `{$column}` = %s ORDER BY id DESC LIMIT 1",
                    $order_id
                ),
                ARRAY_A
            );
            if (is_array($row)) {
                return $row;
            }
        }

        foreach (['extension_data', 'params', 'data', 'logs', 'log', 'meta'] as $column) {
            if (!in_array($column, $columns, true)) {
                continue;
            }

            $row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM `{$table}` WHERE `{$column}` LIKE %s ORDER BY id DESC LIMIT 1",
                    $like
                ),
                ARRAY_A
            );
            if (is_array($row)) {
                return $row;
            }
        }

        // Last resort: any column equals order id.
        foreach ($columns as $column) {
            if (in_array($column, ['id', 'amount', 'price', 'paid', 'total'], true)) {
                continue;
            }

            $row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM `{$table}` WHERE `{$column}` = %s ORDER BY id DESC LIMIT 1",
                    $order_id
                ),
                ARRAY_A
            );
            if (is_array($row)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function extract_gateway_ref_from_payzito_row(array $row, string $order_id): string
    {
        $preferred = [
            'gateway_ref_num',
            'gateway_ref',
            'gateway_tracking',
            'gateway_track',
            'ref_num',
            'refnum',
            'reference_number',
            'rrn',
            'RRN',
            'track_id',
            'tracking_code',
            'authority',
        ];

        foreach ($preferred as $key) {
            if (!array_key_exists($key, $row)) {
                continue;
            }
            $value = $this->normalize_payzito_gateway_value((string) $row[$key], $order_id);
            if ($value !== '') {
                return $value;
            }
        }

        // Parse JSON/text blobs (logs / extension_data) for "کدپیگیری درگاه: …"
        foreach ($row as $value) {
            if (!is_string($value) || $value === '') {
                continue;
            }
            if (!str_contains($value, 'کدپیگیری') && !str_contains($value, 'کد پیگیری')) {
                continue;
            }
            if (preg_match('/کد\s*پیگیری\s*درگاه\s*[:：]\s*([0-9A-Za-z\-]+)/u', $value, $matches)) {
                $normalized = $this->normalize_payzito_gateway_value($matches[1], $order_id);
                if ($normalized !== '') {
                    return $normalized;
                }
            }
        }

        // Fallback: first scalar that looks like a gateway tracking code.
        foreach ($row as $key => $value) {
            $key_l = strtolower((string) $key);
            if (
                str_contains($key_l, 'factor')
                || str_contains($key_l, 'invoice')
                || str_contains($key_l, 'mobile')
                || str_contains($key_l, 'phone')
                || str_contains($key_l, 'amount')
                || str_contains($key_l, 'price')
                || str_contains($key_l, 'order')
                || $key_l === 'id'
            ) {
                continue;
            }

            if (!is_scalar($value)) {
                continue;
            }

            $normalized = $this->normalize_payzito_gateway_value((string) $value, $order_id);
            if ($normalized !== '' && preg_match('/^\d{6,}$/', $normalized)) {
                return $normalized;
            }
        }

        return '';
    }

    private function normalize_payzito_gateway_value(string $value, string $order_id): string
    {
        $value = trim($value);
        if ($value === '' || $value === '0' || $value === $order_id) {
            return '';
        }

        if ($this->looks_like_payzito_invoice($value)) {
            return '';
        }

        // Mobile numbers (e.g. 09100257904).
        if (preg_match('/^09\d{9}$/', $value)) {
            return '';
        }

        // Keep only leading tracking token if Persian text was glued on.
        if (preg_match('/^([0-9A-Za-z\-]+)/u', $value, $matches)) {
            $value = $matches[1];
        }

        if ($value === '' || $value === '0' || $value === $order_id || $this->looks_like_payzito_invoice($value)) {
            return '';
        }

        return $value;
    }

    private function parse_gateway_ref_from_notes(): string
    {
        $notes = wc_get_order_notes([
            'order_id' => $this->order->get_id(),
            'limit'    => 20,
        ]);

        // Digits/latin only — after wp_strip_all_tags, PayZito lines can glue as "248101322موبایل".
        $patterns = [
            '/کد\s*پیگیری\s*درگاه\s*[:：]\s*([0-9A-Za-z\-]+)/u',
            '/کدپیگیری\s*درگاه\s*[:：]\s*([0-9A-Za-z\-]+)/u',
        ];

        foreach ($notes as $note) {
            if (!$note instanceof stdClass || !isset($note->content)) {
                continue;
            }

            $content = wp_strip_all_tags((string) $note->content);
            $content = preg_replace('/\s+/u', ' ', $content) ?? $content;

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content, $matches)) {
                    return trim($matches[1]);
                }
            }
        }

        return '';
    }

    public function national_id_holder(bool $html = false): string
    {
        return $this->field_line(__('کد ملی:', 'factorchi'), $this->get_national_id(), $html, 'national-id');
    }

    public function get_national_id(): string
    {
        if (!$this->order) {
            return '';
        }

        $id = (string) $this->order->get_meta('_billing_national_id');
        if ($id === '') {
            $id = (string) $this->order->get_meta('billing_national_id');
        }

        return $id;
    }

    public function shipping_method_holder(bool $html = false): string
    {
        if (factorchi_get_setting('show_shipping_method', 'yes') !== 'yes') {
            return '';
        }

        return $this->field_line(__('روش ارسال:', 'factorchi'), $this->get_shipping_method(), $html, 'shipping-method');
    }

    public function customer_note_line_holder(bool $html = false): string
    {
        if (factorchi_get_setting('show_customer_note_buyer', 'yes') !== 'yes') {
            return '';
        }

        return $this->field_line(__('یادداشت:', 'factorchi'), $this->get_customer_note(), $html, 'customer-note-line');
    }

    public function get_customer_note(): string
    {
        if (!$this->order) {
            return '';
        }

        return trim((string) $this->order->get_customer_note());
    }

    public function get_shipping_method(): string
    {
        if (!$this->order) {
            return '';
        }

        return (string) $this->order->get_shipping_method();
    }

    public function user_meta_holder(): string
    {
        return '';
    }

    public function order_meta_holder(): string
    {
        return '';
    }

    public function delivery_date_holder(): string
    {
        return '';
    }
}
