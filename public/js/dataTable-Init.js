$('#table_id').DataTable({
    responsive: true,
    'language': {
        "lengthMenu": "Afficher _MENU_ éléments par page",
        "search": "Recherche: ",
        "info": "Affichage de _START_ à _END_ sur un total de _TOTAL_ éléments",
        "paginate": {
            "first":      "Première",
            "last":       "Dernière",
            "next":       "Suivant",
            "previous":   "Précédent"
        }
    }
})