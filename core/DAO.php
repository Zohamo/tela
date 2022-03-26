<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Objet d'Accès aux Données (Database Access Object) qui hérite de l'objet PDO.
 * 
 * Cet objet permet de se connecter à la BDD et d'exécuter une requête.
 */
class DAO extends PDO
{
    /**
     * Crée une connexion à la Base De Données.
     * 
     * La configuration de la BDD est définie dans '.env' ("DB_CONNECTION", "DB_HOST"...).
     * 
     * @param  string $dbKey Indice de la configuration de BDD alternative à utiliser (ex: "DB_$dbKey_HOST").
     * @throws InvalidArgumentException
     */
    public function __construct($dbKey = "")
    {
        foreach (["CONNECTION", "HOST", "PORT", "DATABASE", "USERNAME", "PASSWORD"] as $key) {
            $$key = isset($_ENV["DB_{$dbKey}_$key"]) ? $_ENV["DB_{$dbKey}_$key"] : env("DB_$key"); // NOSONAR : Évite la duplication de code
        }
        $dsn = "$CONNECTION:host=$HOST; port=$PORT; dbname=$DATABASE"; // NOSONAR : Variables définies par $$key

        try {
            parent::__construct($dsn, $USERNAME, $PASSWORD);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            throw new \InvalidArgumentException("Erreur de connexion à la base de données : {$e->getMessage()}");
        }
    }

    /**
     * Renvoie un objet PDOStatement.
     * 
     * @param  string $query Requête SQL.
     * @return PDOStatement
     */
    public final function statement($query)
    {
        return $this->prepare($query);
    }

    /**
     * Exécute une requête SQL.
     * 
     * @param  string  $query  Requête SQL.
     * @param  mixed[] $values Tableau de valeurs pour éxécuter la requête.
     * @return int|false Nombre de lignes affectées par la requête ou `false` en cas d'erreur.
     * @throws DomainException
     */
    public final function execute($sql, $values = [])
    {
        try {
            $statement = $this->statement($sql);
            $statement->execute($values);
            return $statement->rowCount();
        } catch (PDOException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Exécute une requête SQL `SELECT`.
     * 
     * Si l'argument `$className` est défini chaque ligne retournée créera une instance de la classe.
     * 
     * @param  string  $query     Requête SQL.
     * @param  mixed[] $values    Tableau de valeurs pour éxécuter la requête.
     * @param  string  $className Nom de la classe à instancier.
     * @return mixed[]|object[] Résultat de la requête.
     * @throws DomainException
     */
    public final function executeSelect($query, $values = [], $className = null)
    {
        try {
            $statement = $this->statement($query);
            $statement->execute($values);
            return $className
                ? $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className)
                : $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $this->handleException($e, "SELECT");
        }
    }

    /**
     * Exécute une requête SQL `INSERT`.
     * 
     * @param  string  $query  Requête SQL.
     * @param  mixed[] $values Tableau de valeurs pour éxécuter la requête.
     * @return int|false Dernier id inséré ou `false` en cas d'erreur.
     * @throws DomainException
     */
    public final function executeInsert($query, $values)
    {
        try {
            $statement = $this->statement($query);
            $res = $statement->execute($values);
            if ($res !== false) {
                return $this->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            return $this->handleException($e, "INSERT");
        }
    }

    /**
     * Exécute une requête SQL `UPDATE`.
     * 
     * @param  string  $query  Requête SQL.
     * @param  mixed[] $values Tableau de valeurs pour éxécuter la requête.
     * @return int|false Nombre de lignes modifiées ou `false` en cas d'erreur.
     */
    public final function executeUpdate($query, $values)
    {
        return $this->execute($query, $values);
    }

    /**
     * Exécute une requête SQL `DELETE`.
     * 
     * @param  string  $query  Requête SQL.
     * @param  mixed[] $values Tableau de valeurs pour éxécuter la requête.
     * @return int|false Nombre de lignes supprimées ou `false` en cas d'erreur.
     */
    public final function executeDelete($query, $values)
    {
        return $this->execute($query, $values);
    }

    /**
     * Exécute une requête SQL d'insertion de procédure stockée.
     * 
     * @param  string $procedureQuery
     * @return bool
     * @throws DomainException
     */
    public final function executeInsertProcedure($procedureQuery)
    {
        try {
            return $this->exec($procedureQuery) === 0;
        } catch (PDOException $e) {
            return $this->handleException($e, "INSERT procédure");
        }
    }

    /**
     * Exécute une requête SQL de suppression de procédure stockée.
     * 
     * @param  string $name Nom de la procédure.
     * @return int Nombre de lignes supprimées.
     * @throws DomainException
     */
    public final function executeDeleteProcedure($name)
    {
        try {
            return $this->exec("DROP PROCEDURE IF EXISTS `$name`");
        } catch (PDOException $e) {
            return $this->handleException($e, "DELETE procédure '$name'");
        }
    }

    /**
     * Exécute une procédure stockée.
     *
     * @param  string  $name   Nom de la procédure.
     * @param  mixed[] $params Paramètres de la procédure.
     * @return bool
     * @throws DomainException
     */
    public final function executeCallProcedure($name, $params = [])
    {
        try {
            $params = join(', ', $params);
            return $this->execute("CALL $name($params)") !== false;
        } catch (PDOException $e) {
            return $this->handleException($e, "CALL procédure '$name'");
        }
    }

    /**
     * Exécute une requête SQL `CHECKSUM`.
     * 
     * @param  string $query Requête SQL.
     * @return int[] Résultat de la requête.
     */
    public final function executeChecksum($query)
    {
        return $this->executeSelect($query);
    }

    /**
     * Gère les exceptions relevées par PDO lors de l'exécution d'une requête SQL.
     *
     * @param  PDOException $e Exception PDO.
     * @param  string $reqName Nom de la requête.
     * @return false Dans le cas d'une transaction.
     * @throws DomainException
     */
    private final function handleException(PDOException $e, $reqName = "")
    {
        if ($this->inTransaction()) {
            debug($this->inTransaction());
            return false;
        }
        throw new \DomainException("Erreur ! Requête SQL $reqName invalide:<br />{$e->getCode()} - {$e->getMessage()}");
    }
}
