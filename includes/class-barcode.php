<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Local Code128 barcode renderer (SVG) — no external API.
 */
class Factorchi_Barcode
{
    /**
     * Code128 bar/space patterns (11 modules each), index = code value.
     * Final entry is STOP (13 modules: 7 bars + 2 quiet terminator bits encoded as widths).
     *
     * @var array<int, string>
     */
    private static $patterns = [
        '11011001100',
        '11001101100',
        '11001100110',
        '10010011000',
        '10010001100',
        '10001001100',
        '10011001000',
        '10011000100',
        '10001100100',
        '11001001000',
        '11001000100',
        '11000100100',
        '10110011100',
        '10011011100',
        '10011001110',
        '10111001100',
        '10011101100',
        '10011100110',
        '11001110010',
        '11001011100',
        '11001001110',
        '11011100100',
        '11001110100',
        '11101101110',
        '11101001100',
        '11100101100',
        '11100100110',
        '11101100100',
        '11100110100',
        '11100110010',
        '11011011000',
        '11011000110',
        '11000110110',
        '10100011000',
        '10001011000',
        '10001000110',
        '10110001000',
        '10001101000',
        '10001100010',
        '11010001000',
        '11000101000',
        '11000100010',
        '10110111000',
        '10110001110',
        '10001101110',
        '10111011000',
        '10111000110',
        '10001110110',
        '11101110110',
        '11010001110',
        '11000101110',
        '11011101000',
        '11011100010',
        '11011101110',
        '11101011000',
        '11101000110',
        '11100010110',
        '11101101000',
        '11101100010',
        '11100011010',
        '11101111010',
        '11001000010',
        '11110001010',
        '10100110000',
        '10100001100',
        '10010110000',
        '10010000110',
        '10000101100',
        '10000100110',
        '10110010000',
        '10110000100',
        '10011010000',
        '10011000010',
        '10000110100',
        '10000110010',
        '11000010010',
        '11001010000',
        '11110111010',
        '11000010100',
        '10001111010',
        '10100111100',
        '10010111100',
        '10010011110',
        '10111100100',
        '10011110100',
        '10011110010',
        '11110100100',
        '11110010100',
        '11110010010',
        '11011011110',
        '11011110110',
        '11110110110',
        '10101111000',
        '10100011110',
        '10001011110',
        '10111101000',
        '10111100010',
        '11110101000',
        '11110100010',
        '10111011110',
        '10111101110',
        '11101011110',
        '11110101110',
        '11010000100',
        '11010010000',
        '11010011100',
        '11000111010',
    ];

    /**
     * Render a Code128 barcode as inline SVG HTML.
     */
    public static function svg(string $data, int $height = 80, int $module_width = 2): string
    {
        $data = trim($data);
        if ($data === '') {
            return '';
        }

        // Code128B supports printable ASCII; drop unsupported chars.
        $data = preg_replace('/[^\x20-\x7E]/', '', $data) ?? '';
        if ($data === '') {
            return '';
        }

        $codes = [104]; // Start Code B
        $len   = strlen($data);
        for ($i = 0; $i < $len; $i++) {
            $codes[] = ord($data[$i]) - 32;
        }

        $checksum = $codes[0];
        for ($i = 1, $n = count($codes); $i < $n; $i++) {
            $checksum += $codes[$i] * $i;
        }
        $codes[] = $checksum % 103;
        $codes[] = 106; // Stop

        $binary = '';
        foreach ($codes as $code) {
            $binary .= self::$patterns[$code];
        }
        $binary .= '11'; // termination bar

        $bars   = '';
        $x      = 0;
        $len_b  = strlen($binary);
        $black  = false;
        $run    = 0;

        for ($i = 0; $i <= $len_b; $i++) {
            $bit = $i < $len_b ? $binary[$i] : null;
            if ($bit === '1') {
                if (!$black) {
                    $black = true;
                    $run   = 1;
                } else {
                    $run++;
                }
            } else {
                if ($black) {
                    $w = $run * $module_width;
                    $bars .= '<rect x="' . $x . '" y="0" width="' . $w . '" height="' . (int) $height . '" fill="#000"/>';
                    $x    += $w;
                    $black = false;
                    $run   = 0;
                }
                if ($bit === '0') {
                    $x += $module_width;
                }
            }
        }

        $width = max($x, 1);
        $label = esc_html($data);

        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . (int) $width . '" height="' . (int) $height . '" viewBox="0 0 ' . (int) $width . ' ' . (int) $height . '" role="img" aria-label="' . $label . '">'
            . $bars
            . '</svg>';
    }

    /**
     * Wrapped barcode HTML matching existing template markup.
     */
    public static function html(string $data, int $height = 80): string
    {
        $svg = self::svg($data, $height);
        if ($svg === '') {
            return '';
        }

        return '<div class="barcode">'
            . $svg
            . '<span class="barcode-text">' . esc_html($data) . '</span>'
            . '</div>';
    }
}
