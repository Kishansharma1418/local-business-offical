<?php

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd-m-Y') {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->format($format);
    }
}   