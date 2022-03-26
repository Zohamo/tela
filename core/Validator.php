<?php

namespace Core;

use Core\Entities\PropertyValidationErrors;
use Core\Entities\ValidationRuleError;

/**
 * Validateur de données.
 * 
 * Cette classe permet de vérifier la conformité de données selon ses attributs.
 * 
 * @see: core/ValidatorRules.php
 */
class Validator
{
    use AttributesTrait;

    /**
     * Tableau associatif de données.
     * 
     * @example: [['id' => 23], ['nom' => "Roger"]]
     * 
     * @var mixed[]
     */
    protected $data;

    /**
     * Instance du modèle correspondant au données.
     * Permet de vérifier l'unicité d'une valeur.
     * 
     * @var object
     * @see: checkIfPropertyValueExists()
     */
    protected $model;

    /**
     * Alimente les données de l'objet à instancier.
     *
     * @param  mixed[] $data Tableau associatif des données avec leur valeur.
     * @param  array[] $attributes Tableau associatif des données avec leurs attributs.
     * @param  object  $model (facultatif) Instance du modèle correspondant au données.
     */
    public function __construct(array $data, array $attributes, $model = null)
    {
        $this->data = $data;
        $this->attributes = $attributes;
        $this->model = $model;
    }

    /**
     * Vérifie la conformité de chaque propriété par rapport à ses attributs.
     *
     * @param int $id Si un id est fourni l'entrée existe déjà en BDD.
     * @return PropertyValidationErrors[] Liste des erreurs.
     */
    public function validate($id = null)
    {
        $errors = []; // Erreurs à renvoyer
        $uniqueProperties = []; // Propriétés à valeur unique pour vérification en BDD

        list($errors, $uniqueProperties) = $this->validateValues($errors, $uniqueProperties);

        $errors = $this->addUndefinedBooleans()->checkMissingProperties($errors);

        if (!empty($uniqueProperties) && $this->model) {
            $errors = $this->checkUniqueProperties($id, $errors, $uniqueProperties);
        }

        return $errors;
    }

    /**
     * Vérifie la conformité de la valeur des propriétés.
     *
     * @param PropertyValidationErrors[] $errors Erreurs à renvoyer.
     * @param string[] $uniqueProperties Propriétés à valeur unique pour vérification en BDD.
     * @return array[] [$errors, $uniqueProperties]
     */
    public function validateValues($errors = [], $uniqueProperties = [])
    {
        foreach ($this->data as $name => $value) {
            if (empty($this->attributes[$name])) {
                continue;
            }
            $propErrors = $this->validateProperty($value, $this->attributes[$name]);
            if (!empty($propErrors)) {
                $errors[$name] = new PropertyValidationErrors($name, $value, get_class($this), $propErrors);
            }
            if (!empty($this->attributes[$name]['unique'])) {
                $uniqueProperties[$name] = $value;
            }
        }

        return [$errors, $uniqueProperties];
    }

    /**
     * Vérifie la conformité de la valeur de la propriété.
     *
     * @return ValidationRuleError[] Liste des erreurs.
     */
    public function validateProperty($propValue, $propAttributes)
    {
        // On commence par vérifier s'il y a une valeur et si elle est requise
        if ($propValue === null && !$propAttributes['required']) {
            return [];
        } elseif (!empty($propAttributes['required'])) {
            $res = \Core\ValidatorRules::required($propValue);
            if ($res !== true) {
                return ['required' => new ValidationRuleError(true, $res)];
            }
        }
        // Ensuite on vérifie les autres règles
        $errors = [];
        foreach ($propAttributes as $rule => $ruleValue) {
            if ($rule !== 'required' && $ruleValue !== false && method_exists('Core\ValidatorRules', $rule)) {
                $res = \Core\ValidatorRules::$rule($propValue, $ruleValue);
                if ($res !== true) {
                    $errors[$rule] = new ValidationRuleError($ruleValue, $res);
                }
            }
        }
        return $errors;
    }

    /**
     * Ajoute les propriétés de type "booléen" à la liste des propriétés.
     * 
     * Une checkbox non cochée ne renverra rien au lieu de sa valeur `false`.
     * 
     * @return $this
     */
    public function addUndefinedBooleans()
    {
        $boolNames = $this->attributes(['type' => 'boolean']);
        if (!empty($boolNames)) {
            foreach ($boolNames as $boolName) {
                if (!in_array($boolName, array_keys($this->data))) {
                    $this->data[$boolName] = false;
                }
            }
        }

        return $this;
    }

    /**
     * Vérifie si des propriétés requises sont manquantes.
     *
     * @param PropertyValidationErrors[] $errors
     * @return PropertyValidationErrors[] $errors
     */
    public function checkMissingProperties($errors = [])
    {
        foreach ($this->attributes as $propName => $attribute) {
            if (!in_array($propName, array_keys($this->data)) && !empty($attribute['required'])) {
                $errors[$propName] = new PropertyValidationErrors(
                    $propName,
                    null,
                    get_class($this),
                    ['required' => new ValidationRuleError(true, "est requis.")]
                );
            }
        }

        return $errors;
    }

    /**
     * Vérifie l'unicité des propriétés.
     *
     * @param int $id
     * @param PropertyValidationErrors[] $errors
     * @param mixed[] $uniqueProperties
     * @return PropertyValidationErrors[] $errors
     */
    public function checkUniqueProperties($id = null, $errors = [], $uniqueProperties = [])
    {
        foreach ($uniqueProperties as $propName => $propValue) {
            if ($this->checkIfPropertyValueExists($propName, $propValue, $id)) {
                $ValidationRuleError = new ValidationRuleError(true, "existe déjà et doit être unique.");
                if (isset($errors[$propName])) {
                    $errors[$propName]->addError('unique', $ValidationRuleError);
                } else {
                    $errors[$propName] = new PropertyValidationErrors(
                        $propName,
                        $propValue,
                        get_class($this),
                        ['unique' => $ValidationRuleError]
                    );
                }
            }
        }

        return $errors;
    }

    /**
     * Vérifie si la valeur de la propriété existe déjà.
     *
     * @param  string  $propName
     * @param  mixed   $propValue
     * @param  integer $id (facultatif) Si un id. est fourni, l'entrée existe déjà en BDD.
     * @return boolean
     */
    public function checkIfPropertyValueExists($propName, $propValue, $id = null)
    {
        if ($id === null) {
            // La clé primaire n'est pas renseignée:
            // on recherche une ligne avec la valeur de l'attribut
            return (bool) $this->model->first([$propName, $propValue]);
        }
        // La clé primaire est renseignée
        if (!is_array($id)) {
            // La clé primaire n'est pas composite:
            // on recherche une ligne avec la valeur de l'attribut qui ne ne corresponde pas à la valeur de la clé primaire
            return $this->model->pk() !== $propName && (bool) $this->model->first([[$this->model->pk(), '!=', $id], [$propName, $propValue]]);
        }
        // Les clés primaires sont composites:
        // on prépare les arguments WHERE de la requête
        $whereArgs = [];
        foreach ($this->model->pk() as $key) {
            $whereArgs[] = [$key, $this->model->$key];
        }
        $whereArgs[] = [$propName, $propValue];
        // on recherche une ligne avec la valeur de l'attribut qui ne ne corresponde pas aux valeurs des clés composites
        return (bool) $this->model->first($whereArgs);
    }
}
