<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Font_Registry
{
    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'peyda'  => __('پیدا', 'factorchi'),
            'tahoma' => 'Tahoma',
        ];
    }

    public static function normalize_key(string $raw): string
    {
        $key = strtolower(trim($raw));
        $key = ltrim($key, '@');

        $options = self::options();
        if (isset($options[$key])) {
            return $key;
        }

        return 'peyda';
    }

    public static function resolve_css_family(string $raw): string
    {
        $key = self::normalize_key($raw);

        $map = [
            'peyda'  => 'Peyda',
            'tahoma' => 'Tahoma',
        ];

        return $map[$key] ?? 'Peyda';
    }

    public static function append_stylesheet_link(string $raw): string
    {
        $key = self::normalize_key($raw);

        $files = [
            'peyda' => 'fonts/peyda.css',
        ];

        if (!isset($files[$key])) {
            return '';
        }

        return '<link rel="stylesheet" href="'
            . esc_url(FACTORCHI_CSS_URL . $files[$key])
            . '?ver=' . esc_attr(FACTORCHI_VERSION) . '" />';
    }
}
