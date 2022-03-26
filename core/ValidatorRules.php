<?php

namespace Core;

/**
 * Règles de validation.
 * 
 * Ces règles peuvent être attribuées aux propriétés d'un modèle dans 'app/models/properties'.
 * Pour les appliquer, le modèle doit posséder le trait `ModelPropertiesValidatorTrait`,
 * et en exécuter la méthode `validate` qui renvoie un tableau d'erreurs.
 * Si ce tableau est vide cela signifie que les données respectent les règles définies.
 * 
 * Chaque règle est une méthode statique qui prend en paramètre:
 * - $value (`mixed`) : La valeur de l'attribut
 * - $ruleValue (`mixed`, optionnel) : La valeur de la règle
 * et qui renvoie:
 * - soit `true` si la valeur fournie est valide
 * - soit un message d'erreur
 */
class ValidatorRules
{
    public static function type($value, $ruleValue)
    {
        $valid = false;
        switch ($ruleValue) {
            case 'int':
            case 'integer':
                $valid = is_integer($value);
                break;
            case 'real':
            case 'float':
            case 'double':
                $valid = is_float($value);
                break;
            case 'string':
            case 'char':
            case 'varchar':
            case 'date':
                $valid = is_string($value);
                break;
            case 'bool':
            case 'boolean':
                $valid = is_bool($value);
                break;
            case 'object':
                $valid = is_object($value);
                break;
            case 'array':
                $valid = is_array($value);
                break;
            default:
                throw new \DomainException("Type d'attribut inconnu: $ruleValue");
        }
        return $valid ?: "doit être de type: $ruleValue";
    }

    public static function required($value)
    {
        return !($value === null || (is_array($value) && empty($value)) || (is_string($value) && trim($value) === ''))
            ?: "est requis.";
    }

    /***************************************************************************
     * VALEUR
     ***************************************************************************/

    public static function min($value, $ruleValue)
    {
        return $value >= $ruleValue
            ?: "doit être supérieur ou égal à $ruleValue.";
    }

    public static function max($value, $ruleValue)
    {
        return $value <= $ruleValue
            ?: "doit être inférieur ou égal à $ruleValue.";
    }

    public static function in($value, $array)
    {
        return $array === null || in_array($value, $array)
            ?: "doit correspondre à une des valeurs suivantes: " . implode(", ", $array);
    }

    /***************************************************************************
     * TAILLE
     ***************************************************************************/

    public static function min_length($value, $ruleValue)
    {
        return strlen((string) $value) >= $ruleValue
            ?: "doit avoir au minimum $ruleValue caractères.";
    }

    public static function length($value, $ruleValue)
    {
        return strlen((string) $value) == $ruleValue
            ?: "doit avoir exactement $ruleValue caractères.";
    }

    public static function max_length($value, $ruleValue)
    {
        return strlen((string) $value) <= $ruleValue
            ?: "doit avoir au maximum $ruleValue caractères.";
    }

    /***************************************************************************
     * REGEX
     ***************************************************************************/

    public static function email($value)
    {
        return (bool) filter_var((string) $value, FILTER_VALIDATE_EMAIL)
            ?: "n'est pas une adresse courriel valide.";
    }

    public static function url($value)
    {
        return (bool) filter_var((string) $value, FILTER_VALIDATE_URL)
            ?: "n'est pas une adresse URL valide.";
    }

    public static function phone($value)
    {
        return self::regex((string) $value, "^(\+ ?([0-9]+))?(\ ?(\(([0-9]+)\))?([0-9]+)?)+$")
            ?: "n'est pas un numéro de téléphone valide.";
    }

    public static function regex($value, $regex)
    {
        if (!is_string($regex)) {
            throw new \InvalidArgumentException("L'expression régulière doit être une chaîne de caractères");
        }

        return (bool) filter_var((string) $value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex]])
            ?: "ne respecte pas l'expression régulière: $regex";
    }
}
