<?php

/**
 * Attributs par défaut des propriétés de type chaîne de caractères.
 * 
 * @see \core\ModelPropertiesTrait.php
 */

return array_merge(include 'common.php', [
    'default'    => '',
    'trim'       => true,
    'escape'     => true,
    'min_length' => 0
]);
