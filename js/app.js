var date = new Date();
var day = date.getDate();
var month = (date.getMonth()+1);
var ticketsData = null;

$(document).ready(function() {

    $('.graph').hide();

    $('#slider').slider({
        range: true,
        min: 0,
        max: 1000,
        values: [ 0, 1000 ],
        animate:"fast",
        step: 10,
        slide: function(event, slider) {
            $("#min-price").html(slider.values[0]);
            $("#max-price").html(slider.values[1]);
            // filter ticket prices
            // create a deep copy of original ticket prices (do not modify original array of objects)
            var filteredData = $.extend(true, [], ticketsData);
            filteredData.forEach((airline, airlineIndex) => {
                airline.data.forEach((price, priceIndex) => {
                    if(price < slider.values[0] || price > slider.values[1]) {
                        filteredData[airlineIndex].data[priceIndex] = 0;
                    }
                });
            });
            BarChart.config.data.datasets = filteredData;
            BarChart.update();
        }
    });

    $.getJSON({
        url: "api.php?destinations",
    }).done(function(data) {
        let destination = $('#destination');
        $.each(data, function(key, val) {
            destination.append(`<option value=${val[0]}>${val[1]}</option>`)
        });
    });

    $("#destination").selectmenu({
        change: function( event, ui ) {
            if(ui.item.index === 0) {
                $(".graph-block-graph p").show();
                $("img.loading-img").hide();
                $(".graph-block-graph .graph").hide();
                return null;
            }
            $(".graph-block-graph p").hide();
            // $(".graph-block-graph .graph").hide();
            // $("img.loading-img").show();
            $.getJSON({
                url: "api.php?destination=" + ui.item.index,
            }).done(function(data) {
                $('#slider').slider('values',0,0);
                $('#slider').slider('values',1,1000);
                ticketsData = data.airlines;
                BarChart.config.data.datasets = ticketsData;
                BarChart.update();
                DoughnutChart.config.data = data.attractions;
                DoughnutChart.update();
                LineChart.config.data.datasets[0].data = data.accommodation[1];
                LineChart.config.data.datasets[1].data = data.accommodation[0];
                LineChart.update();
                // $("img.loading-img").hide();
                $(".graph-block-graph .graph").show();
            });
        }
    });


    var ctx1 = document.getElementById('graph1').getContext('2d');
    var data1 = {
        labels: [day + "-" + month, day + 1 + "-" + month, day + 2 + "-" + month, day + 3 + "-" + month],
        datasets: []
    };
    var BarChart = new Chart(ctx1, {
        type: 'bar',
        data: data1,
        options: {
            tooltips: {
                callbacks: {
                    title: function(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': £' + tooltipItem.yLabel;
                    },
                    // afterLabel: function(tooltipItem, data) {
                    // }
                },
            },
            barValueSpacing: 10,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        callback: function(value, index, values) {
                            return "£" + value;
                        }
                    },
                }],
                xAxes: [{
                    barThickness: 10,
                }]
            }
        }
    });

    var ctx2 = document.getElementById('graph2').getContext('2d');
    var data2 = {
        datasets: [{
            data: [
            ],
            backgroundColor: [
            ],
            label: 'Dataset 1'
        }],
        labels: [
        ]
    }
    var DoughnutChart = new Chart(ctx2, {
        type: 'doughnut',
        data: data2,
        options: {
            tooltips: {
                callbacks: {
                    title: function(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function(tooltipItem, data) {
                        return data['datasets'][0]['data'][tooltipItem['index']] + '%';
                    },
                },
            }
        }
    });

    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var thisYearPrices = [18,22,15,8,12,16,13,23,18,12,10,15];
    var lastYearPrices = [12,19,28,15,18,10,15,18,24,20,10,13];
    var config = {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Last Year',
                backgroundColor: "#2ec5b6",
                borderColor: "#2ec5b6",
                data: thisYearPrices,
                fill: false,
            }, {
                label: 'This Year',
                fill: false,
                backgroundColor: "#e71d35",
                borderColor: "#e71d35",
                data: lastYearPrices,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false,
                text: 'Chart.js Line Chart'
            },
            tooltips: {
                callbacks: {
                    title: function(tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': £' + tooltipItem.yLabel;
                    },
                },
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month',
                    }
                }],
                yAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return "£" + value;
                        }
                    },
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Price',
                    }
                }]
            }
        }
    };

    var ctx3 = document.getElementById('graph3').getContext('2d');
    var LineChart = new Chart(ctx3, config);

});