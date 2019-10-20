/**
 * Referrers Pie Chart Component
 */

import Chart from 'chart.js';

document.addEventListener('DOMContentLoaded', function() {
    let referrersPieChart = document.getElementById('referrersPieChart');

    new Chart(referrersPieChart, {
        type: 'pie',
        data: {
            labels: JSON.parse(referrersPieChart.dataset.keys),
            datasets: [{
                data: JSON.parse(referrersPieChart.dataset.values),
                backgroundColor: '#007bff'
            }]
        },
        options: {
            legend: {
                position: 'left',
            }
        }
    });
});