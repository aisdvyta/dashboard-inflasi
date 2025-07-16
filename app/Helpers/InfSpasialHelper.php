<?php

namespace App\Helpers;

class InfSpasialHelper
{
    /**
     * Menghasilkan class heatmap untuk cell tabel berdasarkan value.
     */
    public static function getHeatClass($value, $min, $max, $top)
    {
        $isPositive = $top > 0;
        if ($isPositive) {
            $percentage = $max - $min != 0 ? ($value - $min) / ($max - $min) : 0;
            if ($percentage >= 0.8) {
                return 'bg-biru2 text-white';
            } elseif ($percentage >= 0.6) {
                return 'bg-biru3 text-white';
            } elseif ($percentage >= 0.4) {
                return 'bg-biru4 text-white';
            } elseif ($percentage >= 0.2) {
                return 'bg-biru5';
            } else {
                return 'bg-white';
            }
        } else {
            $percentage = $min - $max != 0 ? ($value - $max) / ($min - $max) : 0;
            if ($percentage >= 0.8) {
                return 'bg-biru2 text-white';
            } elseif ($percentage >= 0.6) {
                return 'bg-biru3 text-white';
            } elseif ($percentage >= 0.4) {
                return 'bg-biru4 text-white';
            } elseif ($percentage >= 0.2) {
                return 'bg-biru5';
            } else {
                return 'bg-white';
            }
        }
    }

    /**
     * Menghasilkan class warna untuk inflasi/deflasi utama.
     */
    public static function getInfClass($value, $top)
    {
        $isPositive = $top > 0;
        if ($isPositive) {
            return 'bg-hijau';
        } else {
            return 'bg-merah1';
        }
    }
}
