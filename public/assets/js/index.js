$((function() {
    "use strict";
    var e = {
        series: [{
            name: "KUPEN UMUM",
            data: [10,20,30,40,50,60,70,80,90,100,110,120]
        }, {
            name: "KUPEN HYBRID",
            data: [5,10,15,20,25,20,25,30,50,55,65,75]
        }, {
            name: "KUPEG",
            data: [3,8,12,9,13,25,40,50,60,70,80,90]
        }, {
            name: "KPH",
            data: [2,4,6,8,10,30,50,60,70,80,60,100]
        }],
        chart: {
            height: 350,
            type: "area"
        },
        colors: ["#5066e0", "#00d48f","#fbb117","#fb1239"],
        dataLabels: {
            enabled: !1
        },
        stroke: {
            curve: "smooth"
        },
        xaxis: {
            categories: ["JAN", "FEB","MAR","APR","MEI","JUN","JUL", "AGT","SEP","OKT","NOV", "DES"],
            yAxisIndex: 0
        },
        yaxis: {
            labels: {
                formatter: function(e, r) {
                    return Math.round(e)
                }
            }
        },
        grid: {
            show: !0,
            borderColor: "rgba(224, 227, 247, 0.5)",
            strokeDashArray: 0
        },
        legend: {
            show: !1
        },
        markers: {
            size: 0,
            style: "hollow"
        }
    };
    new ApexCharts(document.querySelector("#chart"), e).render();


    var r = {
        series: [76, 67, 61, 90],
        chart: {
            height: 350,
            type: "radialBar"
        },
        plotOptions: {
            radialBar: {
                offsetY: 0,
                startAngle: -120,
                endAngle: 120,
                hollow: {
                    margin: 10,
                    size: "50%",
                    background: "transparent",
                    image: void 0
                },
                track: {
                    show: !0,
                    startAngle: void 0,
                    endAngle: void 0,
                    background: "#dce0f8",
                    strokeWidth: "50%",
                    opacity: 1,
                    margin: 5
                },
                dataLabels: {
                    name: {
                        show: !1
                    },
                    value: {
                        show: !1
                    }
                }
            }
        },
        stroke: {
            lineCap: "round"
        },
        colors: ["#5066e0", "#00d48f", "#ff8c00", "#2d97ff"],
        labels: ["Travel", "Presentation", "Business", "Others"],
        legend: {
            show: !1,
            floating: !0,
            fontSize: "16px",
            position: "left",
            offsetX: 10,
            offsetY: 15,
            labels: {
                useSeriesColors: !0
            },
            markers: {
                size: 0
            },
            itemMargin: {
                vertical: 3
            }
        },
        responsive: [{
            breakpoint: 480,
            options2: {
                legend: {
                    show: !1
                }
            }
        }]
    };
    new ApexCharts(document.querySelector("#chart2"), r).render()
}));