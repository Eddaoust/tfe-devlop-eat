$(function () {

    $('#select-all').click(function () {
        if (this.checked) {
            $('.check-project').each(function () {
                this.checked = true;
            });
        } else {
            $('.check-project').each(function () {
                this.checked = false;
            });
        }
    });

    $('#table_id').DataTable({
        columnDefs: [
            {
                orderable: false,
                targets: 0
            }
        ],
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['pageLength'],
        lengthChange: true,
        language: {
            buttons: {
                pageLength: {
                    _: "Afficher %d éléments"
                }
            },
            search: "Recherche: ",
            info: "Affichage de _START_ à _END_ sur un total de _TOTAL_ éléments",
            paginate: {
                first:      "Première",
                last:       "Dernière",
                next:       "Suivant",
                previous:   "Précédent"
            }
        },
    });

    $('.dt-buttons').append('<button class="dt-button buttons-collection buttons-page-length" type="submit">Fusionner et télécharger les PDF sélectionnés</button>')
});

