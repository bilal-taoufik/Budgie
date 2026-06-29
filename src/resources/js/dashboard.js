import Chart from 'chart.js/auto';

window.addEventListener('load', () => {
    const dataElement = document.getElementById('dashboard-chart-data');

    if (!dataElement) {
        return;
    }

    const data = JSON.parse(dataElement.textContent);

    const options = {
        responsive: true,
        maintainAspectRatio: false,
    };

    const depenseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            },
        },
    };

    // Graphique du solde
    new Chart(document.getElementById('balanceChart'), {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Solde',
                data: data.balanceEvolution,
            }],
        },
        options: options,
    });

    // Graphique des depenses
    new Chart(document.getElementById('depensePieChart'), {
        type: 'doughnut',
        data: {
            labels: data.depenseLabels,
            datasets: [{
                label: 'Depenses',
                data: data.depenseData,
            }],
        },
        options: depenseOptions,
    });

    // Graphique revenus / depenses
    new Chart(document.getElementById('revenuDepenseChart'), {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Revenus',
                    data: data.monthlyRevenus,
                },
                {
                    label: 'Depenses',
                    data: data.monthlyDepenses,
                },
            ],
        },
        options: options,
    });
});