import './bootstrap';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';

window.Alpine = Alpine;
window.flatpickr = flatpickr;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-flatpickr]').forEach((element) => {
        flatpickr(element, {
            dateFormat: 'Y-m-d',
        });
    });

    const chartElement = document.getElementById('dashboardTrendChart');

    if (chartElement) {
        const labels = JSON.parse(chartElement.dataset.labels || '[]');
        const income = JSON.parse(chartElement.dataset.income || '[]');
        const expense = JSON.parse(chartElement.dataset.expense || '[]');

        import('apexcharts').then(({ default: ApexCharts }) => {
            const chart = new ApexCharts(chartElement, {
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'Outfit, sans-serif',
                },
                colors: ['#405cf5', '#ea580c'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                series: [
                    { name: 'Income', data: income },
                    { name: 'Expense', data: expense },
                ],
                xaxis: { categories: labels },
                yaxis: {
                    labels: {
                        formatter: (value) => `NPR ${Number(value).toLocaleString()}`,
                    },
                },
                legend: { position: 'top' },
                grid: { borderColor: '#e2e8f0' },
                fill: {
                    type: 'gradient',
                    gradient: { opacityFrom: 0.28, opacityTo: 0.03 },
                },
            });

            chart.render();
        });
    }
});
