const chartTextColor = "rgba(255,255,255,.55)";
const chartGridColor = "rgba(255,255,255,.08)";
const green = "#49b874";
const softGreen = "#85a795";
const barChart = document.getElementById("barChart");
const doughnutChart = document.getElementById("doughnutChart");
const lineChart = document.getElementById("lineChart");

if (typeof Chart === "undefined") {
    console.warn("Chart.js n'est pas chargé. Les graphiques du dashboard ne peuvent pas être initialisés.");
} else if (barChart && doughnutChart && lineChart) {
    Chart.defaults.color = chartTextColor;
    Chart.defaults.font.family = "Inter, sans-serif";

new Chart(barChart, {
    type: "bar",
    data: {
        labels: ["Nov", "Déc", "Jan", "Fév", "Mar", "Avr"],
        datasets: [
            {
                label: "Revenus",
                data: [4100, 3650, 3400, 3800, 4100, 3700],
                backgroundColor: green,
                borderRadius: 4
            },
            {
                label: "Dépenses",
                data: [1650, 1450, 1350, 1500, 1650, 1500],
                backgroundColor: softGreen,
                borderRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    color: chartGridColor
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: chartGridColor
                },
                ticks: {
                    callback: value => `${value}€`
                }
            }
        }
    }
});

new Chart(doughnutChart, {
    type: "doughnut",
    data: {
        labels: [
            "Loyer",
            "Courses",
            "Assurance auto",
            "Électricité",
            "Internet",
            "Abonnement sport",
            "Netflix + Spotify"
        ],
        datasets: [
            {
                data: [57, 25, 6, 5, 3, 2, 2],
                backgroundColor: [
                    "#85a795",
                    "#7ab48e",
                    "#36b96f",
                    "#3aa7d8",
                    "#e84a5f",
                    "#f4c542",
                    "#8ed1a6"
                ],
                borderColor: "#1c211f",
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "58%",
        plugins: {
            legend: {
                position: "right",
                labels: {
                    boxWidth: 12,
                    boxHeight: 12
                }
            }
        }
    }
});

new Chart(lineChart, {
    type: "line",
    data: {
        labels: ["Nov", "Déc", "Jan", "Fév", "Mar", "Avr"],
        datasets: [
            {
                label: "Solde total",
                data: [22500, 27500, 30500, 33000, 35500, 38000],
                borderColor: softGreen,
                backgroundColor: "rgba(133,167,149,.18)",
                fill: true,
                tension: .35,
                pointRadius: 0,
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    color: chartGridColor
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: chartGridColor
                },
                ticks: {
                    callback: value => `${value}€`
                }
            }
        }
    }
});
}
