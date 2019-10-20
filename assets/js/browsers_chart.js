/**
 * Browsers Chart Component
 */

import Chart from 'chart.js';

document.addEventListener('DOMContentLoaded', function() {
    let browsersChart = document.getElementById('browsersChart');

    new Chart(browsersChart, {
        type: 'horizontalBar',
        data: {
            labels: JSON.parse(browsersChart.dataset.keys),
            datasets: [{
                data: JSON.parse(browsersChart.dataset.values),
                backgroundColor: '#007bff'
            }]
        },
        options: {
            legend: {
                display: false,
            }
        }
    });
});