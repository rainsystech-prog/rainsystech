// Admin Dashboard - PERFECT 3-CHART IMPLEMENTATION
document.addEventListener("DOMContentLoaded", function() {

    // Initialize the exact 3 charts as specified
    initSystemOverviewChart();  // Chart 1
    initStatusChart();          // Chart 2  
    initServicePopularityChart();// Chart 3 ⭐
    
    // Additional interactive features
    initInteractiveFeatures();
});

// 📊 CHART 1: System Overview (Bar) - Total Clients vs Total Appointments
function initSystemOverviewChart() {
    const data = window.dashboardData;
    const ctx = document.getElementById('overviewChart');
    
    if (!ctx || !data) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Clients', 'Total Appointments'],
            datasets: [{
                label: 'System Overview',
                data: [data.totalClients || 0, data.totalAppointments || 0],
                backgroundColor: [
                    '#3b82f6',  // Blue for Clients
                    '#10b981'   // Emerald for Appointments
                ],
                borderColor: [
                    '#1d4ed8',
                    '#059669'
                ],
                borderWidth: 2,
                borderRadius: 12,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'System Overview',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#e5e7eb',
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.9)',
                    titleColor: '#e5e7eb',
                    bodyColor: '#e5e7eb',
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.y.toLocaleString()}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)'
                    }
                },
                x: {
                    ticks: {
                        color: '#e5e7eb',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
}

// 📊 CHART 2: Appointment Status (Bar) - 5 Status Categories
function initStatusChart() {
    const data = window.dashboardData;
    const ctx = document.getElementById('statusChart');
    
    if (!ctx || !data) return;

    const statusData = [
        data.statusCounts.pending || 0,
        data.statusCounts.confirmed || 0,
        data.statusCounts['in-progress'] || 0,
        data.statusCounts.completed || 0,
        data.statusCounts.cancelled || 0
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Confirmed', 'In-progress', 'Completed', 'Cancelled'],
            datasets: [{
                label: 'Appointment Status',
                data: statusData,
                backgroundColor: [
                    '#f59e0b',  // Amber (Pending)
                    '#10b981',  // Emerald (Confirmed)
                    '#3b82f6',  // Blue (In-progress)
                    '#8b5cf6',  // Violet (Completed)
                    '#ef4444'   // Red (Cancelled)
                ],
                borderColor: [
                    '#d97706',
                    '#059669', 
                    '#1d4ed8',
                    '#7c3aed',
                    '#dc2626'
                ],
                borderWidth: 2,
                borderRadius: 12,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Appointment Status',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#e5e7eb',
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.9)',
                    titleColor: '#e5e7eb',
                    bodyColor: '#e5e7eb',
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                            return `${context.dataset.label}: ${context.parsed.y} (${percentage}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)'
                    }
                },
                x: {
                    ticks: {
                        color: '#e5e7eb',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart',
                delay: 200
            }
        }
    });
}

// 📊 CHART 3: Service Popularity (Bar) ⭐ EXTRA POINTS
function initServicePopularityChart() {
    const data = window.dashboardData;
    const ctx = document.getElementById('serviceChart');
    
    if (!ctx || !data.services || data.services.length === 0) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.services,
            datasets: [{
                label: 'Service Popularity',
                data: data.serviceData,
                backgroundColor: function(context) {
                    const gradientColors = [
                        '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', 
                        '#3b82f6', '#ef4444', '#06b6d4', '#f97316'
                    ];
                    return gradientColors[context.dataIndex % gradientColors.length];
                },
                borderColor: '#ffffff',
                borderWidth: 2,
                borderRadius: 12,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Service Popularity ⭐',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#e5e7eb',
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.9)',
                    titleColor: '#e5e7eb',
                    bodyColor: '#e5e7eb',
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.y} appointments`;
                        },
                        afterLabel: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                            return `(${percentage}% of total)`;
                        }
                    }
                },
                datalabels: {
                    display: function(context) {
                        return context.parsed.y > 0;
                    },
                    color: '#ffffff',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        return value > 0 ? value : '';
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9ca3af',
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)'
                    }
                },
                x: {
                    ticks: {
                        color: '#e5e7eb',
                        maxRotation: 45,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutBounce',
                delay: 400
            }
        },
        plugins: [ChartDataLabels] // Requires chartjs-plugin-datalabels
    });
}

// ✨ Additional Interactive Features
function initInteractiveFeatures() {
    // Hover effects on stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'all 0.3s ease';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Smooth animations for table rows
    document.querySelectorAll('tbody tr').forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.6s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, 100);
    });
}

// 🔔 Notification system (unchanged but improved)
function showNotification(message, type = 'success') {
    const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
    const icon = type === 'error' ? 'bi-x-circle' : 'bi-check-circle';
    
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    
    toast.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show shadow-lg" role="alert">
            <i class="bi ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}