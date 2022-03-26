<?php

namespace App\Functions;

class SqlUtils
{
    /**
     * Renvoie un type de valeur d'un type SQL.
     *
     * @param string  $value     'min' ou 'max'
     * @param string  $type      Type SQL
     * @param boolean $unsigned
     * @return int|false
     */
    public static function get_value($value, $type, $unsigned = false)
    {
        $values = include("assets/sql-values.php");
        $type = strtolower($type);
        $sign = $unsigned ? "unsigned" : "signed";
        if (!isset($values[$type]) || !in_array($value, ['min', 'max'])) {
            return false;
        }
        return $values[$type][$sign][$value];
    }

    /**
     * Renvoie la valeur maximale possible d'un type de nombre SQL.
     *
     * @param string  $type     Type SQL
     * @param boolean $unsigned
     * @return int|false
     */
    public static function max_value($type, $unsigned = false)
    {
        return self::get_value("max", $type, $unsigned);
    }

    /**
     * Renvoie la valeur minimale possible d'un type de nombre SQL.
     *
     * @param string  $type     Type SQL
     * @param boolean $unsigned
     * @return int|false
     */
    public static function min_value($type, $unsigned = false)
    {
        return self::get_value("min", $type, $unsigned);
    }

    /**
     * Renvoie les différents types numériques de SQL.
     *
     * @return string[]
     */
    public static function number_types()
    {
        return [
            'tinyint', 'smallint', 'mediumint', 'int', 'bigint',
            'float', 'double', 'decimal'
        ];
    }

    /**
     * Reçoit un type SQL et renvoie un type générique.
     *
     * @param  string $type
     * @return string
     */
    public static function type_generic($type)
    {
        switch ($type) {
            case "tinyint":
            case "smallint":
            case "int":
            case "bigint":
                $type = "integer";
                break;
            case "decimal":
            case "float":
            case "real":
            case "double":
                $type = "float";
                break;
            case "datetime":
            case "date":
                $type = "date";
                break;
            case "char":
            case "varchar":
            case "tinytext":
            case "text":
            case "mediumtext":
            case "longtext":
            default:
                $type = "string";
        }

        return $type;
    }
}
