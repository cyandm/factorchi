<?php

defined('ABSPATH') || exit;

class Factorchi_Date_Convert
{
    public static function change_num($str, string $mod = 'en', string $mf = '٫')
    {
        $number = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        $key    = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf];

        if ($mod === 'fa') {
            return str_replace($number, $key, (string) $str);
        }

        return str_replace($key, $number, (string) $str);
    }

    /**
     * @return array<int, int>|string
     */
    public static function jalali_to_gregorian($jy, $jm, $jd, string $mod = '')
    {
        [$jy, $jm, $jd] = array_map('intval', explode('_', self::change_num($jy . '_' . $jm . '_' . $jd)));

        if ($jy > 979) {
            $g_to_y = 1600;
            $jy -= 979;
        } else {
            $g_to_y = 621;
        }

        $days = (365 * $jy) + ((int) ($jy / 33) * 8) + ((int) ((($jy % 33) + 3) / 4)) + 78 + $jd
            + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);

        $g_to_y += 400 * ((int) ($days / 146097));
        $days %= 146097;

        if ($days > 36524) {
            $g_to_y += 100 * ((int) (--$days / 36524));
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }

        $g_to_y += 4 * ((int) ($days / 1461));
        $days %= 1461;
        $g_to_y += (int) (($days - 1) / 365);

        if ($days > 365) {
            $days = ($days - 1) % 365;
        }

        $gd = $days + 1;
        $gm = 0;

        foreach (
            [
                0,
                31,
                ((($g_to_y % 4 === 0) && ($g_to_y % 100 !== 0)) || ($g_to_y % 400 === 0)) ? 29 : 28,
                31, 30, 31, 30, 31, 31, 30, 31, 30, 31,
            ] as $month => $days_in_month
        ) {
            if ($gd <= $days_in_month) {
                $gm = $month;
                break;
            }
            $gd -= $days_in_month;
        }

        return ($mod === '') ? [$g_to_y, $gm, $gd] : $g_to_y . $mod . $gm . $mod . $gd;
    }

    /**
     * @return array{0:int,1:int,2:int}|string
     */
    public static function gregorian_to_jalali(int $gy, int $gm, int $gd, string $mod = '')
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        if ($gy > 1600) {
            $jy = 979;
            $gy -= 1600;
        } else {
            $jy = 0;
            $gy -= 621;
        }

        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = (365 * $gy) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100))
            + ((int) (($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
        $jy  += 33 * ((int) ($days / 12053));
        $days %= 12053;
        $jy  += 4 * ((int) ($days / 1461));
        $days %= 1461;

        if ($days > 365) {
            $jy   += (int) (($days - 1) / 365);
            $days  = ($days - 1) % 365;
        }

        $jm = ($days < 186) ? 1 + (int) ($days / 31) : 7 + (int) (($days - 186) / 30);
        $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));

        return ($mod === '') ? [$jy, $jm, $jd] : $jy . $mod . $jm . $mod . $jd;
    }
}
