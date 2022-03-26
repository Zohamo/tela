<?php

use Core\Router;

/**
 * Définition des routes des API.
 * 
 * Lors de la définition de l'URL, mettre les paramètres de route entre {},
 * exemple : "fruits/{id}/modifier".
 * 
 * Par défaut, les paramètres de route n'acceptent que les caractères alphanumériques, les '-' et les '_'.
 * 
 * Pour définir une expression régulière particulière à un paramètre de route, il faut l'ajouter après ':',
 * exemple : "droits/{id:\d+}/modifier", ici 'id' n'accepte que les chiffres.
 */
