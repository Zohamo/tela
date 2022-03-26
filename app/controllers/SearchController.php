<?php

namespace App\Controllers;

use App\Functions\ClassUtils;
use App\Functions\StringUtils;
use Core\AbstractController;
use Core\Alert;
use Core\View;

/**
 * Ce contrôleur permet d'effectuer des recherches dans la base de données.
 * 
 * Paramétrage :
 * - définir la propriété `$categories` ('utilisateur' n'est là qu'à titre d'exemple !!!)
 * - pour chaque modèle de `$categories`, définir les propriétés où effectuer la recherche (`'search' => true`)
 * 
 * Pour personnaliser les tableaux de résultats (supposons un nom de ressource `foo`) :
 * - créer dans `app\views\recherche\components` un fichier nommé `results-foo.php` à l'exemple de `results-utilisateur.php`
 */
class SearchController extends AbstractController
{
    /**
     * Recherche saisie.
     * @var string
     */
    private $search;

    /**
     * Nombre minimum de caractères de la saisie de recherche.
     * @var integer
     */
    private $searchMinLength = 3;

    /**
     * Nom de la ressource (modèle) où faire la recherche.
     * Cette catégorie doit faire partie de `$categories` pour être valide.
     * @var string
     */
    private $category;

    /**
     * Noms de toutes les catégories de ressources (modèles) où faire la recherche.
     * Si aucune catégorie n'est définie en POST, la catégorie sera considérée comme étant "tout" et
     * la recherche sera effectuée dans toutes ces catégories.
     * Pour être valide, la catégorie définie en POST (en kebab-case) doit faire partie de cette liste.
     * @var string[] Noms des ressources (en kebab-case)
     */
    private $categories = ['tout', 'utilisateur']; // `utilisateur` n'est là qu'à titre d'exemple !!!

    /**
     * Affiche la page de recherche.
     * 
     * @route("/search", methods={"GET", "POST"})
     */
    public function index($request = [])
    {
        $results = $this->setAndValidateSearch($request)
            ? $this->getResults($this->search, $this->category)
            : [];

        View::render(
            // Vue à utiliser dans le dossier /views
            'search/search-form',
            // Paramètres à afficher dans la vue
            [
                // Titre de la page
                "title" => "Recherche",
                // Liste des résultats à afficher par catégorie
                "results" => $results,
                // Recherche effectuée
                "search" => $this->search,
                // Liste des catégories pour le <select> du formulaire de recherche
                "categories" => $this->categories,
                // Catégorie dans laquelle la recherche a été effectuée
                "category" => $this->category
            ]
        );
    }

    /**
     * Assainit et vérifie la conformité des données passées en POST.
     * Crée une alerte si des erreurs sont relevées.
     *
     * @param mixed[] $params
     * @return bool
     */
    private function setAndValidateSearch(array $params)
    {
        $propertiesValidationErrors = []; // type : PropertyValidationErrors[]

        if (empty($params['search'])) {
            return false;
        }

        // Nettoyage du champ "recherche"
        $this->search = \App\Functions\StringUtils::cleanSearchString($params['search']);
        if (strlen($this->search) < $this->searchMinLength) {
            $propertiesValidationErrors['search'] =
                new \Core\Entities\PropertyValidationErrors('search', $this->search, "Recherche", [
                    'min_length' => new \Core\Entities\ValidationRuleError(
                        $this->searchMinLength,
                        "doit avoir au minimum {$this->searchMinLength} caractères."
                    )
                ]);
        }

        // Définition de la catégorie
        $this->category = empty($params['category'])
            ? $this->categories[0]
            : \App\Functions\StringUtils::cleanSearchString($params['category']);

        // Vérification de la catégorie
        if (!in_array($this->category, $this->categories)) {
            $propertiesValidationErrors['category'] =
                new \Core\Entities\PropertyValidationErrors('category', $this->category, "Recherche", [
                    'in' => new \Core\Entities\ValidationRuleError(
                        $this->category,
                        "doit correspondre à une des valeurs suivantes: " . implode(", ", $this->categories)
                    )
                ]);
        }

        if (empty($propertiesValidationErrors)) {
            return true;
        }

        $this->buildAlerts($propertiesValidationErrors);
        return false;
    }

    /**
     * Construit les messages d'erreur à afficher.
     *
     * @param PropertyValidationErrors[] $propertiesValidationErrors
     * @return void
     */
    private function buildAlerts($propertiesValidationErrors)
    {
        $html = "";
        foreach ($propertiesValidationErrors as $propertyValidationErrors) {
            foreach ($propertyValidationErrors->errors as $validationRuleError) {
                $html .= "<p class='mb-0'>Le sujet recherché {$validationRuleError->message}</p>";
            }
        }
        Alert::add($html, "danger");
    }

    /**
     * Renvoie les résultats de la recherche.
     * 
     * @param string $search   Recherche saisie
     * @param string $category Catégorie où effectuer la recherche (nom du modèle)
     * @return array
     */
    private function getResults($search, $category = null)
    {
        $results = [];
        if ($category && $category !== "tout") {
            $model = ClassUtils::getModel($category);
            if ($model) {
                $results[StringUtils::pascal($category, "-")] = $model->search($search);
            }
        } else {
            foreach ($this->categories as $category) {
                if ($category !== "tout") {
                    $results = array_merge($results, $this->getResults($search, $category));
                }
            }
        }
        return $results;
    }
}
