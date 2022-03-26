<?php

namespace Core;

use Core\Entities\Relationship;

/**
 * Ce trait gère les relations avec d'autres modèles.
 * Il doit être placé dans le QueryBuilder pour pouvoir ajouter les clauses SQL aux requêtes.
 */
trait ModelRelationshipsTrait
{
    use RelationshipTrait;

    /**
     * Permet de récupérer la relation d'un modèle dans une requête `SELECT`.
     *
     * @param  string|string[] $relationName Nom (de la clé) de la relation.
     * @return $this
     * @throws InvalidArgumentException
     */
    public function with($relationNames)
    {
        if (!is_array($relationNames)) {
            $relationNames = [$relationNames];
        }
        $relList = [];
        foreach ($relationNames as $relationName) {
            if (!in_array($relationName, array_keys($this->relationships))) {
                throw new \InvalidArgumentException("La relation avec '$relationName' n'a pas été définie dans <em>App/Models/relationships/</em>.");
            }
            $relList[$relationName] = $this->relationships[$relationName];
        }
        $this->setRelationClause($relList);

        return $this;
    }

    /**
     * Ajoute la clause SQL de la relation.
     *
     * @param  Relationship[] $relationships
     * @return void
     * @throws LogicException
     */
    private function setRelationClause(array $relationships)
    {
        $this->clauses['leftjoin'] = [];
        $this->clauses['getManyRelations'] = [];

        $modelsDir = "\App\Models\\";
        foreach ($relationships as $relationName => $relationship) {
            $model = $modelsDir . $relationship->relationClassName;
            if (!class_exists($model)) {
                throw new \LogicException("La classe '$model' n'existe pas");
            }

            $relatedTable = (new $model())->getTable();
            $thisTable = $this->getTable();
            $foreignKey = $relationship->foreignKey;
            $relatedKey = $relationship->relatedKey ?: (new $model())->pk;

            switch ($relationship->type) {
                case "has-one":
                    $this->clauses['leftjoin'][] = [$relatedTable, "$thisTable.$foreignKey = $relatedTable.$relatedKey"];
                    break;
                case "has-many":
                    $relationship->setRelation(new $model());
                    $this->clauses['getManyRelations'][$relationName] = $relationship;
                    break;
                default:
                    throw new \LogicException("Le type de relation '$relationship->type' n'est pas défini.");
            }
        }
    }
}
