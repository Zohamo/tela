<?php

namespace App\Functions;

/**
 * Utilitaires de cryptage et décryptage.
 * 
 * Nécessite les constantes d'environnement 'KEY_CRYPT' et 'KEY_IV'.
 */
class CryptUtils
{
    /**
     * Méthode cipher.
     * @var string
     */
    private static $cipher_algo = "AES-256-CBC";

    /**
     * Algorithme d'encodage.
     * @var string
     */
    private static $hash_algo = "sha256";

    /**
     * Chiffre une chaîne de caractères ou un nombre.
     * 
     * @param string|int|float $str
     * @return string
     */
    public static function encrypt($str)
    {
        if (!is_string($str) && is_nan($str)) {
            return $str;
        }

        $key = hash(self::$hash_algo, env('KEY_CRYPT'));
        // La méthode d'encryption AES-256-CBC attend 16 bytes, sinon elle renverra une erreur
        $iv = substr(hash(self::$hash_algo, env('KEY_IV')), 0, 16);

        return base64_encode(openssl_encrypt($str, self::$cipher_algo, $key, 0, $iv));
    }

    /**
     * Déchiffre une chaîne de caractères.
     * 
     * @param string $str
     * @return string
     */
    public static function decrypt($str)
    {
        if (!is_string($str)) {
            return $str;
        }

        $key = hash(self::$hash_algo, env('KEY_CRYPT'));
        // La méthode d'encryption AES-256-CBC attend 16 bytes, sinon elle renverra une erreur
        $iv = substr(hash(self::$hash_algo, env('KEY_IV')), 0, 16);

        return openssl_decrypt(base64_decode($str), self::$cipher_algo, $key, 0, $iv);
    }

    /**
     * Chiffre une chaîne de caractères à passer dans une URL.
     * 
     * @param string|int|float $str
     * @return string
     */
    public static function encryptUrl($str)
    {
        return is_string($str) || !is_nan($str)
            ? rtrim(strtr(self::encrypt($str), '+/', '-_'), '=')
            : $str;
    }

    /**
     * Déchiffre une chaîne de caractères passée dans une URL.
     * 
     * @param string $str
     * @return string
     */
    public static function decryptUrl($str)
    {
        return is_string($str)
            ? self::decrypt(strtr($str, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($str)) % 4))
            : $str;
    }

    /**
     * Génère et renvoie une clé aléatoire.
     * 
     * @param integer $length Taille de la clé.
     * @return string
     */
    public static function generateKey($length = 32)
    {
        return substr(base64_encode(openssl_random_pseudo_bytes($length)), 0, $length);
    }
}
