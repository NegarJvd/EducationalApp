$(document).ready(function() {
    "use strict";
    var last_week_users = [];
    var last_week_orders = [];
    var this_week_users = [];
    var this_week_orders = [];

    $.ajax({
        url: '/panel/dashboard_info_counts',
        method: 'GET',
        datatype: 'json',
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val(),
            'Accept': 'application/json'
        },
        success: function (data) {
            last_week_users = data.data.last_week_users;
            last_week_orders = data.data.last_week_orders;
            this_week_users = data.data.this_week_users;
            this_week_orders = data.data.this_week_orders;

            var users_count = document.getElementById("users_count");
            if (users_count !== null) {
                var users_config = {
                    type: "line",
                    data: {
                        labels: ["شنبه", "1شنبه", "2شنبه", "3شنبه", "4شنبه", "5شنبه", "جمعه"],
                        datasets: [
                            {
                                label: "هفته جاری",
                                fill: false,
                                pointRadius: 4,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                backgroundColor: "transparent",
                                borderWidth: 2,
                                borderColor: "#fd7e14",
                                data: this_week_users
                            },
                            {
                                label: "هفته گذشته",
                                pointRadius: 4,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                fill: false,
                                backgroundColor: "transparent",
                                borderWidth: 2,
                                borderColor: "#443939",
                                data: last_week_users
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                right: 10,
                                left: 0,
                                top: 10,
                                bottom: 0
                            }
                        },

                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [
                                {
                                    gridLines: {
                                        drawBorder: false,
                                        display: false
                                    },
                                    ticks: {
                                        display: false, // hide main x-axis line
                                        beginAtZero: true
                                    },
                                    barPercentage: 1.8,
                                    categoryPercentage: 0.2
                                }
                            ],
                            yAxes: [
                                {
                                    gridLines: {
                                        drawBorder: false, // hide main y-axis line
                                        display: false
                                    },
                                    ticks: {
                                        display: false,
                                        beginAtZero: true
                                    }
                                }
                            ]
                        },
                        tooltips: {
                            titleFontColor: "#888",
                            bodyFontColor: "#555",
                            titleFontSize: 12,
                            bodyFontSize: 14,
                            backgroundColor: "rgba(256,256,256,0.95)",
                            displayColors: true,
                            borderColor: "rgba(220, 220, 220, 0.9)",
                            borderWidth: 2
                        }
                    }
                };
                new Chart(users_count, users_config);
            }

            var orders_chart = document.getElementById("orders_chart");
            if (orders_chart !== null) {
                var orders_config = {
                    type: "line",
                    data: {
                        labels: ["شنبه", "1شنبه", "2شنبه", "3شنبه", "4شنبه", "5شنبه", "جمعه"],
                        datasets: [
                            {
                                label: "هفته جاری",
                                fill: false,
                                pointRadius: 4,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                backgroundColor: "transparent",
                                borderWidth: 2,
                                borderColor: "#fd7e14",
                                data: this_week_orders
                            },
                            {
                                label: "هفته گذشته",
                                pointRadius: 4,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                fill: false,
                                backgroundColor: "transparent",
                                borderWidth: 2,
                                borderColor: "#443939",
                                data: last_week_orders
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                right: 10,
                                left: 0,
                                top: 10,
                                bottom: 0
                            }
                        },

                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [
                                {
                                    gridLines: {
                                        drawBorder: false,
                                        display: false
                                    },
                                    ticks: {
                                        display: false, // hide main x-axis line
                                        beginAtZero: true
                                    },
                                    barPercentage: 1.8,
                                    categoryPercentage: 0.2
                                }
                            ],
                            yAxes: [
                                {
                                    gridLines: {
                                        drawBorder: false, // hide main y-axis line
                                        display: false
                                    },
                                    ticks: {
                                        display: false,
                                        beginAtZero: true
                                    }
                                }
                            ]
                        },
                        tooltips: {
                            titleFontColor: "#888",
                            bodyFontColor: "#555",
                            titleFontSize: 12,
                            bodyFontSize: 14,
                            backgroundColor: "rgba(256,256,256,0.95)",
                            displayColors: true,
                            borderColor: "rgba(220, 220, 220, 0.9)",
                            borderWidth: 2
                        }
                    }
                };
                new Chart(orders_chart, orders_config);
            }

        },
        error: function (data) {
            swal("خطا!", "خطایی در دریافت اطلاعات داشبورد رخ داده است.", "error");
        },

    });

});
