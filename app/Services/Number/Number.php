<?php

namespace App\Services\Number;


class Number
{
    function formatNumber($number) {
        if ($number >= 1e9) {
            return round($number / 1e9, 1) . 'B'; // Milliards
        } elseif ($number >= 1e6) {
            return round($number / 1e6, 1) . 'M'; // Millions
        } elseif ($number >= 1e3) {
            return round($number / 1e3, 1) . 'K'; // Milliers
        }

        return $number; // Retourne le nombre original s'il est inférieur à 1000
    }
}
