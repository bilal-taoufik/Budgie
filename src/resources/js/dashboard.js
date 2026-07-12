import Chart from 'chart.js/auto';

window.addEventListener('load', () => {
    // Récupère l'élément qui contient les données JSON
    const dataElement = document.getElementById('dashboard-chart-data');
    if (!dataElement) return;

    // Parse les données JSON
    const data = JSON.parse(dataElement.textContent);

    // Fonction utilitaire pour créer un graphique
    const createChart = (elementId, type, chartData, customOptions = {}) => {
        new Chart(document.getElementById(elementId), {
            type, // Type du graphique (line, doughnut, bar)
            data: chartData, // Données à afficher
            options: {
                responsive: true, // Adapte la taille à l'écran
                maintainAspectRatio: false, // Respecte la hauteur du conteneur
                ...customOptions, // Options personnalisées
            },
        });
    };

    // Graphique en ligne pour l'évolution du solde
    createChart('balanceChart', 'line', {
        labels: data.etiquettes, // Mois ou dates
        datasets: [{ label: 'Solde', data: data.evolutionSolde }], // Valeurs du solde
    });

    // Graphique en donut pour les dépenses
    createChart('depensePieChart', 'doughnut', {
        labels: data.etiquettesDepenses, // Catégories de dépenses
        datasets: [{ label: 'Depenses', data: data.donneesDepenses }], // Montants par catégorie
    }, {
        plugins: { legend: { position: 'bottom' } }, // Légende en bas
    });

    // Graphique en barres comparant revenus et dépenses
    createChart('revenuDepenseChart', 'bar', {
        labels: data.etiquettes, // Mois ou dates
        datasets: [
            { label: 'Revenus', data: data.revenusMensuels }, // Montants des revenus
            { label: 'Depenses', data: data.depensesMensuelles }, // Montants des dépenses
        ],
    });
});