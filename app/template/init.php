<?php

/**
 * Initialisation des variables avant l'affichage d'une vue.
 */

use Core\View;

// Tous les arguments du tableau de type $args['key'] sont extraits en $key
// EXTR_SKIP : En cas de conflit, la variable existante ne sera pas écrasée
if (!empty($args)) {
    extract($args, EXTR_SKIP);
}

// Utilisateur authentifié
$auth = View::$auth;

// Messages d'alerte
$alerts = \Core\Alert::get();

// Initialisation des variables si n'ont pas été définies

if (!isset($title)) {
    $title = "";
}
if (isset($activeLinks)) {
    View::$activeLinks = array_merge(View::$activeLinks, $activeLinks);
}
if (!isset($json)) {
    $json = [];
}
if (empty($scripts)) {
    $scripts = [];
}
