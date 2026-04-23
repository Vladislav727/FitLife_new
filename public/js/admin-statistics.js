document.addEventListener('DOMContentLoaded', () => {
    const userChartCanvas = document.getElementById('userChart');
    const postChartCanvas = document.getElementById('postChart');

    if (!userChartCanvas || !postChartCanvas || typeof Chart === 'undefined') {
        return;
    }

    const userStats = JSON.parse(userChartCanvas.dataset.userStats || '[]');
    const postStats = JSON.parse(postChartCanvas.dataset.postStats || '[]');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    new Chart(userChartCanvas, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'New Users',
                data: userStats.map((stat) => stat.count),
                backgroundColor: 'rgba(0, 255, 0, 0.5)',
                borderColor: 'rgba(0, 255, 0, 1)',
                borderWidth: 1,
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                },
            },
        },
    });

    new Chart(postChartCanvas, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'New Posts',
                data: postStats.map((stat) => stat.count),
                backgroundColor: 'rgba(0, 255, 0, 0.5)',
                borderColor: 'rgba(0, 255, 0, 1)',
                borderWidth: 1,
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                },
            },
        },
    });
});