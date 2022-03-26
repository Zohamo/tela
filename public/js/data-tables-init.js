/**
 * Initialisation de Data Tables.
 *
 * @see /public/vendor/data-tables
 *
 * @see https://datatables.net/
 * @see https://datatables.net/extensions/
 *
 * Certaines extensions sont déjà installées:
 * Buttons @see https://datatables.net/extensions/buttons/
 * SearchPanes @see https://datatables.net/extensions/searchpanes/
 * DateTime @see https://datatables.net/extensions/datetime/
 */
jQuery(function () {
  const dataTable = $(".data-table").DataTable({
    // https://datatables.net/reference/option/dom
    dom:
      "<'d-print-none d-md-flex justify-content-between align-items-center' B f>" +
      "<tr>" +
      "<'d-print-none d-md-flex justify-content-between align-items-center' l p>",
    buttons: ["copy", "excel", "pdf", "print"],
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Tout"],
    ],
    searchPanes: { collapse: false, controls: false },
    pageLength: 25,
    orderCellsTop: true,
    language: { url: appUrl + "/public/vendor/data-tables/fr-FR.json" },
    order: [],
    drawCallback: function () {
      this.find(".not-sortable").removeClass("sorting").off();
    },
  });

  // Ajout des filtres dans le DOM
  $(".data-table-filters").append(dataTable.searchPanes.container());

  // Filtre par date (nécessite le module 'data-tables-datetime')
  $("#data-table-minDate, #data-table-maxDate").change(dataTable.draw);
});
