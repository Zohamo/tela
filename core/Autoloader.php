<?php

namespace Core;

class Autoloader
{
    /**
     * Fonction déclarant la fonction Autoloader::autoload() comme étant la fonction de chargement par défaut
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Fonction d'autoloading déclarée comme autoloader dans la fonction register ci dessus
     * 
     * @param string $class Le nom de la classe à charger
     * @return bool Si la classe a pu être chargée.
     * @throws LogicException
     */
    public static function autoload($class)
    {
        $class = str_replace('\\', '/', $class);

        if ($class === "AppConstants") {
            $file = "helpers/AppConstants.php";
        } else {
            $arr = [
                "App/" => "app/",
                "Core/" => "core/",
                "/Controllers/" => "/controllers/",
                "/Entities/" => "/entities/",
                "/Functions/" => "/functions/",
                "/Models/" => "/models/",
            ];
            $file = str_replace(array_keys($arr), array_values($arr), $class) . ".php";
        }

        if (file_exists($file) && include_once($file)) {
            return true;
        }

        throw new \LogicException("spl_autoload : Une erreur est survenue lors du chargement de '$file'");
    }
}
