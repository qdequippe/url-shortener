/**
 * Visits Chart Component
 */

import Chart from 'chart.js';

document.addEventListener("DOMContentLoaded", function() {
    let visitsPerPeriodChart = document.getElementById('visitsPerPeriodChart');

    new Chart(visitsPerPeriodChart, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
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