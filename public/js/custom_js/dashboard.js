$(document).ready(function() {
    "use strict";
    var last_week_users = [];
    var this_week_users = [];
    var last_week_contents = [];
    var this_week_contents = [];
    var last_week_admins = [];
    var this_week_admins = [];

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
            this_week_users = data.data.this_week_users;
            last_week_contents = data.data.last_week_contents;
            this_week_contents = data.data.this_week_contents;
            last_week_admins = data.data.last_week_admins;
            this_week_admins = data.data.this_week_admins;

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

            var contents_count = document.getElementById("contents_count");
            if (contents_count !== null) {
                var contents_config = {
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
                                data: this_week_contents
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
                                data: last_week_contents
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
                new Chart(contents_count, contents_config);
            }

            var admins_count = document.getElementById("admins_count");
            if (admins_count !== null) {
                var admins_config = {
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
                                data: this_week_admins
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
                                data: last_week_admins
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
                new Chart(admins_count, admins_config);
            }

        },
        error: function (data) {
            swal("خطا!", "خطایی در دریافت اطلاعات داشبورد رخ داده است.", "error");
        },

    });

});
