<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les calculs.
 */
class CalcUtils
{
    /**
     * Calcule le pourcentage d'une valeur.
     * 
     * @param int|float $value Valeur à calculer
     * @param int|float $total Total pour le calcul
     * @param int       $precision Nombre de chiffres après la virgule
     * @return float|null
     */
    public static function rate($value, $total, $precision = 2)
    {
        if (!$total) {
            return null;
        }
        return round($value * 100 / $total, $precision);
    }
}
