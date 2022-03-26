<?php

/*
 |--------------------------------------------------------------------------
 | Fonctions utilitaires de l'application
 |--------------------------------------------------------------------------
 |
 | Ces fonctions regroupent des utilitaires, des raccourcis, des fonctions
 | de débogage, etc...
 | Elles sont chargées en tout premier lieu dans index.php.
 |
*/

// Définit la Timezone par défaut pour éviter les erreurs liées à date()
date_default_timezone_set('Europe/Paris');

/********************************************************************
 * DÉBOGAGE
 ********************************************************************/

/**
 * Affiche des données.
 * 
 * @param mixed $data
 * @param mixed[] $origin
 * @return void
 */
/**
 * Affiche le contenu de n'importe quoi
 */
function debug($data = null, $origin = null)
{
    if (!$origin) {
        $origin = debug_backtrace()[0];
    }
    $type = gettype($data) == 'object' ? get_class($data) : gettype($data);
    echo "<pre>{$origin['file']}::{$origin['line']} (<em>$type</em>)<br />";
    if (is_string($data)) {
        echo $data; // Les grandes string sont tronquées avec var_dump
    } else {
        var_dump($data);
    }
    echo "</pre><hr />";
}

/**
 * Affiche le contenu de n'importe quoi
 */
function dump($data = null, $origin = null)
{
    if (!$origin) {
        $origin = debug_backtrace()[0];
    }
    debug($data, $origin);
}

/**
 * Affiche le contenu de n'importe quoi puis arrête le script
 */
function dd($data = null)
{
    $origin = debug_backtrace()[0];
    debug($data, $origin);
    die();
}

/**
 * Affiche des données dans la console.
 * 
 * @param mixed $data
 * @return void
 */
function console_log($data = null)
{
    echo "<script>console.log({json_encode($data)})</script>";
}

/**
 * Affiche toutes les variables ('var'), constantes ('const'), fonctions ('fn') ou tout ('all').
 * 
 * @param string $type 'var', 'const', 'fn' ou 'all'.
 * @return void
 */
function printAll($type = 'var')
{
    $all = [];
    switch ($type) {
        case 'const':
            $all = get_defined_constants();
            break;
        case 'fn':
            $all = get_defined_functions()['user'];
            break;
        case 'all':
            $all['var'] = get_defined_vars();
            $all['const'] = get_defined_constants();
            $all['fn'] = get_defined_functions()['user'];
            break;
        case 'var':
        default:
            $all = get_defined_vars();
    }
    debug($all);
}

/********************************************************************
 * RACCOURCIS
 ********************************************************************/

/**
 * Renvoie les alias d'un modèle.
 *
 * @param  string $modelName
 * @return string[]
 * @see: \App\aliases
 */
function aliases($modelName)
{
    $file = path('aliases') . '/' . str_replace('Model', 'Alias.php', (substr($modelName, strrpos($modelName, '\\') + 1)));
    return is_readable($file) ? include $file : null;
}

/**
 * Renvoie une constante de configuration depuis "composer.json".
 * 
 * @param string $keys,... Clés imbriquées pour accéder à la valeur.
 * @return string
 * @throws LogicException
 * 
 * @example: $docsUrl = config("support", "docs");
 */
function config(...$keys)
{
    $json = file_get_contents(path() . "/composer.json");
    $result = json_decode($json, true);
    foreach ($keys as $key) {
        if (isset($result[$key])) {
            $result = $result[$key];
        } else {
            throw new \LogicException("La clé imbriquée '$key' n'existe pas dans 'composer.json'.");
        }
    }
    return $result;
}

/**
 * Déchiffre une chaîne de caractères passée dans une URL.
 *
 * @param  string $str
 * @return string
 */
function decrypt($str)
{
    return \App\Functions\CryptUtils::decryptUrl($str);
}

/**
 * Chiffre une chaîne de caractères ou un nombre à passer dans une URL.
 *
 * @param  string|int|float $str
 * @return string
 */
function encrypt($str)
{
    return \App\Functions\CryptUtils::encryptUrl($str);
}

/**
 * Renvoie une constante d'environnement.
 * 
 * @param string $key
 * @return string|integer|double|boolean
 * @throws LogicException
 */
function env($key)
{
    // Renvoie le chemin à la racine du projet s'il n'est pas défini
    if ($key === "APP_ROOT" && empty($_ENV[$key])) {
        $val = realpath('');
    }
    // Cherche une éventuelle valeur de session (ex: APP_DEBUG)
    elseif (isset($_SESSION[$key])) {
        $val = $_SESSION[$key];
    }
    // Gère les erreurs
    elseif (!isset($_ENV[$key])) {
        throw new \LogicException("'$key' n'est pas défini dans les constantes d'environnement.");
    }
    // Gère les booléens
    else {
        switch ($_ENV[$key]) {
            case "true":
                $val = true;
                break;
            case "false":
                $val = false;
                break;
            default:
                $val = $_ENV[$key];
        }
    }
    return $val;
}

/**
 * Lance une Exception.
 * 
 * @see Core\Error::exceptionHandler()
 *
 * @param  int     $code
 * @param  string  $message
 * @return never
 * @throws Exception
 */
function error($code, $message = "")
{
    throw new \Exception($message, $code);
}

/**
 * Renvoie le chemin absolu d'un répertoire.
 *
 * @param  string $key
 * @return string
 * @see: \helpers\directories
 */
function path($key = "root")
{
    $dir = include 'directories.php';
    return env('APP_ROOT') . "$dir[$key]";
}

/**
 * Renvoie l'URL absolue d'un répertoire.
 *
 * @param  string $key
 * @return string
 * @see: \helpers\directories
 */
function url($key = "root")
{
    $dir = include 'directories.php';
    return env('APP_URL') . "$dir[$key]";
}

/**
 * Redirige vers l'URL relative demandée.
 *
 * @param  string $url ex: 'admin/utilisateurs'
 * @return void
 */
function redirect($url = null)
{
    $header = "Location: " . url();
    if ($url) {
        $header .= "/$url";
    }
    header($header);
}

/********************************************************************
 * AFFICHAGE
 ********************************************************************/

/**
 * Renvoie un alias de la ressource.
 *
 * @param  string $resource  Nom de la ressource (en PascalCase ou camelCase).
 * @param  string $key       Nom de l'alias.
 * @return string
 */
function alias($resource, $key = null)
{
    $aliasFile = path('aliases') . "/" . ucfirst($resource) . "Alias.php";
    if (!is_readable($aliasFile)) {
        return $key ? ucfirst($key) : ucfirst($resource);
    }
    $aliases = include $aliasFile;
    if ($key && !empty($aliases[$key])) {
        return $aliases[$key];
    }
    return  $key ? ucfirst($key) : ucfirst($resource);
}

/**
 * Convertit un format de date pour l'affichage.
 *
 * @param  string $date  Date au format 'Y-m-d'.
 * @param  string $after Format de date à afficher.
 * @return string
 */
function formatDate($date, $after = "")
{
    return \App\Functions\DateUtils::convert($date, 'Y-m-d', $after ?: \AppConstants::FORMAT_DATE_DISPLAY);
}

/**
 * Convertit un format de date pour l'affichage.
 *
 * @param  string $date  Date au format 'Y-m-d H:i:s'.
 * @param  string $after Format de date à afficher.
 * @return string
 */
function formatDatetime($date, $after = "")
{
    return \App\Functions\DateUtils::convert($date, 'Y-m-d H:i:s', $after ?: \AppConstants::FORMAT_DATETIME_DISPLAY);
}

/**
 * Formate l'affichage des nombres au format français.
 *
 * @param  int|float $number
 * @param  int $decimals Nombre de décimales après la virgule.
 * @param  string $thousandsSeparator Séparateur de milliers.
 * @return string
 */
function formatNumber($number, $decimals = 0, $thousandsSeparator = "&nbsp;")
{
    return $number === null || is_nan($number) ? $number : number_format($number, $decimals, ',', $thousandsSeparator);
}

/**
 * Formate l'affichage de la taille d'un fichier.
 * 
 * @param integer $size Taille en octets.
 * @return string
 */
function formatSize($size)
{
    if ($size < 1000) {
        $result = "$size octets";
    } elseif ($size < 1000000) {
        $ko = round($size / 1024, 2);
        $result = "$ko Ko";
    } elseif ($size < 1000000000) {
        $mo = round($size / (1024 * 1024), 2);
        $result = "$mo Mo";
    } else {
        $go = round($size / (1024 * 1024 * 1024), 2);
        $result = "$go Go";
    }

    return $result;
}
