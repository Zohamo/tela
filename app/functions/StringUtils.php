<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les chaînes de caractères.
 */
class StringUtils
{
    /**
     * Transforme une chaîne de caractères en camelCase.
     *
     * @param  string $str
     * @param  string $delimiter Caractère utilisé pour séparer les mots de la source.
     * @return string
     */
    public static function camel($str, $delimiter = " ")
    {
        return lcfirst(self::pascal($str, $delimiter));
    }

    /**
     * Ajoute des espaces à une chaîne de caractères en camelCase ou PascalCase et la met en minuscules.
     *
     * @example: "fooBar" devient "foo bar"
     * @param  string $str
     * @return string
     */
    public static function camelToSpaced($str)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $str, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode(' ', $ret);
    }

    /**
     * Nettoie une chaîne de caractères passée dans un champ de recherche.
     *
     * @param  string $str
     * @return string
     */
    public static function cleanSearchString($str)
    {
        return htmlspecialchars(strtolower(self::reduceSpaces(trim($str))));
    }

    /**
     * Transforme une chaîne de caractères en kebab-case.
     *
     * @param  string $str
     * @param  string $delimiter Caractère utilisé pour séparer les mots de la source.
     * @return string
     */
    public static function kebab($str, $delimiter = " ")
    {
        return str_replace($delimiter, "-", self::cleanSearchString($str));
    }

    /**
     * Transforme une chaîne de caractères en PascalCase.
     *
     * @param  string $str
     * @param  string $delimiter Caractère utilisé pour séparer les mots de la source.
     * @return string
     */
    public static function pascal($str, $delimiter = " ")
    {
        return implode("", array_map(function ($word) {
            return ucfirst($word);
        }, explode($delimiter, self::cleanSearchString($str))));
    }

    /**
     * Prettify une requête SQL
     *
     * @param  string $query
     * @return string
     */
    public static function prettifySqlQuery($query)
    {
        // remplace les retours par des espaces
        $query = preg_replace("/\r|\n/", " ", $query);
        // supprime les espaces en trop
        $query = self::reduceSpaces(trim($query));
        return str_replace(" ,", ",", $query);
    }

    /**
     * Convertit plusieurs espaces en un seul dans une chaîne de caractères.
     *
     * @param  string $str
     * @return string
     */
    public static function reduceSpaces($str)
    {
        while (strpos($str, '  ') !== false) {
            $str = str_replace("  ", " ", $str);
        }
        return $str;
    }

    /**
     * Renvoie une chaîne sans caractères spéciaux
     * 
     * @param string $text Texte à remplacer
     * @return string
     */
    public static function replaceSpecialChars($text)
    {
        $utf8 = include("assets/special-characters.php");
        return str_replace(array_keys($utf8), array_values($utf8), $text);
    }

    /**
     * Transforme une chaîne de caractères en snake_case.
     *
     * @param  string $str
     * @param  string $delimiter Caractère utilisé pour séparer les mots de la source.
     * @return string
     */
    public static function snake($str, $delimiter = " ")
    {
        return str_replace($delimiter, "_", self::cleanSearchString($str));
    }

    /**
     * Change le formatage d'une chaîne de caractères.
     * Options :
     * - kebab-case ('k', 'kebab')
     * - PascalCase ('p', 'pascal')
     * - camelCase ('c', 'camel')
     *
     * @param  string $string
     * @param  string $case
     * @param  string $delimiter Caractère utilisé pour séparer les mots de la source.
     * @return string
     */
    public static function switchCase($str, $case = null, $delimiter = " ")
    {
        switch ($case) {
            case 'k':
            case 'kebab';
                $str = self::kebab($str, $delimiter);
                break;
            case 'p':
            case 'pascal':
                $str = self::pascal($str, $delimiter);
                break;
            case 'c':
            case 'camel':
                $str = self::camel($str, $delimiter);
                break;
            case '':
            default:
        }
        return $str;
    }
}
