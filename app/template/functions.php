<?php

/**
 *--------------------------------------------------------------------------
 * Fonctions utilitaires pour les vues
 *--------------------------------------------------------------------------
 */

/**
 * Renvoie "active" s'il s'agit de la page courante.
 * 
 * Utilisé pour mettre en valeur la page courante dans la barre de navigation.
 * 
 * Utilisation :
 * - définir `$activeLinks` dans le contrôleur.
 *      ex: `$activeLinks = ["administration", "utilisateur"]`
 * - (facultatif) ajouter une ou plusieurs clés en appelant View::render
 *      ex: View::render(
 *          ...,
 *          [
 *              [...]
 *              "activeLinks" => "formulaireUtilisateur", ...
 * - dans l'attribut 'classe' du lien de navigation ajouter <?= activeLink('identifiant-du-lien') ?>
 *      ex: <li class="nav-item <?= activeLink('formulaire-utilisateur'); ?>">
 *              <a class="nav-link" href="<?= url() ?>">
 *                  Formulaire utilisateur
 *              </a>
 *          </li>
 *
 * @param  string $link          Identifiant du lien.
 * @param  string $classActive   Nom de classe CSS à renvoyer si le lien est actif.
 * @param  string $classInactive Nom de classe CSS à renvoyer si le lien est inactif.
 * @return string
 */
function activeLink($link, $classActive = "active", $classInactive = "")
{
    return in_array($link, \Core\View::$activeLinks) ? $classActive : $classInactive;
}

/**
 * Renvoie un champ 'hidden' avec un Token CSRF à placer dans un formulaire.
 * 
 * La méthode checkCSRFToken() du contrôleur permet de vérifier la validité du jeton à la reception du formulaire.
 * 
 * @see /core/AbstractController.php
 * 
 * @return string
 */
function CSRFToken()
{
    if (empty($_SESSION['CSRFToken'])) {
        $_SESSION['CSRFToken'] = bin2hex(random_bytes(32));
    }

    return '<input type="hidden" name="CSRFToken" value="' . $_SESSION['CSRFToken'] . '" />';
}

/**
 * Renvoie le template de la modale de suppression.
 * 
 * Si l'URL est variable selon l'ID, remplacer l'ID par "{?}" dans l'URL,
 * puis ajouter l'attribut "data-id" avec la valeur de l'ID dans la balise du bouton
 * d'ouverture de la modale.
 * 
 * @example: Confirmation de suppression d'un utilisateur.
 * - `$modalId = "modalConfirmDeleteUtilisateur"`
 * - `$url = url() . "/admin/utilisateurs/{?}/supprimer"`
 * - button : `<button data-bs-toggle="modal" data-bs-target="#modalConfirmDeleteUtilisateur" data-id="<?= $utilisateur->id ?>"...`
 * 
 * @param string $modalId ex: "modalConfirmDeleteUtilisateur"
 * @param string $url URL de redirection en cas de confirmation.
 * @param string $message
 * @return void
 * @see: app/template/components/modal-confirm-delete.php
 */
function modalConfirmDelete($modalId, $url, $message = "Êtes-vous sûr·e de vouloir supprimer cet élément&nbsp;?") // NOSONAR : Variables utilisées dans le fichier inclus
{
    include path("template") . "/components/modal-confirm-delete.php";
}

/**
 * Renvoie les scripts JavaScript et/ou CSS à afficher dans la vue et à l'endroit correspondant.
 *
 * @param  string $location   Endroit dans lequel seront insérés les scripts ('head', 'end').
 * @param  array  $addScripts Scripts supplémentaires à intégrer dans la page.
 * @return string
 * @throws InvalidArgumentException
 */
function scripts($location, $addScripts = [])
{
    $pathTemplate = path('template');
    // On récupère les scripts globaux à l'application
    $scripts = include "$pathTemplate/scripts.php";
    $scripts = !empty($scripts[$location]) ? $scripts[$location] : [];

    // S'il y a des modules supplémentaires, on les ajoute aux scripts globaux
    if (!empty($addScripts['modules'])) {
        foreach ($addScripts['modules'] as $moduleName) {
            $scriptsFile = "$pathTemplate/modules/{$moduleName}.php";
            if (!is_readable($scriptsFile)) {
                throw new \InvalidArgumentException("Le fichier '{$moduleName}.php' est introuvable.");
            }
            $moduleScripts = include $scriptsFile;
            if (!empty($moduleScripts[$location])) {
                $scripts = array_merge($scripts, $moduleScripts[$location]);
            }
        }
    }

    // S'il y a des scripts supplémentaires, on les ajoute également
    if (!empty($addScripts[$location])) {
        $scripts = array_merge($scripts, $addScripts[$location]);
    }

    // On génère l'affichage des scripts avec le template
    if (!empty($scripts)) {
        include "$pathTemplate/components/script-link.php";
    }
}

/**
 * Renvoie la valeur d'une constante de session.
 *
 * @param  string $key
 * @return mixed
 */
function session($key)
{
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

/**
 * Renvoie le titre de la page.
 *
 * @param  string $title    Titre de la page.
 * @param  string $location Endroit où le titre sera affiché ('head', 'page').
 * @return string
 */
function title($title, $location = 'page')
{
    if ($location === "head") {
        $appName = env('APP_TITLE');
        return $title ? "$title | $appName" : $appName;
    }
    return $title;
}
