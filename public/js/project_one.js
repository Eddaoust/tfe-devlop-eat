$(function () {

    const url = '/api/project/stat/';
    const id = $('#projectId').val();

    // Fonction pour récupérer les années manquantes (https://stackoverflow.com/questions/37277897/javascript-find-missing-number-in-array)
    let missingNumbers = (a, l=true) => Array.from(Array(Math.max(...a)).keys()).map((n, i) => a.indexOf(i) < 0  && (!l || i > Math.min(...a)) ? i : null).filter(f=>f);


    $.ajax({
        type: 'GET',
        url: url + id,
    }).done(function (data) {

        const state = {
            Vente: [],
            Prevente: [],
            Option: [],
            Location: [],
            Projection: [],
            date: []
        };

        const step = {
            name: ['Etude', 'Maîtrise', 'Permis', 'Travaux', 'Livraison'],
            date: []
        };

        const years = [];
        const chartLabel = []

        ////////////// SET THE STEP DATA ////////////////

        for (const key in data.step){
            if(data.step.hasOwnProperty(key) && data.step[key] != null){
                // Set step date
                const datetime = new Date(data.step[key].timestamp*1000);
                // Récupération de toutes les années des steps
                years.push(new Date(data.step[key].timestamp*1000).getFullYear());
                // Si ajout d'une date de fin + 3 mois
                if (key === 'study' || key === 'mastery' || key === 'delivery'){
                    step.date.push({
                        x: new Date(data.step[key].timestamp*1000),
                        y: 1
                    }, {
                        x: datetime.setMonth(datetime.getMonth() + 3),
                        y: 1
                    })
                } else {
                    step.date.push({
                        x: new Date(data.step[key].timestamp*1000),
                        y: 1
                    })
                }

            }
        }

        ////////////// Création du label des graphiques AXE X ////////////////

        // Suppression des doublons dans les années
        let yearsLabel = Array.from(new Set(years));
        // Ajout d'une année à la fin du tableau
        let lastYear = new Date(yearsLabel[yearsLabel.length-1].toString());
        let lastLabel = new Date(lastYear.setFullYear(lastYear.getFullYear()+1));
        // Ajout d'une année au début du tableau
        let firstYear = new Date(yearsLabel[0].toString());
        let firstLabel = new Date(firstYear.setFullYear(firstYear.getFullYear()-1));
        yearsLabel.push(lastLabel.getFullYear());
        yearsLabel.unshift(firstLabel.getFullYear());
        // Remplissage d'un tableau de date pour le label des deux graphs

        const yearsMissingLabel = yearsLabel.concat(missingNumbers(yearsLabel));
        // Trie les dates par ordre croissant
        yearsMissingLabel.sort(function (a, b) {
            return a - b
        });

        for (let yearLabel of yearsMissingLabel) {
            yearLabel = new Date(yearLabel.toString());
            chartLabel.push(yearLabel);
        }

        ////////////// SET THE STATE DATA ////////////////

        // Remplissage des dates du state
        for (const stateObj of data.state) {
            let date = new Date(stateObj.date.timestamp * 1000);
            state.date.push(date);
            years.push(date.getFullYear());
        }

        // Trie les dates par ordre croissant State
        state.date.sort(function (a, b) {
            return a - b
        });

        // Création des datasets pour chaque state
        for (const oneState of data.state) {
            let datetime = new Date(oneState.date.timestamp*1000);
            // test de la présence de la date dans le tableau
            if (state.date.findIndex(isSameDate) !== -1){
                // Test pour repérer la dernière itération
                if ((state.date.findIndex(isSameDate) + 2) > state.date.length){
                    state[oneState.name].push({
                        x: datetime,
                        y: oneState.quantity
                    }, {
                        // Dernière date du tableau
                        x: chartLabel[chartLabel.length -1],
                        y: oneState.quantity
                    })
                    // Test si Location
                } else if(oneState.name === 'Location'){
                    state[oneState.name].push({
                        x: datetime,
                        y: oneState.quantity
                    }, {
                        x: chartLabel[chartLabel.length - 1],
                        y: oneState.quantity
                    })
                }else {
                    state[oneState.name].push({
                        x: datetime,
                        y: oneState.quantity
                    }, {
                        x: state.date[state.date.findIndex(isSameDate) + 1],
                        y: oneState.quantity
                    })
                }
            }
            // Fonction de comparaison de deux objets Date différents
            function isSameDate(element){
                return element.valueOf() === datetime.valueOf()
            }
        }

        ////////////// INIT CHARTS ////////////////

        const timeline = document.getElementById("timeline").getContext('2d')
        const timeChart = new Chart(timeline, {
            type: 'line',
            data: {
                labels: chartLabel,
                datasets: [{
                    type: "line",
                    label: step.name[0],
                    backgroundColor: "#e87878",
                    data: [step.date[0], step.date[1]]
                }, {
                    type: "line",
                    label: step.name[1],
                    backgroundColor: "#8ac7b5",
                    data: [step.date[2], step.date[3]]
                }, {
                    type: "line",
                    label: step.name[2],
                    backgroundColor: "#ffd67d",
                    data: [step.date[4], step.date[5]]
                }, {
                    type: "line",
                    label: step.name[3],
                    backgroundColor: "#ab85bd",
                    data: [step.date[6], step.date[7]]
                }, {
                    type: "line",
                    label: step.name[4],
                    backgroundColor: "#7899c9",
                    data: [step.date[8], step.date[9]]
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
                            max: chartLabel[chartLabel.length - 1],
                            min: chartLabel[0],
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
                        gridLines: {
                            display: true,
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
                labels: chartLabel,
                datasets: [{
                    type: 'line',
                    label: Object.keys(state)[3],
                    backgroundColor: '#8d8b8f',
                    data: state.Location
                }, {
                    type: 'line',
                    label:Object.keys(state)[0],
                    backgroundColor: '#45a750',
                    data: state.Vente
                }, {
                    type: 'line',
                    label: Object.keys(state)[1],
                    backgroundColor: '#90cb99',
                    data: state.Prevente
                }, {
                    type: 'line',
                    label: Object.keys(state)[2],
                    backgroundColor: '#c8e5cc',
                    data: state.Option
                }, {
                    type: 'line',
                    label: Object.keys(state)[4],
                    backgroundColor: '#eff7f1',
                    borderColor: '#454545',
                    borderWidth: 2,
                    borderDash: [1, 2],
                    data: state.Projection
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                elements: {
                    line: {
                        tension: 0
                    }
                },
                scales: {
                    xAxes: [{
                        autoSkip: false,
                        position: "bottom",
                        type: "time",
                        time: {
                            max: chartLabel[chartLabel.length - 1],
                            min: chartLabel[0],
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
                            max: data.lots,
                            min: 0
                        }
                    }]
                }
            }
        })
    })
});
