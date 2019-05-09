$(function () {

    const url = '/internal/api/project/stat/'
    const id = $('#projectId').val()

    $.ajax({
        type: 'GET',
        url: url + id
    }).done(function(datas) {

        const stateData = {
            Vente: [],
            Prevente: [],
            Option: [],
            Location: [],
            Projection: [],
            stateTime: [],
            stepName: ['Etude', 'Maîtrise', 'Permis', 'Travaux', 'Livraison'],
            stepDate: []
        }
        const years = []

        for (const key in datas.step){
            if(datas.step.hasOwnProperty(key) && datas.step[key] != null){
                const datetime = new Date(datas.step[key].timestamp*1000)
                // Récupération de toutes les années des steps
                years.push(new Date(datas.step[key].timestamp*1000).getFullYear())
                if (key === 'study' || key === 'mastery' || key === 'delivery'){
                    stateData.stepDate.push({
                        x: new Date(datas.step[key].timestamp*1000),
                        y: 1
                    }, {
                        x: datetime.setMonth(datetime.getMonth() + 3),
                        y: 1
                    })
                } else {
                    stateData.stepDate.push({
                        x: new Date(datas.step[key].timestamp*1000),
                        y: 1
                    })
                }

            }
        }
        // Suppression des doublons dans les années
        const yearsLabels = Array.from(new Set(years))
        // Ajout des années manquantes pour avoir toujours un tableau de 6 éléments
        while (yearsLabels.length < 6){
            const temp = yearsLabels[0] - 1
            yearsLabels.splice(0, 0, temp)
        }
        for (const item in yearsLabels){
            yearsLabels[item] = new Date(yearsLabels[item], 1)
        }

        // Définition de la date de fin de l'axe X
        const dateNowMoreOneYear = new Date(new Date().setFullYear(new Date().getFullYear()+1))
        const futurDate = new Date(new Date().setFullYear(yearsLabels[yearsLabels.length -1].getFullYear() + 1))
        yearsLabels[yearsLabels.length - 1].getFullYear() > dateNowMoreOneYear.getFullYear() ? yearsLabels.push(futurDate) : yearsLabels.push(dateNowMoreOneYear)

        // remplissage du tableau des dates
        for (const data of datas.state) {
            let date = new Date(data.date.timestamp * 1000)
            stateData.stateTime.push(date)
        }
        // Trie les dates par ordre croissant
        stateData.stateTime.sort(function (a, b) {
            return a - b
        })

        // Création des datasets pour chaque stateType
        for (const data of datas.state) {
            let datetime = new Date(data.date.timestamp*1000)
            // test de la présence de la date dans le tableau
            if (stateData.stateTime.findIndex(isSameDate) !== -1){
                // Test pour repérer la dernière itération
                if ((stateData.stateTime.findIndex(isSameDate) + 2) > stateData.stateTime.length){
                    const lastDate = new Date(datetime)
                    stateData[data.name].push({
                        x: datetime,
                        y: data.quantity
                    }, {
                        // Dernière date du tableau + 3 mois
                        x: yearsLabels[yearsLabels.length - 1],
                        y: data.quantity
                    })
                    // Test si Location
                } else if(data.name === 'Location'){
                    stateData[data.name].push({
                        x: datetime,
                        y: data.quantity
                    }, {
                        x: yearsLabels[yearsLabels.length - 1],
                        y: data.quantity
                    })
                }else {
                    stateData[data.name].push({
                        x: datetime,
                        y: data.quantity
                    }, {
                        x: stateData.stateTime[stateData.stateTime.findIndex(isSameDate) + 1],
                        y: data.quantity
                    })
                }
            }
            // Fonction de comparaison de deux objets Date différents
            function isSameDate(element){
                return element.valueOf() === datetime.valueOf()
            }
        }
        console.log(stateData)

        const timeline = document.getElementById("timeline").getContext('2d')
        timeline.height = 100
        const timeChart = new Chart(timeline, {
            type: 'line',
            data: {
                labels: yearsLabels,
                datasets: [{
                    type: "line",
                    label: stateData.stepName[0],
                    backgroundColor: "#e87878",
                    data: [stateData.stepDate[0], stateData.stepDate[1]]
                }, {
                    type: "line",
                    label: stateData.stepName[1],
                    backgroundColor: "#8ac7b5",
                    data: [stateData.stepDate[2], stateData.stepDate[3]]
                }, {
                    type: "line",
                    label: stateData.stepName[2],
                    backgroundColor: "#ffd67d",
                    data: [stateData.stepDate[4], stateData.stepDate[5]]
                }, {
                    type: "line",
                    label: stateData.stepName[3],
                    backgroundColor: "#ab85bd",
                    data: [stateData.stepDate[6], stateData.stepDate[7]]
                }, {
                    type: "line",
                    label: stateData.stepName[4],
                    backgroundColor: "#7899c9",
                    data: [stateData.stepDate[8], stateData.stepDate[9]]
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        autoSkip: false,
                        position: "bottom",
                        type: "time",
                        time: {
                            max: yearsLabels[yearsLabels.length - 1],
                            min: yearsLabels[0],
                            unit: "year",
                            parser: '[Q]Q - YYYY',
                            tooltipFormat: '[Q]Q - YYYY',
                            displayFormats: {
                                hour: 'YYYY',
                                day: 'YYYY',
                                week: 'YYYY',
                                month: 'YYYY',
                                quarter: 'YYYY',
                                year: 'YYYY'
                            }
                        },
                        gridLines : {
                            display : true,
                        },
                        ticks: {
                            source: 'labels',
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            max: 1,
                            min: 0,
                            stepSize: 1
                        }
                    }]
                }
            }
        })

        const ctx = document.getElementById("myChart").getContext('2d')
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: yearsLabels,
                datasets: [{
                    type: 'line',
                    label: Object.keys(stateData)[3],
                    backgroundColor: '#8d8b8f',
                    data: stateData.Location
                }, {
                    type: 'line',
                    label:Object.keys(stateData)[0],
                    backgroundColor: '#45a750',
                    data: stateData.Vente
                }, {
                    type: 'line',
                    label: Object.keys(stateData)[1],
                    backgroundColor: '#90cb99',
                    data: stateData.Prevente
                }, {
                    type: 'line',
                    label: Object.keys(stateData)[2],
                    backgroundColor: '#c8e5cc',
                    data: stateData.Option
                }, {
                    type: 'line',
                    label: Object.keys(stateData)[4],
                    backgroundColor: '#eff7f1',
                    borderColor: '#454545',
                    borderWidth: 2,
                    borderDash: [1, 2],
                    data: stateData.Projection
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                scales: {
                    xAxes: [{
                        autoSkip: false,
                        position: "bottom",
                        type: "time",
                        time: {
                            max: yearsLabels[yearsLabels.length - 1],
                            min: yearsLabels[0],
                            unit: "year",
                            parser: '[Q]Q - YYYY',
                            tooltipFormat: '[Q]Q - YYYY',
                            displayFormats: {
                                hour: 'YYYY',
                                day: 'YYYY',
                                week: 'YYYY',
                                month: 'YYYY',
                                quarter: 'YYYY',
                                year: 'YYYY'
                            }
                        },
                        gridLines : {
                            display : true
                        },
                        ticks: {
                            source: 'labels',
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            max: datas.lots,
                            min: 0
                        }
                    }]
                }
            }
        })
    })
})