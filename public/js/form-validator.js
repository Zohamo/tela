/**
 * Validateur de formulaire.
 * Fonctions de validation AJAX, d'affichage d'erreurs et redirection.
 *
 * # Description
 *
 * Si les données passées dans le formulaire sont correctes, on effectue une redirection.
 *
 * # Mise en place
 *
 *     1. Ajouter le module formValidator à la méthode du contrôleur appelant le formulaire :
 *        ["modules" => ["form-validator"]]
 *     2. Ajouter l'id="validatableForm" et data-redirect="url_de_redirection"
 *        à la balise <form> du formulaire.
 *     3. La méthode du contrôleur doit renvoyer la réponse du validateur :
 *        `$errors = $this->model->sanitize($request)->validate();`
 *        puis `HttpUtils::jsonResponse($errors);`
 *
 * # Fonctionnement
 *
 *     1. On crée le conteneur de la liste des erreurs s'il n'existe pas
 *     2. À la soumission du formulaire, on fait un appel AJAX: `validateForm()`
 *     3. S'il y a des erreurs, on les affiche: `displayErrors()`,
 *        sinon on redirige vers url_de_redirection
 */

jQuery(function () {
  const formId = "#validatableForm";
  if (!$(formId).length) return;

  $(formId).on("submit", function (e) {
    e.preventDefault();
    validateForm(formId, $(this).serialize(), $(this).data("redirect"));
  });

  // On crée le conteneur d'erreurs s'il n'a pas été défini
  if (!$("#form-errors").length) {
    buildListErrors(formId);
  }
  // On cache le conteneur des erreurs au clic sur la croix
  $("#form-errors .btn-close").click(function () {
    $(this).parent().parent().addClass("d-none").toggleClass("hide show");
  });
});

/**
 * Vérifie la validité du formulaire puis redirige vers `redirectUrl` ou affiche les erreurs.
 *
 * @param {string} formId      Attribut "id" du formulaire
 * @param {string} formData    Données du formulaire
 * @param {string} redirectUrl URL de redirection après validation
 */
function validateForm(formId, formData, redirectUrl) {
  // Désactivation du bouton 'submit'
  $(`${formId} [type=submit]`).addClass("disabled").attr("disabled", true);
  // On cache les erreurs précédentes
  hideErrors();

  // Appel AJAX
  $.post($(`${formId}`).attr("action"), formData, (errors) => {
    // Récupération des erreurs
    if (!errors || (Array.isArray(errors) && !errors.length)) {
      // Aucune erreur : redirection
      window.location.replace(redirectUrl);
    } else {
      // Affichage des erreurs
      displayErrors(errors);
    }
    // Réactivation du bouton 'submit'
    $(`${formId} [type=submit]`)
      .removeClass("disabled")
      .prop("disabled", false);
  });
}

/**
 * Affiche les erreurs de validité du formulaire.
 *
 * @param {array[]} errors Liste des erreurs.
 */
function displayErrors(errors) {
  // On affiche les nouveaux messages d'erreur
  for (var field in errors) {
    for (var error in errors[field]["errors"]) {
      // Si un message d'erreur personnalisé existe on l'affiche (ex: id="uti_matricule-unique")
      if ($(`#list-errors #${field}-${error}`).length) {
        $(`#list-errors #${field}-${error}`).removeClass("d-none");
      }
      // Sinon on crée un message temporaire avec le message de l'erreur
      else {
        $("#list-errors").append(
          `<li class="temp-error"><strong>${errors[field]["alias"]}</strong> ${errors[field]["errors"][error]["message"]}</li>`
        );
      }
    }
  }
  $("#form-errors").addClass("show").removeClass("d-none hide");
}

/**
 * Cache ou supprime tous les éventuels messages d'erreur.
 */
function hideErrors() {
  $("#form-errors").addClass("d-none hide").removeClass("show");
  $("#list-errors .temp-error").remove();
  $("#list-errors li").addClass("d-none");
}

/**
 * Crée le conteneur de la liste des erreurs.
 *
 * @param {string} formId
 */
function buildListErrors(formId) {
  $(`#${formId}`).prepend(
    `<div id="form-errors" class="alert alert-danger fade hide d-none" role="alert">
        <div class="d-flex">
            <ul id="list-errors" class="mb-0"></ul>
            <button type="button" class="btn close ms-auto" aria-label="Fermer">
                <i class="fas fa-times fa-xs" aria-hidden="true"></i>
            </button>
        </div>
    </div>`
  );
}
