<?php

namespace Core;

/**
 * Gestion des erreurs et exceptions
 */
class Error
{
    /**
     * Gestionnaire d'erreurs.
     * Convertit toutes les erreurs en Exceptions en renvoyant une ErrorException.
     *
     * @param int $level       Niveau d'erreur
     * @param string $message  Message d'erreur
     * @param string $file     Nom du fichier où l'erreur s'est produite
     * @param int $line        Numéro de ligne dans le fichier
     * @return void
     * @throws Exception
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // pour que l'opérateur @ fonctionne
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Gestionnaire d'exceptions.
     *
     * @param Exception $exception
     * @return exit
     */
    public static function exceptionHandler($exception)
    {
        // On récupère le code d'erreur
        $code = $exception->getCode();

        // On vérifie que ce code soit supporté par PHP
        if (in_array($code, \AppConstants::HTTP_CODES_PHP_ACCEPT)) {
            http_response_code($code);
        }

        // En mode debug on affiche l'erreur et la trace de pile
        if (env('APP_DEBUG')) {
            $msg = "<h1>Erreur fatale</h1>";
            $msg .= "<p>Code: '{$exception->getCode()}'</p>";
            $msg .= "<p>Exception: '{get_class($exception)}'</p>";
            $msg .= "<p>Message: '{$exception->getMessage()}'</p>";
            $msg .= "<p>Trace de pile:<pre>{$exception->getTraceAsString()}</pre></p>";
            $msg .= "<p>Détécté dans {$exception->getFile()}' ligne {$exception->getLine()}</p>";
            echo $msg;
        } else {
            // En mode debug inactif on affiche une page d'erreur générique
            View::error($code, !in_array($code, [401, 403]));
            if (in_array($code, [401, 403, 404])) {
                exit(1);
            }
            // Si le code n'est pas 401, 403 ou 404,
            // on crée un fichier log
            $date = date('Y-m-d H:i:s');
            $matricule = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur']->matricule : null;
            $message = "::: $date :::";
            $message .= "\nUtilisateur : $matricule";
            $message .= "\nCode : " . $exception->getCode();
            $message .= "\nException : " . get_class($exception);
            $message .= "\nMessage : " . $exception->getMessage();
            $message .= "\nTrace de pile :\n" . $exception->getTraceAsString();
            $message .= "\nDétecté dans '{$exception->getFile()}' ligne {$exception->getLine()}\n\n";
            error_log($message, 3, path('logs') . "/" . date('Y-m-d') . ".txt");
            // et une entrée dans t_log en BDD
            (new QueryBuilder())->table("t_log")
                ->insert([
                    "log_date" => $date,
                    "log_uti_matricule" => $matricule,
                    "log_code" => $exception->getCode() ?: null,
                    "log_message" => $exception->getMessage(),
                    "log_file" => $exception->getFile(),
                    "log_line" => $exception->getLine()
                ]);
        }
    }
}
