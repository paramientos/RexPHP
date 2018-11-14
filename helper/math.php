<?php

class math
{
    public function is_odd_even($number)
    {
        return ($number & 1) ? 'odd' : 'even';
    }

    /**
     * The function (Mean, Median, Mode, Range) will calculate the Mean, Median, Mode, or Range of an array.
     * The function automatically defaults to Mean (average).
     *
     * @param type $array
     * @param type $output
     *
     * @return boolean
     */
    public function sta($array, $output = 'mean')
    {
        if (!is_array($array)) {
            return false;
        } else {
            switch ($output) {
                case 'mean':
                    $count = count($array);
                    $sum = array_sum($array);
                    $total = $sum / $count;
                    break;
                case 'median':
                    rsort($array);
                    $middle = round(count($array) / 2);
                    $total = $array[$middle - 1];
                    break;
                case 'mode':
                    $v = array_count_values($array);
                    arsort($v);
                    foreach ($v as $k => $v) {
                        $total = $k;
                        break;
                    }
                    break;
                case 'range':
                    sort($array);
                    $sml = $array[0];
                    rsort($array);
                    $lrg = $array[0];
                    $total = $lrg - $sml;
                    break;
            }

            return $total;
        }
    }
}
