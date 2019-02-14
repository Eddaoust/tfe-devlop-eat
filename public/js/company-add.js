// Ajout des actionnaires
$('#add-shareholder').on('click', function() {
    const index = $('#company_shareholders div.form-group').length;
    const template = $('#company_shareholders').data('prototype').replace(/__name__/g, index);
    $('#company_shareholders').append(template);
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').on('click', function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}
handleDeleteButtons();

for(const option of $('#company_companyCategory option')){
    if (option.value.length > 1){
        option.remove();
    }
}
// Ajax pour remplir le type de société par rapport au pays
$('#company_country').on('change', function () {
    const url = 'http://127.0.0.1:8000/admin/api/company/category/';
    const countryId = $('#company_country').val();
    $.ajax({
        url: url + countryId,
        type: 'get',
    })
        .done(function (datas) {
            for(const option of $('#company_companyCategory option')){
                if (option.value.length < 8){
                    option.remove();
                }
            }
            for(const data of datas){
                $('#company_companyCategory').append(`<option value="${data.id}">${data.abbreviation} - ${data.name}</option>`);
            }
        })
});