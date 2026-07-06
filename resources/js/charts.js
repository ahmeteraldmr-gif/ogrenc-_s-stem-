import {
    Chart,
    LineController,
    BarController,
    PieController,
    DoughnutController,
    LineElement,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js';

// Register Chart.js components
Chart.register(
    LineController,
    BarController,
    PieController,
    DoughnutController,
    LineElement,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

// Initialize charts
window.initExamCharts = function(chartData) {
    // Destroy existing charts
    if (window.examCharts) {
        Object.values(window.examCharts).forEach(chart => {
            if (chart) chart.destroy();
        });
    }
    window.examCharts = {};
    
    // Net Progress Chart (Line Chart) - TYT ve AYT ayrı göster
    const netCtx = document.getElementById('netProgressChart');
    if (netCtx && chartData.netProgress && chartData.netProgress.labels.length > 0) {
        const datasets = [];
        
        // Tüm denemeler (genel çizgi)
        if (chartData.netProgress.allData && chartData.netProgress.allData.length > 0) {
            datasets.push({
                label: 'Toplam Net',
                data: chartData.netProgress.allData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            });
        }
        
        // TYT denemeleri
        if (chartData.netProgress.tytData && chartData.netProgress.tytData.some(v => v !== null)) {
            datasets.push({
                label: 'TYT Net',
                data: chartData.netProgress.tytData,
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: false,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(16, 185, 129)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderDash: [5, 5]
            });
        }
        
        // AYT denemeleri
        if (chartData.netProgress.aytData && chartData.netProgress.aytData.some(v => v !== null)) {
            datasets.push({
                label: 'AYT Net',
                data: chartData.netProgress.aytData,
                borderColor: 'rgb(245, 158, 11)',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                fill: false,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(245, 158, 11)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderDash: [5, 5]
            });
        }
        
        if (datasets.length > 0) {
            window.examCharts.netProgress = new Chart(netCtx, {
                type: 'line',
                data: {
                    labels: chartData.netProgress.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' Net';
                                }
                            }
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }

    // Course Development Chart (Seçilen Denemeler)
    const courseDevCtx = document.getElementById('courseDevelopmentChart');
    if (courseDevCtx && chartData.courseDevelopment && chartData.courseDevelopment.labels.length > 0) {
        window.examCharts.courseDevelopment = new Chart(courseDevCtx, {
            type: 'bar',
            data: {
                labels: chartData.courseDevelopment.labels,
                datasets: [
                    {
                        label: chartData.courseDevelopment.firstExamLabel || 'İlk Deneme Net',
                        data: chartData.courseDevelopment.firstExam,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 2,
                        borderRadius: 4
                    },
                    {
                        label: chartData.courseDevelopment.secondExamLabel || 'Son Deneme Net',
                        data: chartData.courseDevelopment.secondExam,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 2,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y.toFixed(2);
                                return label + ': ' + value + ' Net';
                            },
                            afterLabel: function(context) {
                                if (context.datasetIndex === 1) {
                                    const firstValue = context.chart.data.datasets[0].data[context.dataIndex];
                                    const lastValue = context.parsed.y;
                                    const improvement = lastValue - firstValue;
                                    return 'Gelişim: ' + (improvement >= 0 ? '+' : '') + improvement.toFixed(2) + ' Net';
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Course Performance Chart (Bar Chart) - Ortalama
    const courseCtx = document.getElementById('coursePerformanceChart');
    if (courseCtx && chartData.coursePerformance && chartData.coursePerformance.labels.length > 0) {
        window.examCharts.coursePerformance = new Chart(courseCtx, {
                type: 'bar',
                data: {
                    labels: chartData.coursePerformance.labels,
                    datasets: [{
                        label: 'Ortalama Net',
                        data: chartData.coursePerformance.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)',
                            'rgb(139, 92, 246)',
                            'rgb(236, 72, 153)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

    // Field Distribution Chart (Pie Chart)
    const fieldCtx = document.getElementById('fieldDistributionChart');
    if (fieldCtx && chartData.fieldDistribution && chartData.fieldDistribution.labels.length > 0) {
        window.examCharts.fieldDistribution = new Chart(fieldCtx, {
                type: 'pie',
                data: {
                    labels: chartData.fieldDistribution.labels,
                    datasets: [{
                        data: chartData.fieldDistribution.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' deneme (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
        
    // Exam Type Distribution Chart (Doughnut Chart)
    const examTypeCtx = document.getElementById('examTypeDistributionChart');
    if (examTypeCtx && chartData.examTypeDistribution && chartData.examTypeDistribution.labels.length > 0) {
        window.examCharts.examTypeDistribution = new Chart(examTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.examTypeDistribution.labels,
                    datasets: [{
                        data: chartData.examTypeDistribution.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' deneme (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
        
    // Monthly Average Chart (Bar Chart)
    const monthlyCtx = document.getElementById('monthlyAverageChart');
    if (monthlyCtx && chartData.monthlyAverage && chartData.monthlyAverage.labels.length > 0) {
        window.examCharts.monthlyAverage = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: chartData.monthlyAverage.labels,
                    datasets: [{
                        label: 'Ortalama Net',
                        data: chartData.monthlyAverage.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Ortalama Net: ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
};

// Export for use in other modules
export { Chart };
