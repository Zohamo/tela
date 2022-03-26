<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les opérations cURL.
 */
class CurlUtils
{
    /**
     * Lance une opération cURL.
     * 
     * @param mixed[] $params Clés :
     *        string 'url'            URL de l'action à effectuer
     *        string 'requestType'    Type d'action à réaliser (lecture de dossier, récupération de fichier etc)
     *                                @see https://curl.haxx.se/libcurl/c/CURLOPT_CUSTOMREQUEST.html
     *        bool   'progress'       Toggle on/off progress meter (true = off)
     *        string 'proxyAuth'      Type d'authentification HTTP
     *        string 'userAgent'      Nom de la connexion
     *        array  'header'         Headers de la requètes http
     *        int    'redir'          Nombre maximum de redirection acceptées
     *        string 'certif'         Certificat utilisé lors de la connexion au serveur
     *        string 'certKey'        Clé du certificat utilisé lors de la connexion au serveur
     *        int    'verifyPeer'     Détermine si le curl doit vérifier le certificat de la cible (1 = oui, 0 = non)
     *        int    'verifyHost'     Détermine qu'est ce qui doit être vérifié. (2 = Le serveur est-il bien celui demandé ? 1 = unused 0 = pas de vérification)
     *        int    'followLoc'      Détermine si le curl accepte les redirections demandées par le serveur distant (1 = oui, 0 = non)
     *        int    'returnTransfer' Détermine si le curl accepte les redirections demandées par le serveur distant (1 = oui, 0 = non)
     * @return string|bool
     */
    public static function setCurl(array $params)
    {
        $p = array_merge([
            "url" => "",
            "requestType" => null,
            "progress" => true,
            "proxyAuth" => CURLAUTH_BASIC,
            "userAgent" => "curl/7.29.0",
            "header" => [],
            "redir" => 50,
            "certif" => null,
            "certKey" => null,
            "verifyPeer" => 1,
            "verifyHost" => 2,
            "followLoc" => 1,
            "returnTransfer" => 1
        ], $params);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $p['url']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $p['requestType']);
        curl_setopt($curl, CURLOPT_NOPROGRESS, $p['progress']);
        curl_setopt($curl, CURLOPT_PROXY, env('PROXY_URL'));
        curl_setopt($curl, CURLOPT_PROXYUSERPWD, env('PROXY_USER') . ':' . env('PROXY_PASSWORD'));
        curl_setopt($curl, CURLOPT_PROXYAUTH, $p['proxyAuth']);
        curl_setopt($curl, CURLOPT_USERAGENT, $p['userAgent']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $p['header']);
        curl_setopt($curl, CURLOPT_MAXREDIRS, $p['redir']);
        curl_setopt($curl, CURLOPT_SSLCERT, $p['certif']);
        curl_setopt($curl, CURLOPT_SSLKEY, $p['certKey']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $p['verifyPeer']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $p['verifyHost']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $p['followLoc']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $p['returnTransfer']);

        $res = curl_exec($curl);
        curl_close($curl);

        if (curl_error($curl)) {
            return curl_error($curl);
        }

        return $res;
    }
}
