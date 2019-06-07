$(function() {
    // Info de suppression sur les boutons delete
    const deleteBtnList = $('.item-delete')
    $('[data-toggle="tooltip"]').tooltip()

    $.ajax({
        type: 'get',
        url: '/log/api/archi-projects'
    }).done(function(datas) {
        for(const deleteBtn of deleteBtnList){
            const inactiveLine = $.grep(datas, function(obj){return obj.id == deleteBtn.id;});
            if(inactiveLine.length > 0){
                deleteBtn.parentNode.setAttribute('data-toggle', '')
                deleteBtn.href = 'javascript:void(0)'
                deleteBtn.classList = 'company-delete text-secondary'
                deleteBtn.style.cursor = 'not-allowed'
                let tooltipStr = 'Suppression impossible car l\'architecte est utilisé dans les projest suivants:\n '
                for(const company of inactiveLine){
                    tooltipStr += '- ' + company.name + '\n '
                }
                deleteBtn.title = tooltipStr
            }
        }
    })
    // Initialisation de DataTables
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
})