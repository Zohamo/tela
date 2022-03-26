<?php

/*
 |--------------------------------------------------------------------------
 | Définition des chemins statiques de l'application
 |--------------------------------------------------------------------------
 |
 | Ces chemins sont accessibles par les fonctions `path()` et `url()`
 | (voir /helpers/fonctions.php).
 |
 | exemples :
 |  path("js") vaut "/var/www/html/tela/public/js"     sur l'environnement "dev"
 |  url("js")  vaut "http://ld2app001d/tela/public/js" sur l'environnement "dev"
 |
*/

return [
    "root"           => "",                                 // Racine du projet
    "aliases"        => "/app/aliases",                     // Alias des colonnes des tables de la BDD
    "assets"         => "/core/assets",                     // Données statiques utiles au moteur
    "ctrl"           => "/app/controllers",                 // Contrôleurs
    "css"            => "/public/css",                      // Fichiers CSS
    "entities"       => "/app/entities",                    // Entités
    "img"            => "/public/img",                      // Images
    "js"             => "/public/js",                       // Fichiers JavaScript
    "logs"           => "/logs",                            // Journaux d'évènements
    "models"         => "/app/models",                      // Modèles
    "public"         => "/public",                          // Dossier public
    "public/vendor"  => "/public/vendor",                   // Bibliothèques CSS et/ou JS developpées par des tiers
    "template"       => "/app/template",                    // Composants, modules, mise en page
    "template/error" => "/app/template/pages/error",        // Pages d'erreur
    "vendor"         => "/vendor",                          // Bibliothèques PHP developpées par des tiers
    "views"          => "/app/views",                       // Vues
];
