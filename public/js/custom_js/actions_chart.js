$(document).ready(function() {
    // "use strict";
    $(document).on('click', '.view_actions', function () {
        let td = $(this).parent();
        let cluster_id = td.find('.cluster_id').val();
        let content_name = td.parent().find('.content_name').text();
        let cluster_name = td.parent().find('.cluster_name').text();
        let steps_count = td.parent().find('.steps_count').text();

        //fix cluster id for filtering
        $('#cluster_id').val(cluster_id).change();
        $('#steps_count').val(steps_count).change();
        $('#content_name').text(content_name).change();
        $('#cluster_name').text(cluster_name).change();
        //

        let user_id = $('#user_id').val();
        let month = $('#month').val();
        get_evaluation_data(cluster_id, user_id, month, steps_count);
    });

    $('#month').on('change', function () {
        let cluster_id = $('#cluster_id').val();
        let user_id = $('#user_id').val();
        let month = $(this).val();
        let steps_count = $('#steps_count').val();
        get_evaluation_data(cluster_id, user_id, month, steps_count);
    })
});

function get_evaluation_data(cluster_id, user_id, month, steps_count) {
    let chart_div = $('.charts_div');

    chart_div.empty();
    chart_div.append(
        '<div class="card-body d-flex align-items-center justify-content-center" style="height: 400px">'+
        '<div class="sk-folding-cube">'+
        '<div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube3 sk-cube"></div><div class="sk-cube4 sk-cube"></div>'+
        '</div>'+
        '</div>'
    );

    $.ajax({
        url: '/panel/evaluation',
        type: 'GET',
        datatype: 'json',
        data: {
            cluster_id : cluster_id,
            user_id : user_id,
            month : month
        },
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val(),
            'Accept': 'application/json'
        },
        success: function (data) {
            $('#last_action_score').text(data.data.last_action_score).change();
            $('#last_visit_action_score').text(data.data.last_visit_action_score).change();

            chart_div.empty();
            $('#parents_chart').append('<canvas id="parents_chart_convas" class="chartjs"></canvas>');
            $('#therapists_chart').append('<canvas id="therapists_chart_convas" class="chartjs"></canvas>');

            var parents_chart = document.getElementById("parents_chart_convas");
            if (parents_chart !== null) {
                new Chart(parents_chart, {
                    // The type of chart we want to create
                    type: "line",

                    // The data for our dataset
                    data: {
                        labels: [...Array(data.data.results.length)].map((_, i) => i + 1),
                        datasets: [
                            {
                                label: "عملکرد",
                                backgroundColor: "transparent",
                                borderColor: "rgb(82, 136, 255)",
                                data: data.data.results,
                                lineTension: 0.3,
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                pointHoverRadius: 8,
                                pointHoverBorderWidth: 1
                            },{
                                label: "حداکثر مقدار",
                                backgroundColor: "transparent",
                                borderColor: "rgb(239,130,29)",
                                data: [...Array(data.data.results.length)].map((_, i) => i = steps_count * 2),
                                lineTension: 0.3,
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                pointHoverRadius: 8,
                                pointHoverBorderWidth: 1
                            }
                        ]
                    },

                    // Configuration options go here
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                right: 10
                            }
                        },
                        title: {
                            display: true,
                            text: 'عملکرد ثبت شده توسط والدین'
                        },
                        scales: {
                            xAxes: [
                                {
                                    gridLines: {
                                        display: false
                                    }
                                }
                            ],
                            yAxes: [
                                {
                                    gridLines: {
                                        display: true,
                                        color: "#eee",
                                        zeroLineColor: "#eee",
                                    },
                                    ticks: {
                                        beginAtZero: true,
                                        suggestedMax: ( (steps_count*2) + 1 )
                                    }
                                }
                            ]
                        },
                        tooltips: {
                            callbacks: {
                                title: function (tooltipItem, data) {
                                    return "تاریخ: " + month + "/" + data["labels"][tooltipItem[0]["index"]];
                                },
                                label: function (tooltipItem, data) {
                                    return "امتیاز: " + data["datasets"][tooltipItem["datasetIndex"]]["data"][tooltipItem["index"]];
                                }
                            },
                            responsive: true,
                            intersect: false,
                            enabled: true,
                            titleFontColor: "#888",
                            bodyFontColor: "#555",
                            titleFontSize: 12,
                            bodyFontSize: 18,
                            backgroundColor: "rgba(256,256,256,0.95)",
                            xPadding: 20,
                            yPadding: 10,
                            displayColors: false,
                            borderColor: "rgba(220, 220, 220, 0.9)",
                            borderWidth: 2,
                            caretSize: 10,
                            caretPadding: 15
                        }
                    }
                });
            }

            var therapists_chart = document.getElementById("therapists_chart_convas");
            if (therapists_chart !== null) {
                new Chart(therapists_chart, {
                    // The type of chart we want to create
                    type: "line",

                    // The data for our dataset
                    data: {
                        labels: data.data.visit_dates,
                        datasets: [
                            {
                                label: "عملکرد",
                                backgroundColor: "transparent",
                                borderColor: "rgb(82, 136, 255)",
                                data: data.data.visit_results,
                                lineTension: 0.3,
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                pointHoverRadius: 8,
                                pointHoverBorderWidth: 1
                            },{
                                label: "حداکثر مقدار",
                                backgroundColor: "transparent",
                                borderColor: "rgb(239,130,29)",
                                data: [...Array(data.data.visit_results.length)].map((_, i) => i = steps_count * 7),
                                lineTension: 0.3,
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                pointHoverRadius: 8,
                                pointHoverBorderWidth: 1
                            }
                        ]
                    },

                    // Configuration options go here
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                right: 10
                            }
                        },
                        title: {
                            display: true,
                            text: 'عملکرد ثبت شده توسط درمانگر'
                        },
                        scales: {
                            xAxes: [
                                {
                                    gridLines: {
                                        display: false
                                    }
                                }
                            ],
                            yAxes: [
                                {
                                    gridLines: {
                                        display: true,
                                        color: "#eee",
                                        zeroLineColor: "#eee",
                                    },
                                    ticks: {
                                        beginAtZero: true,
                                        suggestedMax: ( (steps_count*7) + 1 )
                                    }
                                }
                            ]
                        },
                        tooltips: {
                            callbacks: {
                                title: function (tooltipItem, data) {
                                    return "تاریخ: " + data["labels"][tooltipItem[0]["index"]];
                                },
                                label: function (tooltipItem, data) {
                                    return "امتیاز: " + data["datasets"][tooltipItem["datasetIndex"]]["data"][tooltipItem["index"]];
                                }
                            },
                            responsive: true,
                            intersect: false,
                            enabled: true,
                            titleFontColor: "#888",
                            bodyFontColor: "#555",
                            titleFontSize: 12,
                            bodyFontSize: 18,
                            backgroundColor: "rgba(256,256,256,0.95)",
                            xPadding: 20,
                            yPadding: 10,
                            displayColors: false,
                            borderColor: "rgba(220, 220, 220, 0.9)",
                            borderWidth: 2,
                            caretSize: 10,
                            caretPadding: 15
                        }
                    }
                });
            }

        },
        error: function (jqXhr, textStatus, errorThrown) {
            let response = JSON.parse(jqXhr.responseText);
            swal("خطا!", response.message, "error");
        },

    });
}
