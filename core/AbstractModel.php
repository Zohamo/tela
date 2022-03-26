<?php

namespace Core;

use Core\QueryBuilder;

/**
 * Modèle de base, permet d'effectuer des requêtes SQL avec le QueryBuilder.
 * 
 * Les modèles se situent dans "app/models".
 * 
 * Il est nécessaire de définir le nom de la table (`$table`) de la BDD ainsi que
 * sa ou ses clé(s) primaire(s) (`$pk`).
 * 
 * Les requêtes de type SELECT renvoient des objets "entité", par défaut du nom du
 * modèle sans "Model" (ex: UtilisateurModel (modèle) -> Utilisateur (entité)).
 * Pour définir une entité différente il faut définir `$entity`.
 * Les entités sont définies dans "app/entities".
 * 
 * Le modèle contient également un assainisseur de données avec la méthode `sanitize()`,
 * ainsi qu'un validateur de données avec la méthode `validate()`.
 * 
 * @see: app/models
 */
abstract class AbstractModel extends QueryBuilder
{
    use PropertiesTrait;

    /**
     * Nom de la table du modèle.
     * 
     * @var string ex: `t_utilisateur`
     */
    protected $table = '';

    /**
     * Nom de la clé primaire de la table ou des clés composites.
     * 
     * @var string|string[] ex: `uti_id` ou [`fk_uti_id`, `fk_droit_id`]
     */
    protected $pk = '';

    /**
     * Nom de l'entité relative au modèle.
     * 
     * @example: "Utilisateur"
     * @var string
     */
    protected $entity = "";

    /**
     * Définit les propriétés du modèle et ses relations.
     * 
     * @param  mixed[] $data
     * @param  string  $dbKey Indice de la configuration de BDD à utiliser (ex: "DB_$dbKey_HOST").
     * @return void
     */
    function __construct(array $data = [], $dbKey = "")
    {
        parent::__construct($dbKey);
        // Si le nom de l'entité n'est pas défini, on récupère celui du modèle duquel on supprime "Model"
        if (!$this->entity) {
            $this->entity = preg_replace('/Model$/', '', \App\Functions\ClassUtils::getName($this, false));
        }
        $this->setAttributes($this->entity);
        $this->setRelationships();
        $this->hydrate($data);
    }

    /**
     * Alimente les propriétés du modèle.
     * 
     * @param  array  $data Tableau associatif de données.
     * @return $this
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
        return $this;
    }

    /**
     * Renvoie le nom de la table du modèle.
     * 
     * @return string 
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Renvoie la clé primaire ou les clés composites du modèle.
     * 
     * @return mixed|mixed[]
     */
    public function pk()
    {
        return $this->pk;
    }

    /**
     * Renvoie la valeur de la clé primaire ou des clés composites du modèle.
     * 
     * @return mixed|mixed[]
     */
    public function getPk()
    {
        $primaryKey = $this->pk;
        if (!is_array($primaryKey)) {
            return $this->$primaryKey;
        }
        $compositeKeys = [];
        foreach ($primaryKey as $key) {
            $compositeKeys[$key] = $this->$key;
        }
        return $compositeKeys;
    }

    /**
     * Assainit la valeurs de chaque propriété selon ses attributs.
     *
     * @param  mixed[] $data Tableau associatif de données qui seront alimentées dans le modèle.
     * @param  array[] $optAttributes (facultatif) Tableau associatif d'attributs additionnels.
     * @return $this
     */
    public function sanitize(array $data = [], array $optAttributes = [])
    {
        $this->hydrate($data);

        $this->properties = (new Sanitizer($this->properties, $this->attributes, true))
            ->sanitize($optAttributes);

        return $this;
    }

    /**
     * Vérifie la conformité de chaque propriété selon ses attributs.
     *
     * @param  int $id (facultatif) Fournir un id. si cette entrée existe déjà en BDD.
     * @return PropertyValidationErrors[] Liste des erreurs.
     */
    public function validate($id = null)
    {
        return (new Validator($this->properties, $this->attributes, $this))->validate($id);
    }
}
