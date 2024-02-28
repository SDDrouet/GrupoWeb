// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

var xmlhttp = new XMLHttpRequest();
var url = "../temp/proyecciones.json";
xmlhttp.open("GET", url, true);
xmlhttp.send();
xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        var data = JSON.parse(this.responseText);

        // Ordenar el array de objetos por fecha de ingreso
        data.sort(function (a, b) {
            return new Date(a.fechaIngreso) - new Date(b.fechaIngreso);
        });

        // Obtener las fechas ordenadas y los valores de ingreso correspondientes
        var fechaIngreso = data.map(function (elem) {
            return elem.fechaIngreso;
        });

        var valorIngreso = data.map(function (elem) {
            return elem.valorIngreso;
        });

        data.sort(function (a, b) {
            return new Date(a.fechaEgreso) - new Date(b.fechaEgreso);
        });

        // Obtener las fechas ordenadas y los valores de egreso correspondientes
        var fechaEgreso = data.map(function (elem) {
            return elem.fechaEgreso;
        });

        var valorEgreso = data.map(function (elem) {
            return elem.valorEgreso;
        });

        var ctx = document.getElementById("myChartP");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechaIngreso,
                datasets: [{
                    label: 'Valores de Ingreso',
                    data: valorIngreso,
                    backgroundColor: [
                        'rgba(0, 102, 204, 0.2)', // Azul
                        'rgba(51, 153, 255, 0.2)', // Azul claro
                        'rgba(0, 153, 204, 0.2)', // Otro tono de azul
                        'rgba(0, 102, 153, 0.2)', // Otro tono de azul
                        'rgba(102, 153, 255, 0.2)', // Azul más claro
                        'rgba(0, 128, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(0, 102, 204, 1)', // Azul
                        'rgba(51, 153, 255, 1)', // Azul claro
                        'rgba(0, 153, 204, 1)', // Otro tono de azul
                        'rgba(0, 102, 153, 1)', // Otro tono de azul
                        'rgba(102, 153, 255, 1)', // Azul más claro
                        'rgba(0, 128, 255, 1)'
                    ],
                    borderWidth: 1,
                    tension: 0.4
                }, {
                    label: 'Valores de Egreso',
                    data: valorEgreso,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    tension: 0.4
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return '$' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                    }
                }
            }
        });
    }
}

