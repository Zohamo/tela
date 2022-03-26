<?php

namespace App\Functions;

class ZipUtils
{
    /**
     * Ajoute les fichiers et les sous-répertoires d'un dossier à une archive ZIP.
     * 
     * @param string $folder
     * @param ZipArchive $zipFile
     * @param int $excludedLength Longueur du chemin (à supprimer du fichier).
     */
    private static function folderToZip($folder, &$zipFile, $excludedLength)
    {
        $handle = opendir($folder);
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Suppression du chemin du fichier avant de l'ajouter au ZIP
                $localPath = substr($filePath, $excludedLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Ajout du sous-répertoire
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $excludedLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * Extrait un fichier ZIP vers un dossier donné.
     * 
     * @param string $zipFile     Chemin vers le fichier ZIP.
     * @param string $destination Chemin vers le répertoire de sortie.
     * @return bool
     */
    public static function unzip($zipFile, $destination)
    {
        $zip = new \ZipArchive();
        if ($zip->open($zipFile)) {
            $zip->extractTo($destination);
            $zip->close();
            return true;
        }
        return false;
    }

    /**
     * Crée une archive ZIP à partir du contenu d'un répertoire.
     * 
     * @example ZipUtils::zipDir(['/path/to/file1', '/path/to/file2'], '/path/to/out.zip');
     *
     * @param \App\Entities\File[] $files Fichiers à archiver.
     * @param string $outZipPath Chemin de destination de l'archive ZIP.
     * @return bool
     */
    public static function zip($files, $outZipPath)
    {
        $zip = new \ZipArchive();

        if ($zip->open($outZipPath, \ZipArchive::CREATE) !== true) {
            return false;
        }
        foreach ($files as $file) {
            $zip->addFile($file->path, $file->name);
        }
        $zip->close();

        return is_readable($outZipPath);
    }

    /**
     * Crée une archive ZIP à partir d'un répertoire.
     * 
     * @example ZipUtils::zipDir('/path/to/sourceDir', '/path/to/out.zip');
     *
     * @param string $sourcePath Chemin du répertoire à archiver.
     * @param string $outZipPath Chemin de destination de l'archive ZIP.
     * @return bool
     */
    public static function zipDir($sourcePath, $outZipPath)
    {
        $pathInfo = pathInfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];

        $zip = new \ZipArchive();
        $zip->open($outZipPath, \ZipArchive::CREATE);
        $zip->addEmptyDir($dirName);
        self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        $zip->close();

        return is_readable($outZipPath);
    }
}
