<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les tableaaux.
 */
class ArrayUtils
{
    /**
     * Cherche si une clé correspondant à une expression régulière existe dans un tableau.
     * 
     * @param string $pattern L'expression régulière à vérifier
     * @param array $array Le tableau dans lequel chercher la clé
     * @return string L'index de premier élément du tableau $array correspondant à la regex
     */
    public static function arrayKeyRegex($pattern, $array)
    {
        $keys = array_keys($array);
        $res = preg_grep($pattern, $keys);
        return $res[0];
    }

    /**
     * Convertit un tableau multi-dimensionnel en un tableau uni-dimensionnel.
     *
     * @param array[] $array
     * @param bool    $overwriteKeys Écrase les clés existantes ou renvoie un tableau séquentiel.
     * @return mixed[]|false
     */
    public static function flatten(array $array, $overwriteKeys = false)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = [];
        foreach ($array as $key => $value) {
            $arrayValue = [];
            if (is_array($value)) {
                $arrayValue = self::flatten($value, $overwriteKeys);
            } elseif ($overwriteKeys) {
                $arrayValue = [$key => $value];
            } else {
                $arrayValue = [$value];
            }
            $result = array_merge($result, $arrayValue);
        }
        return $result;
    }

    /**
     * Recherche une valeur par sa clé dans un tableau de tableaux associatifs et renvoie l'index ou `false`
     *
     * @param  array  $array Tableau de tableaux associatifs.
     * @param  string $key   Clé où rechercher `$value`.
     * @param  string $value Valeur à rechercher.
     * @return int|false Index du tableau où se touve la valeur ou `false`. 
     */
    public static function getIndexWhereKeyValue($array, $key, $value)
    {
        return array_search($value, array_column($array, $key));
    }

    /**
     * Groupe un tableau de tableaux associatifs par une clé donnée.
     * 
     * @param string $key  Clé avec laquelle trier le tableau.
     * @param array  $data Tableau de tableaux associatifs.
     */
    public static function groupBy($key, $data)
    {
        $result = [];
        foreach ($data as $val) {
            array_key_exists($key, $val)
                ? $result[$val[$key]][] = $val
                : $result[""][] = $val;
        }
        return $result;
    }

    /**
     * Trie les clés d'un tableau récursivement.
     *
     * @param array $array
     * @return array
     */
    public static function sortByKey($array)
    {
        ksort($array);
        foreach ($array as $item) {
            if (is_array($item)) {
                $item = self::sortByKey($item);
            }
        }
        return $array;
    }
}
