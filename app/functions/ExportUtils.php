<?php

namespace App\Functions;

/**
 * Fonctions utiles pour les exportations de fichier.
 */
class ExportUtils
{
    /**
     * Convertit un tableau en CSV et force son téléchargement.
     * 
     * @param  array  $data             Tableau des données à passer dans le CSV
     * @param  string $fileName         Nom du fichier (sans extension)
     * @param  bool   $forceStringType  Force le type de cellule à 'chaîne de caractères'
     * @param  string $delimiter        Délimiteur pour les colonnes
     * @return string|false|null
     */
    public static function csv(array $data, $fileName, $forceStringType = true, $delimiter = ";")
    {
        if (empty($data)) {
            return null;
        }

        HttpUtils::dowloadSendHeaders("$fileName.csv", ['Content-Type: text/csv; charset=utf-8']);

        ob_start();
        $df = fopen("php://output", 'w');
        fputs($df, "\xEF\xBB\xBF");
        fputcsv($df, array_keys(reset($data)), $delimiter);

        foreach ($data as $row) {
            if ($forceStringType) {
                $row = array_map(function ($val) {
                    return '="' . $val . '"';
                }, $row);
            }
            fputcsv($df, $row, $delimiter);
        }

        fclose($df);
        return ob_get_clean();
    }

    /**
     * Convertit un tableau en tableur XLSX et force son téléchargement.
     * 
     * @uses phpoffice/phpspreadsheet
     *
     * @param array[] $data             Tableau de tableaux associatifs
     * @param string  $fileName         Nom du fichier (sans extension)
     * @param bool    $forceStringType  Force le type de cellule à 'chaîne de caractères'
     * @return void
     */
    public static function xlsx($data, $fileName, $forceStringType = true)
    {
        // Création de la feuille de calcul
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        if ($forceStringType) {
            $spreadsheet->getDefaultStyle()->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }

        // Ajout des données à la feuille
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(array_merge([array_keys($data[0])], $data));

        // Création du fichier XLSX
        ob_clean();
        ob_start();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Téléchargement
        HttpUtils::dowloadSendHeaders("$fileName.xlsx", ["Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"]);
        $writer->save('php://output');
        ob_end_flush();
        ob_flush();
        flush();
    }
}
