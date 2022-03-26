<?php

namespace Core;

/**
 * Ce trait contient des méthodes utiles au QueryBuilder pour la construction des requêtes SQL.
 */
trait QueryBuilderSqlToolsTrait
{
    /**
     * Renvoie la liste des clauses SQL, ordonnée par type ('join', 'leftjoin'...).
     *
     * @return \Core\Entities\SqlClause[]
     */
    private function getSqlClauses()
    {
        $sqlClausesArray = include 'assets/sql-select-clauses.php';
        $sqlClauses = [];
        foreach ($sqlClausesArray as $type => $data) {
            $sqlClauses[$type] = new \Core\Entities\SqlClause($data);
        }
        return $sqlClauses;
    }

    /**
     * Définit les commandes SQL d'une requête SELECT à partir des clauses passées.
     * 
     * @return string[]
     */
    private function getSelectCommands()
    {
        $sqlClauses = $this->getSqlClauses();
        $commands = [];

        foreach ($sqlClauses as $type => $sqlClause) {
            if (!empty($this->clauses[$type])) {
                $clause = $this->clauses[$type];
                // Ajout de plusieurs clauses (comme plusieurs 'leftjoin' par exemple)
                if (is_array($clause) && !empty($clause[0]) && is_array($clause[0])) {
                    foreach ($clause as $clauseItem) {
                        $commands[] = $this->getCommandWithSeparator($sqlClause, $clauseItem);
                    }
                }
                // Ajout d'une simple clause
                else {
                    $commands[] = $this->getCommandWithSeparator($sqlClause, $clause);
                }
            }
        }

        return $commands;
    }

    /**
     * Construit et renvoie une commande SQL à partir d'une clause.
     * 
     * @param  \Core\Entities\SqlClause $sqlClause Clause SQL de référence.
     * @param  string|string[] $clause Clause définie dans la requête.
     * @return string
     */
    private function getCommandWithSeparator(\Core\Entities\SqlClause $sqlClause, $clause)
    {
        if (is_array($clause)) {
            if (isset($clause['separator'])) {
                $sqlClause->separator = $clause['separator'];
                unset($clause['separator']);
            }
            $clause = implode($sqlClause->separator, $clause);
        }

        return $sqlClause->command . ' ' . $clause;
    }

    /**
     * Renvoie la condition SQL et ses valeurs dans le cas où l'on possède la clé primaire
     * de la table.
     * 
     * @param  mixed[] $data
     * @param  mixed[] $values
     * @return mixed[] ['condition' => string, 'values' => mixed[]]
     */
    private function getUpdateConditionsIfPk($data, $values)
    {
        $condition = '';
        if (is_array($this->pk)) {
            $conditions = [];
            foreach ($this->pk as $ck) {
                $conditions[] = "$ck = :$ck";
                $values[$ck] = $data[$ck];
            }
            $condition = implode(', ', $conditions);
        } else {
            $condition = "{$this->pk} = :{$this->pk}";
            $values[$this->pk] = $data[$this->pk];
        }
        return ['condition' => $condition, 'values' => $values];
    }

    /**
     * Prépare les champs et les valeurs à insérer dans la base de données :
     * - Vérifie que tous les champs requis soient présents
     * - Prépare les booléens pour MySQL (les transforme en entiers)
     * - Supprime les champs inexistants dans la table ou non modifiables en BDD
     * 
     * @param  mixed[] $data
     * @param  boolean $checkRequired S'il faut renvoyer une erreur si des champs requis sont manquants.
     * @param  boolean $keepPk S'il faut préserver la clé primaire.
     * @return mixed[]
     * @throws InvalidArgumentException
     */
    private function prepareSaveFields($data, $checkRequired = true, $keepPk = false)
    {
        if ($checkRequired) {
            $data = $this->checkRequiredFields($data);
        }
        $data = $this->prepareBooleansForMySql($data);
        $data = $this->purgeFields($data, $keepPk);

        return $data;
    }

    /**
     * Vérifie que les champs requis soient présents.
     * 
     * @param  mixed[] $data
     * @return mixed[]
     */
    private function checkRequiredFields($data)
    {
        if (!method_exists($this, "propsAttributes")) {
            return $data;
        }
        $requiredFields = $this->attributes('required');
        foreach ($requiredFields as $requiredField) {
            if (!isset($data[$requiredField])) {
                throw new \InvalidArgumentException("Le champ '$requiredField' est nécessaire pour une insertion en BDD.");
            }
        }

        return $data;
    }

    /**
     * Vérifie que les champs requis soient présents.
     * 
     * @param  mixed[] $data
     * @return mixed[]
     */
    private function prepareBooleansForMySql($data)
    {
        if (!method_exists($this, "propsAttributes")) {
            return $data;
        }
        $boolFields = $this->attributes(['type' => 'boolean']);
        if (!empty($boolFields)) {
            foreach ($boolFields as $boolField) {
                $data[$boolField] = empty($data[$boolField]) ? 0 : 1;
            }
        }

        return $data;
    }

    /**
     * Supprime les champs inexistants dans la table ou non modifiables en BDD.
     * 
     * @param  mixed[] $data
     * @param  boolean $keepPk S'il faut préserver la clé primaire.
     * @return mixed[]
     */
    private function purgeFields($data, $keepPk)
    {
        if (!method_exists($this, "propsAttributes") || !method_exists($this, "hasProperty")) {
            return $data;
        }
        $fillableFields = $this->attributes('fillable');
        foreach (array_keys($data) as $field) {
            if (
                !$this->hasProperty($field)
                || (!in_array($field, $fillableFields)
                    && (!$keepPk
                        || ($keepPk
                            && !property_exists($this, 'pk') &&
                            ((!is_array($this->pk) && $field !== $this->pk)
                                || (is_array($this->pk) && in_array($field, $this->pk))))))
            ) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Récupère et renvoie la clause WHERE pour construire une requête SQL.
     * 
     * @return string
     */
    private function getWhereCondition()
    {
        $conditions = $this->clauses['where'];
        $sqlArr = [];

        foreach ($conditions as $condition) {
            if (is_array($condition)) {
                $sqlArr[] = implode(' ', $condition);
            } elseif (is_string($condition)) {
                $sqlArr[] = $condition;
            }
        }

        return implode(" AND ", $sqlArr);
    }
}
