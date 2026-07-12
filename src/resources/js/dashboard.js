import Chart from 'chart.js/auto';

window.addEventListener('load', () => {
    const dataElement = document.getElementById('dashboard-chart-data');
    if (!dataElement) return;

    const data = JSON.parse(dataElement.textContent);

    const createChart = (elementId, type, chartData, customOptions = {}) => {
        new Chart(document.getElementById(elementId), {
            type,
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                ...customOptions,
            },
        });
    };

    // Graphique du solde
    createChart('balanceChart', 'line', {
        labels: data.etiquettes,
        datasets: [{ label: 'Solde', data: data.evolutionSolde }],
    });

    // Graphique des dépenses
    createChart('depensePieChart', 'doughnut', {
        labels: data.etiquettesDepenses,
        datasets: [{ label: 'Depenses', data: data.donneesDepenses }],
    }, {
        plugins: { legend: { position: 'bottom' } },
    });

    // Graphique revenus/dépenses
    createChart('revenuDepenseChart', 'bar', {
        labels: data.etiquettes,
        datasets: [
            { label: 'Revenus', data: data.revenusMensuels },
            { label: 'Depenses', data: data.depensesMensuelles },
        ],
    });
});