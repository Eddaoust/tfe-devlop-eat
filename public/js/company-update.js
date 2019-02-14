$(function() {
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

    // Ajax pour remplir le type de société par rapport au pays
    $('#company_country').on('change', function () {
        const url = 'http://127.0.0.1:8000/admin/api/company/category/';
        const countryId = $('#company_country').val();
        $.ajax({
            url: url + countryId,
            type: 'get',
        })
            .done(function (datas) {
                for(const option of $('#companyCategory option')){
                    if (option.value.length < 8){
                        option.remove();
                    }
                }
                for(const data of datas){
                    $('#companyCategory').append(`<option value="${data.abbreviation}">${data.abbreviation} - ${data.name}</option>`);
                }
            })
    });

    // Event au chargement de la page pour sélectionner la bonne catégorie de société
    const url = 'http://127.0.0.1:8000/admin/api/company/category/';
    const countryId = $('#company_country').val();
    $.ajax({
        url: url + countryId,
        type: 'get',
    })
        .done(function (datas) {
            for(const option of $('#companyCategory option')){
                if (option.value.length < 8){
                    option.remove();
                }
            }
            for(const data of datas){
                if(data.id == $('#companyCategoryToCheck').val()){
                    // Sélection du champs présent en db
                    $('#companyCategory').append(`<option selected value="${data.abbreviation}">${data.abbreviation} - ${data.name}</option>`)
                } else {
                    $('#companyCategory').append(`<option value="${data.abbreviation}">${data.abbreviation} - ${data.name}</option>`);
                }
            }
        })
})