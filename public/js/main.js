jQuery(function () {
  /**
   * Bootstrap : Popover
   */
  $('[data-bs-toggle="popover"]').popover({
    trigger: "hover",
    placement: "bottom",
    container: "body",
  });

  /**
   * Modifie le lien de la modale de confirmation de suppression.
   *
   * Au clic on récupère la valeur de l'attribut `data-id` du bouton
   * et on le met à la place de `{?}` dans l'attribut `href` du bouton
   * de confirmation de la modale.
   */
  $(".modal-confirm-delete").each(function () {
    const deleteItem = $(this).find(".btn-confirm").attr("href");
    $(`[data-bs-toggle='modal'][data-bs-target='#${this.id}']`).click(
      function () {
        $(`${$(this).data("bs-target")} .btn-confirm`).attr(
          "href",
          deleteItem.replace("{?}", $(this).data("id"))
        );
      }
    );
  });
});

/**
 * Crée un message d'alerte.
 *
 * @param {string} message Message à afficher
 * @param {string} type Type d'alerte Bootstrap (danger, success, warning, ...)
 *
 * @return {object} Objet JQuery
 */
function buildAlert(message, type = "danger") {
  return $(`<div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
      ${message}
      <button type="button" class="close" data-bs-dismiss="alert" aria-label="Fermer">
        <i class="fas fa-times fa-xs" aria-hidden="true"></i>
      </button>
    </div>`);
}
