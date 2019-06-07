$('#table_id').DataTable({
    responsive: true,
    info: false,
    language: {
        lengthMenu: "Afficher _MENU_ éléments par page",
        search: "Recherche: ",
        paginate: {
            first:      "Première",
            last:       "Dernière",
            next:       "Suivant",
            previous:   "Précédent"
        }
    }
})