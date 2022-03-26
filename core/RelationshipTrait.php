<?php

namespace Core;

/**
 * Définit les relations entre une classe et d'autres avec des objets
 * `Relationship`.
 */
trait RelationshipTrait
{
    /**
     * Relations entre un modèle ou une entité avec d'autres.
     * 
     * @var Relationship[] Tableau associatif.
     */
    protected $relationships = [];

    /**
     * Définit les relations entre cette classe et d'autres.
     * 
     * Cette méthode est généralement appelée dans le constructeur.
     *
     * @return $this
     */
    protected function setRelationships()
    {
        /*  Exemple avec Utilisateur :
        $this->relationships = [
            'droit' => new Relationship('Droit', 'has-one')
        ];
            Exemple avec UtilisateurModel :
        $this->relationships = [
            'droit' => new Relationship('DroitModel', 'has-one', 'fk_id_droit', 'droit_id')
        ]; */

        return $this;
    }
}
