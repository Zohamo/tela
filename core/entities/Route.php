<?php

namespace Core\Entities;

/**
 * Objet définissant une route:
 * - son URL avec sa méthode d'appel HTTP
 * - son contrôleur avec sa méthode associés
 * - ses paramètres de route
 * - ses éventuels droits d'accès (`$allowedRoles`)
 */
class Route
{
    /**
     * URL de la route.
     * 
     * @var string
     */
    protected $url;

    /**
     * Méthode HTTP de la route.
     * 
     * @var string
     */
    protected $method;

    /**
     * Contrôleur associé à la route.
     * 
     * @var string
     */
    protected $controller;

    /**
     * Action associée à la route (méthode appelée du contrôleur).
     * 
     * @var string
     */
    protected $action;

    /**
     * Paramètres de l'URL de la route (id,..).
     * 
     * @var mixed[]
     */
    protected $params = [];

    /**
     * Identifiants des rôles (droits) qui peuvent accéder à la route.
     * 
     * @var integer|integer[]
     */
    protected $allowedRoles = [];

    /**
     * Crée une instance de Route.
     *
     * @param string     $method
     * @param string     $url
     * @param string     $controller
     * @param string     $action
     * @param int|int[]  $allowedRoles
     */
    public function __construct($method, $url, $controller, $action = 'index', $allowedRoles = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->setController($controller);
        $this->action = $action;
        $this->setAllowedRoles($allowedRoles);
    }

    /**
     * Ajoute un paramètre de route.
     *
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Getter magique.
     *
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Setter : $controller.
     *
     * @param string $value
     * @return void
     */
    public function setController($value)
    {
        $this->controller = "App\Controllers\\" . $value;
    }

    /**
     * Setter : $allowedRoles.
     *
     * @param int|int[] $values
     * @return void
     */
    public function setAllowedRoles($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->allowedRoles = array_map(function ($value) {
            return intval($value);
        }, $values);
    }

    /**
     * Supprime le paramètre 'action'.
     * Pour ne pas l'envoyer dans les arguments de la méthode.
     *
     * @return void
     */
    public function unsetParamAction()
    {
        unset($this->params['action']);
    }
}
