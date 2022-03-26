<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les classes.
 */
class ClassUtils
{
    /**
     * Renvoie un objet de type Model à partir du nom d'une ressource.
     * 
     * ex: `getModel('bureau-vote')` renvoie `new \App\Models\BureauVoteModel()`
     * 
     * @param string $resource Nom de la ressource en kebab-case.
     * @return object|false
     */
    public static function getModel($resource)
    {
        $model = "\\App\\Models\\" . StringUtils::switchCase($resource, 'pascal', "-") . "Model";
        if (file_exists(path() . str_replace('\\', '/', $model) . ".php") && class_exists($model)) {
            return new $model();
        }
        return false;
    }

    /**
     * Renvoie le nom de la classe d'un objet.
     * 
     * @param  object $object
     * @param  bool   $fullPath S'il faut renvoyer le chemin de la classe (ex: "App\Entities\Utilisateur").
     * @param  string $case Si `$fullPath=false`, définit le type de formatage à effectuer.
     * @return string|false
     * @see: App\Functions\StringUtils::switchCase()
     */
    public static function getName($object, $fullPath = true, $case = null)
    {
        $classPath = get_class($object);
        if ($classPath === self::class) {
            return false;
        }
        if ($fullPath) {
            return $classPath;
        }

        $arr = explode("\\", $classPath);
        $name = array_pop($arr);
        return in_array($case, ['p', 'pascal', null])
            ? $name
            : \App\Functions\StringUtils::switchCase(\App\Functions\StringUtils::camelToSpaced($name), $case);
    }
}
