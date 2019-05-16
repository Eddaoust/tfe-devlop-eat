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
        buttons: [
            {
                text: 'Télécharger les PDF sélectionnés',
                className: 'merge-pdf'
            },
            'pageLength'
        ],
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

    // Event on merge pdf click
    $('.merge-pdf').click(function () {
        let projectIds = {
            'ids': []
        };
        $('.check-project').each(function () {
            if (this.checked) {
                projectIds.ids.push(this.value);
            }
        })
        $.ajax({
            type: 'POST',
            url: '/log/api/project/merge-pdf',
            data: projectIds,
        })
    });
});

