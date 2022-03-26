<?php

namespace Core\Entities;

/**
 * Définit un objet relation entre une classe et une autre.
 * 
 * Utilisé pour les modèles et entités qui sont en relation avec d'autres.
 */
class Relationship
{
    /**
     * Instance de la relation.
     * 
     * @var object
     */
    protected $relation;

    /**
     * Nom de la classe de la relation.
     * 
     * @example: "Utilisateur", "UtilisateurModel"
     * 
     * @var string
     */
    protected $relationClassName;

    /**
     * Type de relation.
     * 
     * @var string 'has-one'|'has-many'
     */
    protected $type;

    /**
     * Clé étrangère du modèle.
     * 
     * @example: "fk_droit_id"
     * 
     * @var string
     */
    protected $foreignKey;

    /**
     * Clé du modèle étranger relié à la clé étrangère
     * 
     * @example: "droit_id"
     * 
     * @var string
     */
    protected $relatedKey;

    /**
     * Définit les propriétés de Relationship.
     *
     * @param string $relationClassName Nom de la classe de la relation (ex: 'DroitModel', 'Droit').
     * @param string $type         Type de relation ('has-one'|'has-many').
     * @param string $foreignKey   (facultatif) Clé étrangère du modèle (ex: 'fk_droit_id').
     * @param string $relatedKey   (facultatif) Clé du modèle étranger relié à la clé étrangère (ex: 'droit_id').
     */
    function __construct($relationClassName, $type, $foreignKey = null, $relatedKey = null)
    {
        $this->relationClassName = $relationClassName;
        $this->type = $type;
        $this->foreignKey = $foreignKey;
        $this->relatedKey = $relatedKey;
    }

    /**
     * Méthode magique pour récupérer les propriétés de cet objet.
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

    /**
     * Définit l'instance de la relation.
     * 
     * @param object $relation
     * @return void
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
    }
}
