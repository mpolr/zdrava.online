import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", () => {
    // Получаем элемент с данными для графика
    const chartDataElement = document.getElementById("chartData");
    const chartTypeElement = document.getElementById("chartType");

    if (chartDataElement) {
        const chartData = JSON.parse(chartDataElement.textContent);

        // Получаем тип графика, если передан
        const chartType = chartTypeElement ? JSON.parse(chartTypeElement.textContent) : 'bar';

        // Получаем canvas элемент
        const canvas = document.getElementById("chart");

        if (canvas) {
            const ctx = canvas.getContext("2d");

            // Определяем массив данных для графика
            let datasets = [];

            if (chartData.values) {
                // Для графиков с одним массивом данных (например, количество тренировок)
                datasets.push({
                    label: "Количество тренировок",
                    data: chartData.values,
                    backgroundColor: "rgba(208, 56, 1, 0.4)",
                    borderColor: "rgba(208, 56, 1, 1)",
                    borderWidth: 1,
                });
            } else if (
                chartData.speed ||
                chartData.power ||
                chartData.heart_rate ||
                chartData.cadence ||
                chartData.altitude ||
                chartData.accuracy
            ) {
                // Для графиков с несколькими массивами данных
                if (chartData.speed) {
                    datasets.push({
                        label: "Скорость",
                        data: chartData.speed,
                        backgroundColor: "rgba(54, 162, 235, 0.4)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 0,
                    });
                }
                if (chartData.power) {
                    datasets.push({
                        label: "Мощность",
                        data: chartData.power,
                        backgroundColor: "rgba(100, 0, 100, 0.4)",
                        borderColor: "rgb(100,0,100)",
                        borderWidth: 1,
                    });
                }
                if (chartData.heart_rate) {
                    datasets.push({
                        label: "Пульс",
                        data: chartData.heart_rate,
                        backgroundColor: "rgba(255, 0, 0, 0.4)",
                        borderColor: "rgba(255, 0, 0, 1)",
                        borderWidth: 0,
                    });
                }
                if (chartData.cadence) {
                    datasets.push({
                        label: "Каденс",
                        data: chartData.cadence,
                        backgroundColor: "rgba(255, 0, 234, 0.4)",
                        borderColor: "rgb(255,0,234)",
                        borderWidth: 0,
                    });
                }
                if (chartData.altitude) {
                    datasets.push({
                        label: "Высота",
                        data: chartData.altitude,
                        backgroundColor: "rgba(150, 150, 150, 0.4)",
                        borderColor: "rgb(150,150,150)",
                        borderWidth: 0,
                    });
                }
                if (chartData.accuracy) {
                    datasets.push({
                        label: "Точность GPS",
                        data: chartData.accuracy,
                        backgroundColor: "rgba(0, 200, 0, 0.4)",
                        borderColor: "rgba(0, 200, 0, 1)",
                        borderWidth: 0,
                    });
                }
            }

            // Инициализация графика
            new Chart(ctx, {
                type: chartType, // Используем переданный тип графика
                data: {
                    labels: chartData.labels,
                    datasets: datasets,
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                        },
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
    }
});
