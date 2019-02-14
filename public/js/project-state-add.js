// Ajout des actionnaires
$('#add-state').on('click', function() {
    const index = $('#project_stat_state div.form-group').length;
    const template = $('#project_stat_state').data('prototype').replace(/__name__/g, index);
    $('#project_stat_state').append(template);
    handleDeleteButtons();

});


function handleDeleteButtons(){
    $('button[data-action="delete"]').on('click', function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}
handleDeleteButtons();


