<?php

class times
{
    /**
     * http://snipplr.com/view/24/month-day-year-smart-dropdowns/.
     *
     * @param type $mid
     * @param type $did
     * @param type $yid
     * @param type $mval
     * @param type $dval
     * @param type $yval
     *
     * @return string
     */
    public function mdy($mid = 'month', $did = 'day', $yid = 'year', $mval = '', $dval = '', $yval = '')
    {
        if (empty($mval)) {
            $mval = date('m');
        }
        if (empty($dval)) {
            $dval = date('d');
        }
        if (empty($yval)) {
            $yval = date('Y');
        }

        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $out = "<select name='$mid' id='$mid'>";
        foreach ($months as $val => $text) {
            if ($val == $mval) {
                $out .= "<option value='$val' selected>$text</option>";
            } else {
                $out .= "<option value='$val'>$text</option>";
            }
        }
        $out .= '</select> ';

        $out .= "<select name='$did' id='$did'>";
        for ($i = 1; $i <= 31; $i++) {
            if ($i == $dval) {
                $out .= "<option value='$i' selected>$i</option>";
            } else {
                $out .= "<option value='$i'>$i</option>";
            }
        }
        $out .= '</select> ';

        $out .= "<select name='$yid' id='$yid'>";
        for ($i = date('Y'); $i <= date('Y') + 2; $i++) {
            if ($i == $yval) {
                $out .= "<option value='$i' selected>$i</option>";
            } else {
                $out .= "<option value='$i'>$i</option>";
            }
        }
        $out .= '</select>';

        return $out;
    }

    /**
     * @param type $d
     *
     * @return type
     */
    public function count_days($d)
    {
        $now = time();
        $d = strtotime($d);
        $datediff = $now - $d;
        $conv = floor($datediff / (60 * 60 * 24));

        return $conv == 0 ? 0 : $conv * (-1);
    }

    public function find_days_in_month($month = 0, $year = '')
    {
        if ($month < 1 or $month > 12) {
            return 0;
        }

        if (!is_numeric($year) or strlen($year) != 4) {
            $year = date('Y');
        }

        if ($month == 2) {
            if ($year % 400 == 0 or ($year % 4 == 0 and $year % 100 != 0)) {
                return 29;
            }
        }

        $days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        return $days_in_month[$month - 1];
    }

    /**
     * https://abstractcodify.snipt.net/converting-timestamp-to-time-ago-in-php-eg-1-day-ago-2-days-ago/.
     *
     * @param type $datetime
     * @param type $use_s_suffix
     * @param type $full
     *
     * @return type
     */
    public function time_elapsed_string($datetime, $use_s_suffix = true/**/, $full = false)
    {
        // language strings
        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        $now = new DateTime();
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k.' '.$v.($diff->$k > 1 ? (($use_s_suffix) ? 's' : '') : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string).' ago' : 'just now';
    }
}
