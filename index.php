<?php

/********************************************************************
 * INITIALISATION ET CHARGEMENT
 ********************************************************************/

// Fonctions utilitaires et de débogage
require_once 'helpers/functions.php';

// Autoloader de Composer
require_once 'vendor/autoload.php';

// Autoloader de l'application
require_once 'core/Autoloader.php';
\Core\Autoloader::register();

// Variables d'environnement (.env)
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/********************************************************************
 * GESTION DES ERREURS ET EXCEPTIONS
 ********************************************************************/

error_reporting(E_ALL);
set_error_handler('core\Error::errorHandler');
set_exception_handler('core\Error::exceptionHandler');

/********************************************************************
 * AUTHENTIFICATION
 ********************************************************************/

session_start();

/********************************************************************
 * ROUTEUR
 ********************************************************************/

use Core\Router;

// Définition du rôle de l'utilisateur
Router::setUserRole(0);

// Ajout des routes
require_once "routes/api.php";
require_once "routes/web.php";

// Recherche de la route puis aiguillage vers le contrôleur et son action
Router::dispatch();
