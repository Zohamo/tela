<?php

namespace Core;

class Alert
{
    /**
     * Clé des alertes dans la superglobale $_SESSION.
     * 
     * @var string
     */
    const SESSION_KEY = "alerts";

    /**
     * Ajoute un message d'alerte dans la session
     *
     * @param  string $message  Contenu du message d'alerte.
     * @param  string $type     Type de classe Bootstrap (success, warning, danger, ...)
     * @return void
     */
    public static function add($message, $type = 'info')
    {
        $_SESSION[self::SESSION_KEY][] = [
            "message" => $message,
            "type"    => $type,
        ];
    }

    /**
     * Récupère la liste de messages à afficher depuis la session
     * puis les supprime
     *
     * @return array
     */
    public static function get()
    {
        $alerts = isset($_SESSION[self::SESSION_KEY]) ? $_SESSION[self::SESSION_KEY] : null;
        unset($_SESSION[self::SESSION_KEY]);
        return $alerts;
    }
}
