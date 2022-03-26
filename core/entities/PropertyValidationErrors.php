<?php

namespace Core\Entities;

/**
 * Erreurs renvoyées par le Validator pour une propriété, avec:
 * - le nom et la valeur de la propriété
 * - un tableau d'erreurs sous la forme d'objets `\Core\Entities\ValidationRuleError`
 * 
 * La méthode validate() dont Model hérite renvoie un tableau d'objets
 * PropertyValidationErrors si des erreurs sont rencontrées lors de la
 * validation des propriétés.
 */
class PropertyValidationErrors
{
    /**
     * Alias du nom de la propriété.
     * 
     * @var string
     */
    public $alias;

    /**
     * Valeur de la propriété.
     *
     * @var mixed
     */
    public $value;

    /**
     * Erreurs renvoyées par les règles de validation.
     * Tableau associatif où la clé est le nom de la règle de validation.
     *
     * @var ValidationRuleError[]
     */
    public $errors;

    /**
     * Crée une instance de PropertyValidationErrors.
     *
     * @param string $propName  Nom de la propriété.
     * @param mixed  $value     Valeur de la propriété.
     * @param string $modelName Nom du modèle.
     * @param ValidationRuleError[] $errors
     */
    public function  __construct($propName, $value, $modelName = "", $errors = [])
    {
        $aliases = aliases($modelName);
        $this->alias = $aliases && isset($aliases[$propName]) ? $aliases[$propName] : $propName;
        $this->value = $value;
        $this->errors = $errors;
    }

    /**
     * Ajoute une erreur.
     *
     * @param string $ruleName
     * @param ValidationRuleError $ruleError
     * @return void
     */
    public function addError($ruleName, $ruleError)
    {
        $this->errors[$ruleName] = $ruleError;
    }
}
