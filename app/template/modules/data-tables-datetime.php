<?php

$vendorUrl = url('public/vendor');

return [

    /**
     * A insérer dans le <head> du document HTML
     */

    "head" => [
        ["type" => "js",  "url" => "$vendorUrl/moment/moment.min.js"],
        ["type" => "js",  "url" => "$vendorUrl/data-tables/DateTime-1.1.2/js/dataTables.dateTime.min.js"],
        ["type" => "css", "url" => "$vendorUrl/data-tables/DateTime-1.1.2/css/dataTables.dateTime.min.css"],
    ],

    /**
     * A insérer avant la fermeture de la balise </body> du document HTML
     */

    "end" => [
        ["type" => "js",  "url" => url('js') . "/data-tables-datetime-init.js"],
    ]
];
