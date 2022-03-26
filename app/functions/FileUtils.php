<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les fichiers.
 */
class FileUtils
{
    /**
     * Crée un fichier et son arborescence.
     * 
     * @param string $path    Chemin vers le fichier.
     * @param string $content Contenu du fichier.
     * @return int|false Nombre de bytes écrits ou `false` en cas d'erreur.
     */
    public static function write($path, $content)
    {
        if (!$path) {
            return false;
        }
        $arr = explode('\\', $path);
        $fileName = array_pop($arr);

        $path = empty($arr)
            // Aucune arborescence : on crée le fichier dans 'batch/out'
            ? path('batch') . "/out"
            : implode('/', $arr);
        DirectoryUtils::create($path);

        // Création du fichier
        $fp = fopen($path . "/" . $fileName, "w+");
        $res = fwrite($fp, $content);
        fclose($fp);
        return $res;
    }
}
