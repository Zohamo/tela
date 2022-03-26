<?php

/**
 *--------------------------------------------------------------------------
 * Les fichiers JavaScript et CSS suivants seront ajoutés globalement
 * sur toutes les pages de l'application
 *--------------------------------------------------------------------------
 */

$vendorUrl = url('public/vendor');

return [

    /**
     * A insérer dans le <head> du document HTML
     */

    "head" => [
        // JQuery
        ["type" => "js",  "url" => "$vendorUrl/jquery/jquery.min.js"],
        // Google Fonts
        ["type" => "css", "url" => "https://fonts.googleapis.com/css?family=Roboto"],
        // Font Awesome
        ["type" => "css", "url" => "$vendorUrl/font-awesome/css/all.min.css"],
        // Bootstrap
        ["type" => "css", "url" => "$vendorUrl/bootstrap-5.0.2/css/bootstrap.min.css"],
        ["type" => "js",  "url" => "$vendorUrl/bootstrap-5.0.2/js/bootstrap.bundle.min.js"],
        // Custom
        ["type" => "css", "url" => url('css') . "/style.css"],
    ],

    /**
     * A insérer avant la fermeture de la balise </body> du document HTML
     */

    "end" => [
        // Custom
        ["type" => "js",  "url" => url('js') . "/main.js"],
    ]
];
