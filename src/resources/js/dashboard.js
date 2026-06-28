window.addEventListener('load', () => {
    // Les donnees viennent du dashboard.blade.php
    const data = window.dashboardCharts;

    if (!window.Chart || !data) {
        return;
    }

    const euros = (value) => new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
    }).format(value);

    // Graphique 1 : evolution du solde
    new Chart(document.getElementById('balanceChart'), {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Solde',
                    data: data.balanceEvolution,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    fill: true,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => euros(context.raw),
                    },
                },
            },
        },
    });

    // Graphique 2 : repartition des depenses
    new Chart(document.getElementById('depensePieChart'), {
        type: 'doughnut',
        data: {
            labels: data.depenseLabels,
            datasets: [
                {
                    label: 'Depenses',
                    data: data.depenseData,
                    backgroundColor: [
                        '#2563eb',
                        '#059669',
                        '#dc2626',
                        '#d97706',
                        '#7c3aed',
                        '#0891b2',
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.label}: ${euros(context.raw)}`,
                    },
                },
            },
        },
    });

    // Graphique 3 : revenus vs depenses
    new Chart(document.getElementById('revenuDepenseChart'), {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Revenus',
                    data: data.monthlyRevenus,
                    backgroundColor: '#059669',
                },
                {
                    label: 'Depenses',
                    data: data.monthlyDepenses,
                    backgroundColor: '#dc2626',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${euros(context.raw)}`,
                    },
                },
            },
        },
    });
});
