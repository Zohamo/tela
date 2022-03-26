/**
 * Initialisation de l'extension 'datetime' de Data Tables.
 *
 * Permet de filtrer une plage de temps.
 *
 * @usage La balise <table> doit comporter l'attribut 'data-datetime'
 * avec l'index de la colonne à filtrer (à partir de 0).
 *
 * @see https://datatables.net/
 * @see https://datatables.net/extensions/datetime/
 */
jQuery(function () {
  // Création des champs de date
  minDate = new DateTime($("#data-table-minDate"), {
    format: dateFormat,
    locale: "fr",
    i18n: dateTimeI18n,
  });
  maxDate = new DateTime($("#data-table-maxDate"), {
    format: dateFormat,
    locale: "fr",
    i18n: dateTimeI18n,
  });
  // Définition de l'index de la colonne où filtrer
  dtIndex = $(".data-table").data("datetime");
});

/**
 * Constantes et variables.
 */

var minDate,
  maxDate,
  dtIndex = 0;

const dateFormat = "DD/MM/YYYY";

// Traductions du DatePicker.
const dateTimeI18n = {
  previous: "Précédent",
  next: "Premier",
  months: [
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre",
  ],
  weekdays: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
};

/**
 * Fonction de filtre qui recherchera dans la colonne définie par l'attribut 'data-datetime'
 * (dans la balise <table>) les données comprises entre les deux valeurs.
 */
$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
  const min = $("#data-table-minDate").val();
  const max = $("#data-table-maxDate").val();
  if (!min && !max) {
    return true;
  }
  if (!data[dtIndex]) {
    return false;
  }
  const dateMoment = moment(data[dtIndex], dateFormat);

  return (
    (!min || moment(min, dateFormat).isSameOrBefore(dateMoment)) &&
    (!max || moment(max, dateFormat).isSameOrAfter(dateMoment))
  );
});
