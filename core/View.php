<?php

namespace Core;

/**
 * Permet d'afficher des vues en y passant des données depuis un contrôleur.
 * 
 * @see: app/views
 */
class View
{
    /**
     * Liste des liens qui seront mis en valeur comme étant actifs.
     *
     * @var string[]
     */
    public static $activeLinks = [];

    /**
     * Utilisateur authentifié.
     * 
     * @var \App\Entities\Utilisateur
     */
    public static $auth;

    /**
     * Nom du fichier d'initialisation des données nécessaires à l'affichage d'une vue.
     * 
     * @var string
     */
    public static $initFile = "/init.php";

    /**
     * Nom du fichier contenant les fonctions nécessaires à l'affichage d'une vue.
     * 
     * @var string
     */
    public static $functionsFile = "/functions.php";

    /**
     * Nom du fichier contenant la mise en page d'une vue.
     * 
     * @var string
     */
    public static $layoutFile = "/layout.php";

    /**
     * Affiche une vue.
     *
     * @param string $view    Vue générique ('list')
     *                        ou chemin relatif depuis le dossiers /views vers le fichier à afficher (ex: 'admin/utilisateur/form').
     * @param array  $args    Tableau associatif de données à afficher dans la vue (facultatif).
     * @param array  $scripts Tableau associatif des scripts/modules à insérer dans la vue (facultatif) :
     *                        'modules' (array) Modules à ajouter, 
     *                        'head' (array)    Fichiers à ajouter dans le <head> depuis /public/js/, 
     *                        'end' (array)     Fichiers à ajouter avant </body> depuis /public/js/
     *
     * @return void
     * @throws InvalidArgumentException|LogicException
     */
    public static function render($view = 'index', $args = [], $scripts = []) // NOSONAR : Variable utilisée dans un fichier inclus
    {
        // On récupère l'emplacement du fichier vue
        switch ($view) {
            case 'index':
            case 'list':
                if (!isset($args['list'])) {
                    throw new \InvalidArgumentException("Passez les données à la clé 'list' pour les afficher avec la vue générique.");
                }
                $view = path('template') . "/pages/list.php";
                break;
            default:
                $view =  path('views') . "/$view.php";
        }

        // Si le fichier vue existe on procède à l'affichage
        if (is_readable($view)) {
            // Initialisation des variables
            require path('template') . self::$initFile;
            // Récupération des fonctions utilitaires
            require path('template') . self::$functionsFile;
            // Mise en page
            require path('template') . self::$layoutFile;
        } else {
            throw new \LogicException("La page $view est introuvable !");
        }
    }

    /**
     * Renvoie l'affichage d'une vue pour appel AJAX.
     *
     * @param string $view    Vue générique ('list') ou chemin relatif vers le fichier à afficher (ex: 'admin/utilisateur/form').
     * @param array  $args    Tableau associatif de données à afficher dans la vue (facultatif).
     * @param array  $scripts Tableau associatif des scripts/modules à insérer dans la vue (facultatif) :
     *                        'modules' (array) Noms des modules à ajouter ; 
     *                        'head' (array)    Noms des fichiers à ajouter dans le <head> depuis /public/js/ ; 
     *                        'end' (array)     Noms des fichiers à ajouter avant </body> depuis /public/js/
     *
     * @return void
     * @throws InvalidArgumentException|LogicException
     */
    public static function ajax($view = 'index', $args = [], $scripts = []) // NOSONAR : Variables utilisées dans le fichier inclus
    {
        // On récupère l'emplacement du fichier vue
        switch ($view) {
            case 'index':
            case 'list':
                if (!isset($list)) {
                    throw new \InvalidArgumentException("Passez les données à la clé 'list' pour les afficher avec la vue générique.");
                }
                $view = path('template') . "/pages/list.php";
                break;
            default:
                $view =  path('views') . "/$view.php";
        }

        // Si le fichier vue existe on procède à l'affichage
        if (is_readable($view)) {
            // Initialisation des variables
            require path('template') . self::$initFile;
            // On récupère les fonctions utilitaires
            require path('template') . self::$functionsFile;
            // Affichage de la vue
            ob_start();
            include $view;
            echo ob_get_clean();
        } else {
            throw new \LogicException("La page $view est introuvable !");
        }
    }

    /**
     * Affiche une page d'erreur générique.
     *
     * @param string  $code Code d'erreur.
     * @param boolean $displayLayout S'il faut afficher la mise en page ou juste le message d'erreur (facultatif).
     *
     * @return void
     * @throws Exception
     */
    public static function error($code = 500, $displayLayout = true)
    {
        // On récupère l'emplacement du fichier vue
        $view = path('template/error') . "/$code.php";
        // Si le template de cette erreur n'est pas défini on renvoie vers la page d'erreur 500
        if (!is_readable($view)) {
            $view = path('template/error') . "/500.php";
        }

        // On procède à l'affichage
        if (!$displayLayout) {
            require $view;
            return;
        }
        // Initialisation des variables
        require path('template') . self::$initFile;
        // Récupération des fonctions utilitaires
        require path('template') . self::$functionsFile;
        // Mise en page
        require path('template') . self::$layoutFile;
    }
}
