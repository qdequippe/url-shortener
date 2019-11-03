/**
 * OS Chart Component
 */

import Chart from 'chart.js';

document.addEventListener('DOMContentLoaded', function() {
    let osChart = document.getElementById('osChart');

    new Chart(osChart, {
        type: 'horizontalBar',
        data: {
            labels: JSON.parse(osChart.dataset.keys),
            datasets: [{
                data: JSON.parse(osChart.dataset.values),
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