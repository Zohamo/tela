<?php

namespace Core;

/**
 * Ce trait contient des méthodes utiles au QueryBuilder pour
 * l'utilisation de transactions SQL.
 */
trait QueryBuilderSqlTransactionTrait
{
    /**
     * Objet d'Accès aux Données (Database Access Object).
     * 
     * @var DAO
     */
    protected static $dao;

    /**
     * Démarre une transaction MySQL.
     * Nécessite l'utilisation de `commit()` pour valider la transaction.
     * 
     * @return $this
     */
    public function beginTransaction()
    {
        self::$dao->beginTransaction();
        return $this;
    }

    /**
     * Si une transaction a été déclarée.
     *
     * @return bool
     */
    public function inTransaction()
    {
        return self::$dao->inTransaction();
    }

    /**
     * Valide une transaction MySQL.
     * Nécessite l'utilisation de `beginTransaction()` pour démarrer la transaction.
     * 
     * @return bool
     */
    public function commit()
    {
        return self::$dao->commit();
    }

    /**
     * Annule les précédentes requêtes effectuées dans le cadre d'une transaction.
     * 
     * @return bool
     */
    public function rollBack()
    {
        return self::$dao->rollBack();
    }
}
