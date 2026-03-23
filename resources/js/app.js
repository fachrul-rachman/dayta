import Chart from 'chart.js/auto';

window.Chart = Chart;

function initCharts() {
    if (!window.Chart) {
        return;
    }

    document.querySelectorAll('canvas[data-chart-type]').forEach((canvas) => {
        const type = canvas.dataset.chartType;
        // Avoid double-initializing on the same canvas
        if (canvas.dataset.chartInitialised === 'true') {
            return;
        }

        const ctx = canvas.getContext('2d');
        if (!ctx) {
            return;
        }

        if (type && type.startsWith('pie')) {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const values = JSON.parse(canvas.dataset.values || '[]');

            if (!labels.length || !values.length) {
                return;
            }

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#4f46e5',
                            '#22c55e',
                            '#f97316',
                            '#0ea5e9',
                            '#e11d48',
                            '#a855f7',
                        ],
                    }],
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        } else if (type === 'line-submissions') {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const values = JSON.parse(canvas.dataset.values || '[]');

            if (!labels.length || !values.length) {
                return;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Daily submissions',
                        data: values,
                        tension: 0.3,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.1)',
                        fill: true,
                        pointRadius: 3,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            ticks: { font: { size: 10 } },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 10 },
                            },
                        },
                    },
                },
            });
        } else if (type === 'line-company-submissions') {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const submitted = JSON.parse(canvas.dataset.submitted || '[]');
            const missing = JSON.parse(canvas.dataset.missing || '[]');

            if (!labels.length) {
                return;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Submitted',
                            data: submitted,
                            tension: 0.3,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34,197,94,0.1)',
                            fill: true,
                            pointRadius: 2,
                        },
                        {
                            label: 'Missing',
                            data: missing,
                            tension: 0.3,
                            borderColor: '#e11d48',
                            backgroundColor: 'rgba(225,29,72,0.1)',
                            fill: true,
                            pointRadius: 2,
                        },
                    ],
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { ticks: { font: { size: 10 } } },
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0, font: { size: 10 } },
                        },
                    },
                },
            });
        } else if (type === 'bar-flags-trend') {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const low = JSON.parse(canvas.dataset.low || '[]');
            const medium = JSON.parse(canvas.dataset.medium || '[]');
            const high = JSON.parse(canvas.dataset.high || '[]');

            if (!labels.length || !low.length) {
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Low',
                            data: low,
                            backgroundColor: '#22c55e',
                        },
                        {
                            label: 'Medium',
                            data: medium,
                            backgroundColor: '#f97316',
                        },
                        {
                            label: 'High',
                            data: high,
                            backgroundColor: '#e11d48',
                        },
                    ],
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { stacked: true, ticks: { font: { size: 10 } } },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0, font: { size: 10 } },
                        },
                    },
                },
            });
        } else if (type === 'bar-division-comparison') {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const flags = JSON.parse(canvas.dataset.flags || '[]');
            const rates = JSON.parse(canvas.dataset.rates || '[]');

            if (!labels.length || !flags.length) {
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Flags',
                            data: flags,
                            backgroundColor: '#e11d48',
                        },
                        {
                            label: 'Submission %',
                            data: rates,
                            backgroundColor: '#4f46e5',
                        },
                    ],
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { ticks: { font: { size: 10 } } },
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 10 } },
                        },
                    },
                },
            });
        } else if (type === 'pie-division-workload') {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const values = JSON.parse(canvas.dataset.values || '[]');

            if (!labels.length || !values.length) {
                return;
            }

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#4f46e5',
                            '#22c55e',
                            '#f97316',
                        ],
                    }],
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        } else if (type === 'bar-division-alignment') {
            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const withBigRock = JSON.parse(canvas.dataset.withbigrock || '[]');
            const withoutBigRock = JSON.parse(canvas.dataset.withoutbigrock || '[]');

            if (!labels.length || !withBigRock.length) {
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Aligned to Big Rocks',
                            data: withBigRock,
                            backgroundColor: '#22c55e',
                        },
                        {
                            label: 'Not Linked',
                            data: withoutBigRock,
                            backgroundColor: '#6b7280',
                        },
                    ],
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { ticks: { font: { size: 10 } } },
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0, font: { size: 10 } },
                        },
                    },
                },
            });
        }

        canvas.dataset.chartInitialised = 'true';
    });
}

document.addEventListener('DOMContentLoaded', () => initCharts());
document.addEventListener('livewire:navigated', () => initCharts());

document.addEventListener('livewire:init', () => {
    if (window.Livewire && typeof window.Livewire.hook === 'function') {
        window.Livewire.hook('message.processed', () => {
            initCharts();
        });
    }

    initCharts();
});

window.addEventListener('init-charts', () => {
    initCharts();
});
