<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les dates.
 */
class DateUtils
{
    /**
     * Renvoie une date au format anglais au format français.
     *
     * @param  string $date
     * @return string
     */
    public static function dateEnToFr($date)
    {
        if ($date) {
            return date(\AppConstants::FORMAT_DATE_DISPLAY, strtotime($date));
        }
        return null;
    }

    /**
     * Renvoie une date au format français au format anglais.
     *
     * @param  string $date
     * @return string
     */
    public static function dateFrToEn($date)
    {
        list($day, $month, $year) = explode("/", $date);
        return "$year-$month-$day";
    }

    /**
     * Convertit un format de date en un autre.
     * 
     * @param  string $date   Date à convertir.
     * @param  string $before Format de date de la source.
     * @param  string $after  Format de date à renvoyer.
     * @return string Date convertie
     */
    public static function convert($date, $before, $after)
    {
        return is_string($date) ? \DateTime::createFromFormat($before, $date)->format($after) : $date;
    }

    /**
     * Convertit un format de date au format SQL.
     * 
     * @param  string $date   Date à convertir
     * @param  string $before Format de date de la source
     * @return string
     */
    public static function toSQL($date, $before = '')
    {
        if (!$before) {
            $before = \AppConstants::FORMAT_DATE_DISPLAY;
        }
        return self::convert($date, $before, 'Y-m-d H:i:s');
    }

    /**
     * Convertit un format de date pour l'affichage.
     * 
     * @param  string $date   Date à convertir
     * @param  string $before Format de date de la source
     * @return string
     */
    public static function toHTML($date, $before = 'Y-m-d H:i:s')
    {
        return self::convert($date, $before, \AppConstants::FORMAT_DATE_DISPLAY);
    }
}
