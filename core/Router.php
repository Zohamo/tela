<?php

namespace Core;

use Core\Entities\Route;

/**
 * Routeur
 */
class Router
{
    /**
     * Tableau des routes.
     * 
     * @var Route[]
     */
    public static $routes = [];

    /**
     * L'identifiant du rôle (qui définit les droits) de l'utilisateur authentifié.
     * 
     * @var integer
     */
    private static $userRoleId = null;

    /**
     * Définit l'identifiant du rôle (droit) de l'utilisateur authentifié.
     *
     * @param integer $roleId Identifiant du rôle.
     * @return void
     */
    public static function setUserRole($roleId)
    {
        self::$userRoleId = intval($roleId);
    }

    /**
     * Ajoute les routes génériques d'un contrôleur :
     * 
     * - "$url" : index()
     * - "$url/{action}" : action()
     * - "$url/{id}/{action}" : action($id)
     * 
     * @see core/assets/routes-web-generic.php
     *
     * @param string    $url           URL de la route.
     * @param string    $controller    Contrôleur à instancier.
     * @param int|int[] $allowedRoles  Identifiants des rôles (droits) qui peuvent accéder à la route.
     * @return void
     */
    public static function controller($url, $controller, $allowedRoles = [])
    {
        $routes = include "assets/routes-web-generic.php";
        foreach ($routes as $route) {
            $action = isset($route['action']) ? $route['action'] : null;
            self::add($route['method'], $url . $route['url'], $controller, $action, $allowedRoles);
        }
    }

    /**
     * Ajoute une route au tableau des routes.
     *
     * @param string    $method        Méthode Http de la route.
     * @param string    $url           URL de la route.
     * @param string    $controller    Contrôleur à instancier.
     * @param string    $action        Méthode du contrôleur à appeler.
     * @param int|int[] $allowedRoles  Identifiants des rôles (droits) qui peuvent accéder à la route.
     * @return void
     */
    public static function add($method, $url, $controller, $action = '', $allowedRoles = [])
    {
        self::$routes[] = new Route($method, self::convertUrlToRegex($url), $controller, $action, $allowedRoles);
    }

    /**
     * Convertit une URL en expression régulière.
     *
     * @param string $url
     * @return string
     */
    private static function convertUrlToRegex($url)
    {
        // Échappe les \
        $url = str_replace('/', '\/', $url);
        // Convertit les paramètres sans expression régulière pour n'accepter que les caractères alphanumériques, "-" et "_"
        $url = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<\1>([a-zA-Z0-9_-]*))', $url);
        // Convertit les paramètres avec une expression régulière : ex: `{id:\d+}`
        $url = preg_replace('/\{([a-zA-Z]+):([^\}]+)\}/', '(?P<\1>\2)', $url);
        // Ajoute les délimiteurs de début et de fin, un "/" facultatif et une insensibilité à la casse
        return "/^$url\/?$/i";
    }

    /**
     * note : Les méthodes suivantes sont redondantes, une __callStatic() aurait suffit
     * mais cela permet d'obtenir de la documentation lors de la définition de la fonction.
     */

    /**
     * Ajoute une route de méthode Http 'GET'.
     *
     * @param string    $url           URL de la route.
     * @param string    $controller    Contrôleur à instancier.
     * @param string    $action        Méthode du contrôleur à appeler.
     * @param int|int[] $allowedRoles  Identifiants des rôles (droits) qui peuvent accéder à la route.
     * @return void
     */
    public static function get($url, $controller, $action = '', $allowedRoles = [])
    {
        self::add("GET", $url, $controller, $action, $allowedRoles);
    }

    /**
     * Ajoute une route de méthode Http 'POST'.
     *
     * @param string    $url           URL de la route.
     * @param string    $controller    Contrôleur à instancier.
     * @param string    $action        Méthode du contrôleur à appeler.
     * @param int|int[] $allowedRoles  Identifiants des rôles (droits) qui peuvent accéder à la route.
     * @return void
     */
    public static function post($url, $controller, $action = '', $allowedRoles = [])
    {
        self::add("POST", $url, $controller, $action, $allowedRoles);
    }

    /**
     * Ajoute une route de méthode Http 'PUT'.
     *
     * @param string    $url           URL de la route.
     * @param string    $controller    Contrôleur à instancier.
     * @param string    $action        Méthode du contrôleur à appeler.
     * @param int|int[] $allowedRoles  Identifiants des rôles (droits) qui peuvent accéder à la route.
     * @return void
     */
    public static function put($url, $controller, $action = '', $allowedRoles = [])
    {
        self::add("UPDATE", $url, $controller, $action, $allowedRoles);
    }

    /**
     * Ajoute une route de méthode Http 'DELETE'.
     *
     * @param string    $url           URL de la route.
     * @param string    $controller    Contrôleur à instancier.
     * @param string    $action        Méthode du contrôleur à appeler.
     * @param int|int[] $allowedRoles  Identifiants des rôles (droits) qui peuvent accéder à la route.
     * @return void
     */
    public static function delete($url, $controller, $action = '', $allowedRoles = [])
    {
        self::add("DELETE", $url, $controller, $action, $allowedRoles);
    }

    /**
     * Récupère la route appelée, crée le contrôleur et exécute l'action correspondante.
     *
     * @return void
     * @throws DomainException|BadMethodCallException
     */
    public static function dispatch()
    {
        $route = self::match(self::getCurrentUrl());

        if (!$route) {
            throw new \DomainException("La page que vous recherchez n'existe pas.", 404);
        }
        if (!self::hasAccess($route->allowedRoles)) {
            throw new \DomainException("Vous n'avez pas les droits d'accès à cette page.", 403);
        }

        $controllerName = $route->controller;
        if (!class_exists($controllerName)) {
            throw new \DomainException("Le contrôleur $controllerName est introuvable.", 404);
        }

        $controller = new $controllerName();

        $action = $route->action;
        if (!$action) {
            if (isset($route->params['action'])) {
                $action = $route->params['action'];
                $route->unsetParamAction();
            } else {
                $action = 'index';
            }
        }
        $action = \App\Functions\StringUtils::camel($action, '-');
        if (!method_exists($controller, $action)) {
            throw new \BadMethodCallException("La méthode $action() est introuvable dans le contrôleur $controllerName.", 404);
        }

        $arguments = array_values($route->params);
        $arguments[] = $_REQUEST;
        $controller->$action(...$arguments);
    }

    /**
     * Renvoie l'URL courante sans les paramètres de type $_GET.
     *
     * Une URL de format "localhost/?page" (une variable, aucune valeur) ne fonctionnera
     * cependant pas car le .htaccess convertira le premier "?" en "&".
     * 
     * @example localhost/users?page=2&droit=1 renvoie localhost/users
     *
     * @return string
     */
    private static function getCurrentUrl()
    {
        $url = $_SERVER['QUERY_STRING'];

        if (!$url) {
            return "";
        }

        $parts = explode('&', $url, 2);
        return strpos($parts[0], '=') === false
            ? $parts[0]
            : '';
    }

    /**
     * Renvoie la route correspondant à l'URL dans le tableau des routes.
     *
     * @param string $currentUrl
     * @return Route|false
     */
    private static function match($currentUrl)
    {
        foreach (self::$routes as $route) {
            if (
                $_SERVER['REQUEST_METHOD'] ===  $route->method
                && preg_match($route->url, $currentUrl, $matches)
            ) {
                // Récupère les paramètres de route
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $route->addParam($key, $match);
                    }
                }
                return $route;
            }
        }
        return false;
    }

    /**
     * Vérifie si la route nécessite des droits d'accès
     * et/ou si l'utilisateur les détient.
     *
     * @param integer[] $allowedRoles
     * @return bool
     */
    private static function hasAccess(array $allowedRoles)
    {
        if (empty($allowedRoles) || self::$userRoleId === \AppConstants::ID_DROIT_SUPER) {
            return true;
        }

        foreach ($allowedRoles as $allowedRole) {
            if ($allowedRole === self::$userRoleId) {
                return true;
            }
        }
        return false;
    }
}
