$(function() {
    // Validation pour ne pas dépasser 100% sur le shareholder part
    const parts = document.getElementsByClassName('company-part');
    const submit = document.getElementById('company_Ajouter');
    for (const item of parts) {
        item.addEventListener('blur', function () {
            console.log('caca')
            let maxValue = 100;
            let inputTotal = 0;
            for (const part of parts) {
                inputTotal += parseInt(part.value);
            }
            if (inputTotal > maxValue) {
                this.classList.add('is-invalid');
                const div = document.createElement('div');
                div.classList.add('invalid-feedback');
                div.innerText = 'Le total des champs ne peux dépasser 100%';
                this.parentNode.appendChild(div);
                submit.disabled = true;
            } else {
                this.classList.remove('is-invalid');
                while (this.parentNode.lastChild !== this) {
                    this.parentNode.removeChild(this.parentNode.lastChild);
                }
                submit.disabled = false;
            }
        });
    }
    // Ajout des actionnaires
    $('#add-shareholder').on('click', function() {
        const index = $('#company_shareholders div.form-group').length;
        const template = $('#company_shareholders').data('prototype').replace(/__name__/g, index);
        $('#company_shareholders').append(template);
        handleDeleteButtons();

        // Validation pour ne pas dépasser 100% sur le shareholder part
        const parts = document.getElementsByClassName('company-part');
        const submit = document.getElementById('company_Ajouter');
        for (const item of parts) {
            item.addEventListener('blur', function () {
                console.log('caca')
                let maxValue = 100;
                let inputTotal = 0;
                for (const part of parts) {
                    inputTotal += parseInt(part.value);
                }
                if (inputTotal > maxValue) {
                    this.classList.add('is-invalid');
                    const div = document.createElement('div');
                    div.classList.add('invalid-feedback');
                    div.innerText = 'Le total des champs ne peux dépasser 100%';
                    this.parentNode.appendChild(div);
                    submit.disabled = true;
                } else {
                    this.classList.remove('is-invalid');
                    while (this.parentNode.lastChild !== this) {
                        this.parentNode.removeChild(this.parentNode.lastChild);
                    }
                    submit.disabled = false;
                }
            });
        }
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
        const url = '/log/api/company/category/';
        const countryId = $('#company_country').val();
        $.ajax({
            url: url + countryId,
            type: 'get',
        })
            .done(function (datas) {
                for (const option of $('#company_companyCategory option')) {
                    if (option.value.length < 8){
                        option.remove();
                    }
                }
                for(const data of datas){
                    $('#company_companyCategory').append(`<option value="${data.id}">${data.abbreviation} - ${data.name}</option>`);
                }
            })
    });

    // Event au chargement de la page pour sélectionner la bonne catégorie de société
    const url = '/log/api/company/category/';
    const countryId = $('#company_country').val();
    $.ajax({
        url: url + countryId,
        type: 'get',
    })
        .done(function (datas) {
            for (const option of $('#company_companyCategory option')) {
                if (option.value.length < 8){
                    option.remove();
                }
            }
            for(const data of datas){
                if(data.id == $('#companyCategoryToCheck').val()){
                    // Sélection du champs présent en db
                    $('#company_companyCategory').append(`<option selected value="${data.id}">${data.abbreviation} - ${data.name}</option>`)
                } else {
                    $('#company_companyCategory').append(`<option value="${data.id}">${data.abbreviation} - ${data.name}</option>`);
                }
            }
        })
});