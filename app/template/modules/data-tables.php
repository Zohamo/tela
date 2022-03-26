<?php

$vendorUrl = url('public/vendor');

return [

    /**
     * A insérer dans le <head> du document HTML
     */

    "head" => [
        ["type" => "js",  "url" => "$vendorUrl/data-tables/datatables.min.js"],
        ["type" => "css", "url" => "$vendorUrl/data-tables/datatables.min.css"],
    ],

    /**
     * A insérer avant la fermeture de la balise </body> du document HTML
     */

    "end" => [
        ["type" => "js",  "url" => url('js') . "/data-tables-init.js"],
    ]
];
