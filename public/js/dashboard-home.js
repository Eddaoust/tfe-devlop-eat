$(function () {

    $.ajax({
        type: 'GET',
        url: 'http://127.0.0.1:8000/log/api/project/year'
    }).done(function (datas) {
        const date = []
        const count = []
        for(const data of datas){
            date.push(data.year)
            count.push(data.projectCount)
        }

        var ctx = document.getElementById('myChart').getContext("2d");

        var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
        gradientStroke.addColorStop(0, '#80b6f4');
        gradientStroke.addColorStop(1, '#f49080');

        var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
        gradientFill.addColorStop(0, "rgba(128, 182, 244, 0.6)");
        gradientFill.addColorStop(1, "rgba(244, 144, 128, 0.6)");

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: date,
                datasets: [{
                    label: "Projet / année",
                    borderColor: gradientStroke,
                    pointBorderColor: gradientStroke,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    pointBorderWidth: 10,
                    pointHoverRadius: 10,
                    pointHoverBorderWidth: 1,
                    pointRadius: 3,
                    fill: true,
                    backgroundColor: gradientFill,
                    borderWidth: 4,
                    data: count
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Nombre de projets par année'
                },
                legend: {
                    position: "bottom"
                },
                animation: {
                    duration: 4000
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontColor: "rgba(0,0,0,0.5)",
                            fontStyle: "bold",
                            beginAtZero: true,
                            maxTicksLimit: 5,
                            padding: 20
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "rgba(0,0,0,0.5)",
                            fontStyle: "bold"
                        }
                    }]
                }
            }
        });
    });

    $.ajax({
        type: 'GET',
        url: 'http://127.0.0.1:8000/log/api/company/country'
    }).done(function (datas) {
        const country = []
        const count = []
        for(const data of datas){
            country.push(data.name)
            count.push(data.count)
        }

        var ctx = document.getElementById('myChart2').getContext('2d');

        var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
        gradientFill.addColorStop(0, 'rgba(211,131,18, 0.6)');
        gradientFill.addColorStop(1, 'rgba(168,50,121, 0.6)');

        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: country,
                datasets: [{
                    label: "Société / Pays",
                    backgroundColor: gradientFill,
                    data: count,
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Sociétés par pays'
                },
                animation: {
                    duration: 4000
                }
            }
        });
    });

    $.ajax({
        type: 'GET',
        url: 'http://127.0.0.1:8000/log/api/project/turnover'
    }).done(function (datas) {
        const company = []
        const turnover = []
        for(const data of datas){
            company.push(data.name)
            turnover.push(data.turnover)
        }

        var ctx = document.getElementById("myChart3");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: company,
                datasets: [{
                    label: 'Chiffre d\'affaire en euro',
                    data: turnover,
                    backgroundColor: [
                        'rgba(255,217,7, 0.6)',
                        'rgba(1,49,255, 0.6)',
                        'rgba(255,1,91, 0.6)',
                        'rgba(144,238,2, 0.6)',
                        'rgba(0,229,255, 0.6)',
                        'rgba(255,217,7, 0.6)',
                        'rgba(1,49,255, 0.6)',
                        'rgba(255,1,91, 0.6)',
                        'rgba(144,238,2, 0.6)',
                        'rgba(0,229,255, 0.6)',
                        'rgba(255,217,7, 0.6)',
                        'rgba(1,49,255, 0.6)',
                        'rgba(255,1,91, 0.6)',
                        'rgba(144,238,2, 0.6)',
                        'rgba(0,229,255, 0.6)'
                    ]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Chiffre d\'affaire des derniers projets'
                },
                animation: {
                    duration: 4000
                }
            }
        });
    });
})