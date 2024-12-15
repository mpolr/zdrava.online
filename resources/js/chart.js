import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("monthlyChart").getContext("2d");
    const chartDataElement = document.getElementById("chartData");

    if (chartDataElement) {
        const chartData = JSON.parse(chartDataElement.textContent);

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: "Количество тренировок",
                        data: chartData.values,
                        backgroundColor: "rgba(208, 56, 1, 0.4)",
                        borderColor: "rgba(208, 56, 1, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }
});
