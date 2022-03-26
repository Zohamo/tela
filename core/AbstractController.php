<?php

namespace Core;

/**
 * Contrôleur de base.
 */
abstract class AbstractController
{
    /**
     * Liens à mettre en valeur dans la barre de navigation.
     * 
     * @var string[]
     */
    protected $activeLinks = [];

    /**
     * Utilisateur authentifié.
     * 
     * @var \App\Entities\User
     */
    protected $auth;

    /**
     * Crée une instance d'AbstractController.
     */
    public function __construct()
    {
        $this->auth = !empty($_SESSION['user']) ? $_SESSION['user'] : false;
        View::$auth = $this->auth;
        View::$activeLinks = $this->activeLinks;
    }

    /**
     * Vérifie la validité du jeton CSRF.
     * 
     * Renvoie toujours `true` en mode débogage.
     * 
     * @return bool
     */
    protected function checkCSRFToken()
    {
        if (env('APP_DEBUG')) {
            return true;
        }
        $k = 'CSRFToken';
        if (isset($_POST[$k]) && isset($_SESSION[$k]) && $_POST[$k] === $_SESSION[$k]) {
            unset($_SESSION[$k]);
            return true;
        }
        return false;
    }
}
