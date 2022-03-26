<?php

/**
 * Clauses SQL utilisées pour la construction de requêtes SQL `SELECT` avec le Query Builder.
 * 
 * @see: \Core\QueryBuilder->select()
 */

$onSep = " ON ";
$andSep = " AND ";
$commaSep = ", ";

return [
    'join' => [
        'command' => 'JOIN',
        'separator' => $onSep,
    ],
    'leftjoin' => [
        'command' => 'LEFT JOIN',
        'separator' => $onSep,
    ],
    'rightjoin' => [
        'command' => 'RIGHT JOIN',
        'separator' => $onSep,
    ],
    'innerjoin' => [
        'command' => 'INNER JOIN',
        'separator' => $onSep,
    ],
    'where' => [
        'command' => 'WHERE',
        'separator' => $andSep,
    ],
    'group' => [
        'command' => 'GROUP BY',
        'separator' => $commaSep,
    ],
    'order' => [
        'command' => 'ORDER BY',
        'separator' => $commaSep,
    ],
    'having' => [
        'command' => 'HAVING',
        'separator' => $andSep,
    ],
    'limit' => [
        'command' => 'LIMIT',
        'separator' => $commaSep,
    ],
];
