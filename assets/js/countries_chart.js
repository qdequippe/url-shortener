/**
 * Countries Pie Chart Component
 */

import Chart from 'chart.js';

document.addEventListener('DOMContentLoaded', function() {
    let countriesPieChart = document.getElementById('countriesPieChart');

    new Chart(countriesPieChart, {
        type: 'pie',
        data: {
            labels: JSON.parse(countriesPieChart.dataset.keys),
            datasets: [{
                data: JSON.parse(countriesPieChart.dataset.values),
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