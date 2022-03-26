<?php

namespace Core\Entities;

use Core\AbstractEntity;

/**
 * Objet représentant une clause SQL avec sa commande et son séparateur.
 */
class SqlClause extends AbstractEntity
{
    /**
     * Commande SQL.
     * 
     * @var string
     * 
     * @example: 'LEFT JOIN', 'LIMIT'
     */
    protected $command;

    /**
     * Séparateur de cette clause.
     * 
     * @var string
     * 
     * @example: ' AND ', ' OR '
     */
    protected $separator;
}
