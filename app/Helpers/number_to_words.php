<?php

if (!function_exists('numberToIndianCurrency')) {
    function numberToIndianCurrency($number)
    {
        $isNegative = false;
        if ($number < 0) {
            $isNegative = true;
            $number = abs($number);
        }

        $no = floor($number);
        $point = round(($number - $no) * 100);

        $words = array(
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
            15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty',
            50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty',
            90 => 'Ninety'
        );

        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        $str = [];

        $i = 0;
        while ($no > 0) {
            $divider = ($i == 2) ? 10 : 100;
            $numberPart = $no % $divider;
            $no = floor($no / $divider);

            if ($numberPart) {
                if ($numberPart < 21) {
                    $word = $words[$numberPart];
                } else {
                    $tens = floor($numberPart / 10) * 10;
                    $units = $numberPart % 10;
                    $word = $words[$tens] . " " . ($units ? $words[$units] : "");
                }

                $str[] = $word . " " . $digits[count($str)];
            } else {
                $str[] = "";
            }

            $i += ($divider == 10) ? 1 : 2;
        }

        $result = trim(implode(" ", array_reverse($str)));

        $paiseWords = "";
        if ($point > 0) {
            $tens = floor($point / 10) * 10;
            $units = $point % 10;

            $paiseWords = " and Paise " .
                ($words[$tens] ?? "") . " " . ($words[$units] ?? "");
        }

        $final = ($isNegative ? "Minus " : "") . $result . " Rupees" . $paiseWords . " Only";

        return trim(preg_replace('/\s+/', ' ', $final));
    }
}
