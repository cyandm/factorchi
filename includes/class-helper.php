<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Helper
{
    public static function received(int $order_id): bool
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            return false;
        }

        return $order->get_meta('_factorchi_received') === 'yes';
    }

    public static function mark_received(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if ($order) {
            $order->update_meta_data('_factorchi_received', 'yes');
            $order->save();
        }
    }

    public static function date_format(int $timestamp, bool $jalali = true): string
    {
        $with_time = factorchi_get_setting('show_date_time', 'yes') === 'yes';

        // Use DateTime directly — wp_date/date_i18n are often filtered to Jalali
        // by Persian calendar plugins, which would double-convert (e.g. 1404 → 0784).
        $dt = (new DateTimeImmutable('@' . $timestamp))->setTimezone(wp_timezone());

        if ($jalali && factorchi_get_setting('use_jalali_date', 'yes') === 'yes') {
            $gy = (int) $dt->format('Y');
            $gm = (int) $dt->format('n');
            $gd = (int) $dt->format('j');
            [$jy, $jm, $jd] = Factorchi_Date_Convert::gregorian_to_jalali($gy, $gm, $gd);
            $formatted = sprintf('%04d/%02d/%02d', $jy, $jm, $jd);
            if ($with_time) {
                $formatted .= ' ' . $dt->format('H:i');
            }

            return self::maybe_persian($formatted);
        }

        $formatted = $dt->format($with_time ? 'Y/m/d H:i' : 'Y/m/d');

        return self::maybe_persian($formatted);
    }

    public static function get_state_item(string $country, string $state): string
    {
        $states = WC()->countries->get_states($country);
        return $states[$state] ?? $state;
    }

    /**
     * @param string|float $amount
     */
    public static function format_price($amount): string
    {
        $html = wc_price((float) $amount);

        if (factorchi_get_setting('use_persian_number', 'yes') !== 'yes') {
            return $html;
        }

        $placeholders = [];
        $protected    = preg_replace_callback(
            '/&(?:#x?[0-9a-fA-F]+;|[^\s;]+;)/',
            static function (array $matches) use (&$placeholders): string {
                $key                  = self::entity_placeholder_key(count($placeholders));
                $placeholders[$key] = $matches[0];

                return $key;
            },
            $html
        );

        if (!is_string($protected)) {
            return $html;
        }

        $converted = Factorchi_Date_Convert::change_num($protected, 'fa');

        return str_replace(array_keys($placeholders), array_values($placeholders), $converted);
    }

    private static function entity_placeholder_key(int $index): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $suffix   = '';
        $n        = $index;

        do {
            $suffix = $alphabet[$n % 26] . $suffix;
            $n      = intdiv($n, 26) - 1;
        } while ($n >= 0);

        return '%%' . $suffix . '%%';
    }

    public static function maybe_persian(string $text): string
    {
        if (factorchi_get_setting('use_persian_number', 'yes') === 'yes') {
            return Factorchi_Date_Convert::change_num($text, 'fa');
        }

        return $text;
    }
}
