$(document).ready(function() {
    // "use strict";
    $(document).on('click', '.view_actions', function () {
        let td = $(this).parent();
        let cluster_id = td.find('.cluster_id').val();

        //fix cluster id for filtering
        $('#cluster_id').val(cluster_id).change();
        //

        let user_id = $('#user_id').val();
        let month = $('#month').val();
        get_evaluation_data(cluster_id, user_id, month);
    });

    $('#month').on('change', function () {
        let cluster_id = $('#cluster_id').val();
        let user_id = $('#user_id').val();
        let month = $(this).val();
        get_evaluation_data(cluster_id, user_id, month);
    })
});

function get_evaluation_data(cluster_id, user_id, month) {
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
        type: 'POST',
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
            chart_div.empty();
            chart_div.append(
                '<canvas id="chart_convas" class="chartjs"></canvas>'
            );

            var parents_chart = document.getElementById("chart_convas");
            if (parents_chart !== null) {
                var chart = new Chart(parents_chart, {
                    // The type of chart we want to create
                    type: "line",

                    // The data for our dataset
                    data: {
                        labels: [...Array(data.data.length)].map((_, i) => i + 1),
                        datasets: [
                            {
                                label: "عملکرد ثبت شده توسط والدین",
                                backgroundColor: "transparent",
                                borderColor: "rgb(82, 136, 255)",
                                data: data.data,
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
                        legend: {
                            display: false
                        },
                        layout: {
                            padding: {
                                right: 10
                            }
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
                                        callback: function (value) {
                                            var ranges = [
                                                {divider: 1e6, suffix: "M"},
                                                {divider: 1e4, suffix: "k"}
                                            ];

                                            function formatNumber(n) {
                                                for (var i = 0; i < ranges.length; i++) {
                                                    if (n >= ranges[i].divider) {
                                                        return (
                                                            (n / ranges[i].divider).toString() + ranges[i].suffix
                                                        );
                                                    }
                                                }
                                                return n;
                                            }

                                            return formatNumber(value);
                                        }
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
                                    return "امتیاز: " + data["datasets"][0]["data"][tooltipItem["index"]];
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
