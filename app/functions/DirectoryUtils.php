<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les répertoires.
 */
class DirectoryUtils
{
    /**
     * Copie un répertoire vers un autre de manière récursive.
     * 
     * @param string $source
     * @param string $destination
     * @return void
     */
    public static function copy($source, $destination)
    {
        $dir = opendir($source);
        @mkdir($destination);
        while (($file = readdir($dir)) !== false) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    self::copy($source . '/' . $file, $destination . '/' . $file);
                } else {
                    copy($source . '/' . $file, $destination . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Crée un répertoire s'il n'existe pas.
     * 
     * @param string $path Chemin du répertoire.
     * @return bool
     */
    public static function create($path)
    {
        if (!is_dir($path)) {
            return mkdir($path, 0777, true);
        }
        return false;
    }

    /**
     * Crée une arborescence de répertoires.
     * 
     * @param string|string[] $path Chemin du répertoire.
     * @return bool
     */
    public static function createTree($path)
    {
        // On explose le chemin en tableau
        $directories = is_array($path)
            ? $path
            : explode('/', $path);
        $tmpPath = "";
        foreach ($directories as $dir) {
            $tmpPath .= "$dir/";
            if ($tmpPath && !is_dir($tmpPath)) {
                mkdir($tmpPath, 0777, true);
            }
        }
        return is_dir($path);
    }

    /**
     * Vérifie qu'un répertoire n'est pas vide.
     *
     * @param  string $path   Chemin vers le répertoire
     * @param  array  $ignore Liste des répertoires à ignorer
     * @return bool|null `true`  : le répertoire n'est pas vide
     *                   `false` : le répertoire est vide
     *                   `null`  : le répertoire n'existe pas 
     */
    public static function isNotEmpty($path, $ignore = [])
    {
        if (!is_readable($path)) {
            return null;
        }
        $handle = opendir($path);
        while (($entry = readdir($handle)) !== false) {
            if (!in_array($entry, array_merge(['.', '..'], $ignore))) {
                closedir($handle);
                return true;
            }
        }
        closedir($handle);
        return false;
    }

    /**
     * Renvoie une liste du contenu d'un dossier.
     *
     * @param string $directory
     * @return string[]
     */
    public static function listContent($directory = ".")
    {
        $list = [];
        if ($handle = opendir($directory)) {
            while (($entry = readdir($handle)) !== false) {
                if ($entry != "." && $entry != "..") {
                    $list[] = $entry;
                }
            }
            closedir($handle);
        }
        return $list;
    }

    /**
     * Renvoie la liste des noms de répertoires des applications créées à partir du framework Tela dans /www.
     *
     * @return string[]
     */
    public static function listTelaApps()
    {
        $paths = array_filter(glob(dirname(path()) . '/*'), 'is_dir');
        $telaApps = [];
        foreach ($paths as $path) {
            if (is_readable($path . "/config.php")) {
                $tmp = explode('/', $path);
                $telaApps[] = array_pop($tmp);
            }
        }
        return $telaApps;
    }
}
