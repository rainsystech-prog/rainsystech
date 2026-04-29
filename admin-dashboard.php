<?php
require 'db.php';

// UPDATE STATUS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appt_id'])) {
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['status'], $_POST['appt_id']);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit;
}

// DELETE CLIENT
if (isset($_POST['delete_user'])) {
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->bind_param("i", $_POST['user_id']);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit;
}

// Fetch fresh data
$clients = $conn->query("SELECT * FROM clients ORDER BY id DESC");
$appointments = $conn->query("SELECT * FROM appointments ORDER BY id DESC");

// STATUS BADGES
function statusBadge($status) {
    return match($status) {
        'pending' => 'bg-warning text-dark fw-bold shadow-sm',
        'confirmed' => 'bg-success text-white fw-bold shadow-sm',
        'in-progress' => 'bg-info text-dark fw-bold shadow-sm',
        'completed' => 'bg-purple text-white fw-bold shadow-sm',
        'cancelled' => 'bg-danger text-white fw-bold shadow-sm',
        default => 'bg-secondary text-white fw-bold shadow-sm'
    };
}

// TOTALS
$totalClients = $clients ? $clients->num_rows : 0;
$totalAppointments = $appointments ? $appointments->num_rows : 0;

// STATUS COUNTS
$statusCounts = ['pending' => 0, 'confirmed' => 0, 'in-progress' => 0, 'completed' => 0, 'cancelled' => 0];
$result = $conn->query("SELECT status, COUNT(*) as total FROM appointments GROUP BY status");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $statusCounts[$row['status']] = $row['total'];
    }
}

// SERVICE COUNTS
$services = [];
$serviceData = [];
$result2 = $conn->query("SELECT service, COUNT(*) as total FROM appointments GROUP BY service ORDER BY total DESC");
if ($result2) {
    while ($row = $result2->fetch_assoc()) {
        $services[] = $row['service'];
        $serviceData[] = $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Rain Systems</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="admin.css">
    
</head>

<body>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Rain Systech - Admin Dashboard</span>
    </div>
</nav>

<div class="container py-4">
    <!-- STATS CARDS -->
    <div class="row mb-5 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white bg-primary h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?php echo $totalClients; ?></h1>
                    <p class="mb-0 fs-5 opacity-75">Total Clients</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white bg-success h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?php echo $totalAppointments; ?></h1>
                    <p class="mb-0 fs-5 opacity-75">Total Appointments</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white bg-warning h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?php echo $statusCounts['pending']; ?></h1>
                    <p class="mb-0 fs-5 opacity-75">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white bg-info h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?php echo $statusCounts['completed']; ?></h1>
                    <p class="mb-0 fs-5 opacity-75">Completed</p>
                </div>
            </div>
        </div>



    <!-- CLIENTS SECTION -->
    <div class="card section-card mb-5">
        <div class="card-body">
            <h3 class="card-title mb-4" style="color: white"><i class="bi bi-people me-2"></i>Clients 
                <span class="badge bg-primary fs-6" ><?php echo $totalClients; ?></span>
            </h3>
            <?php if ($clients && $clients->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-dark mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($c = $clients->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $c['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars(($c['first_name'] ?? '').' '.($c['last_name'] ?? '')); ?></td>
                                <td><code><?php echo htmlspecialchars($c['username']); ?></code></td>
                                <td>
                                    <form method="POST" style="display:inline;" 
                                          onsubmit="return confirm('🗑️ Delete <?php echo addslashes($c['username']); ?>?')">
                                        <input type="hidden" name="user_id" value="<?php echo $c['id']; ?>">
                                        <button type="submit" name="delete_user" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div style="font-size: 5rem; opacity: 0.3; margin-bottom: 1rem;">👥</div>
                    <h4>No Clients Yet</h4>
                    <p class="mb-0 opacity-75">Clients will appear here after they register.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- APPOINTMENTS SECTION -->
    <?php if ($appointments && $appointments->num_rows > 0): ?>
    <div class="card section-card mb-5">
        <div class="card-body">
            <h3 class="card-title mb-4" style="color: white"><i class="bi bi-calendar3 me-2"></i>Appointments 
                <span class="badge bg-success fs-6"><?php echo $totalAppointments; ?></span>
            </h3>
            <div class="table-responsive">
                <table class="table table-hover table-dark mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Quick Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $appointments->data_seek(0); while($a = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo $a['id']; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($a['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($a['service']); ?></td>
                            <td>
                                <span class="badge <?php echo statusBadge($a['status']); ?> px-3 py-2">
                                    <?php echo ucfirst(str_replace('-', ' ', $a['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="appt_id" value="<?php echo $a['id']; ?>">
                                    <select name="status" class="form-select form-select-sm" 
                                            onchange="this.form.submit()" style="width: 160px;">
                                        <option value="pending" <?php echo $a['status']=='pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                        <option value="confirmed" <?php echo $a['status']=='confirmed' ? 'selected' : ''; ?>>✅ Confirmed</option>
                                        <option value="in-progress" <?php echo $a['status']=='in-progress' ? 'selected' : ''; ?>>⚙️ In Progress</option>
                                        <option value="completed" <?php echo $a['status']=='completed' ? 'selected' : ''; ?>>🎉 Completed</option>
                                        <option value="cancelled" <?php echo $a['status']=='cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 🎯 PERFECT 3-CHART ANALYTICS DASHBOARD -->
    <div class="card section-card">
        <div class="card-body">
            <h3 class="card-title mb-4" style="color: white"><i class="bi bi-graph-up me-2"></i>Analytics Dashboard</h3>
            <div class="row g-4">
                <!-- Chart 1: System Overview -->
                <div class="col-lg-4 col-md-6">
                    <div class="chart-container">
                        <canvas id="overviewChart"></canvas>
                    </div>
                </div>
                <!-- Chart 2: Appointment Status -->
                <div class="col-lg-4 col-md-6">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <!-- Chart 3: Service Popularity ⭐ -->
                <div class="col-lg-4">
                    <div class="chart-container">
                        <canvas id="serviceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div class="text-center py-5">
        <div style="font-size: 5rem; opacity: 0.3; margin-bottom: 1rem;">📅</div>
        <h4>No Appointments</h4>
        <p class="mb-0 opacity-75">Bookings will appear here automatically.</p>
    </div>
    <?php endif; ?>
</div>

<!-- DASHBOARD DATA -->
<script>
window.dashboardData = <?php echo json_encode([
    'totalClients' => $totalClients,
    'totalAppointments' => $totalAppointments,
    'statusCounts' => $statusCounts,
    'services' => $services,
    'serviceData' => $serviceData
]); ?>;
</script>

<!-- PERFECT 3 CHARTS WITH PROPER HEADERS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    initPerfect3Charts();
});

function initPerfect3Charts() {
    const data = window.dashboardData;
    if (!data) {
        console.error('No dashboard data');
        return;
    }

    // ⭐ CHART 1: System Overview (Bar) ⭐
    const overviewCtx = document.getElementById('overviewChart');
    if (overviewCtx) {
        new Chart(overviewCtx, {
            type: 'bar',
            data: {
                labels: ['Total Clients', 'Total Appointments'],
                datasets: [{
                    data: [data.totalClients, data.totalAppointments],
                    backgroundColor: ['#3b82f6', '#10b981'],
                    borderRadius: 12,
                    borderSkipped: false,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'System Overview',
                        font: { size: 16, weight: 'bold' },
                        color: '#ffffff',
                        padding: { top: 10, bottom: 20 }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(255,255,255,0.1)' },
                        ticks: { color: '#9ca3af', font: { weight: '500' } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { weight: '600' } }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // ⭐ CHART 2: Appointment Status (Bar) ⭐
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Confirmed', 'In-progress', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        data.statusCounts.pending || 0,
                        data.statusCounts.confirmed || 0,
                        data.statusCounts['in-progress'] || 0,
                        data.statusCounts.completed || 0,
                        data.statusCounts.cancelled || 0
                    ],
                    backgroundColor: ['#f59e0b', '#10b981', '#0ea5e9', '#3b82f6', '#ef4444'],
                    borderRadius: 12,
                    borderSkipped: false,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Appointment Status',
                        font: { size: 16, weight: 'bold' },
                        color: '#ffffff',
                        padding: { top: 10, bottom: 20 }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(255,255,255,0.1)' },
                        ticks: { color: '#9ca3af', font: { weight: '500' } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { weight: '600' } }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // ⭐ CHART 3: Service Popularity (Bar) ⭐
    const serviceCtx = document.getElementById('serviceChart');
    if (serviceCtx && data.services && data.services.length > 0) {
        new Chart(serviceCtx, {
            type: 'bar',
            data: {
                labels: data.services,
                datasets: [{
                    label: 'Appointments',
                    data: data.serviceData,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 12,
                    borderSkipped: false,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Requested Services',
                        font: { size: 16, weight: 'bold' },
                        color: '#ffffff',
                        padding: { top: 10, bottom: 20 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${context.parsed.y} (${percentage}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(255,255,255,0.1)' },
                        ticks: { color: '#9ca3af', font: { weight: '500' } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { 
                            color: '#9ca3af', 
                            maxRotation: 45,
                            font: { weight: '600' }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                }
            }
        });
    } else if (serviceCtx) {
        // Show empty state if no services
        serviceCtx.parentElement.innerHTML = '<div class="text-center py-4"><i class="bi bi-star fs-1 text-muted mb-3"></i><h6>No Services Yet</h6></div>';
    }

    console.log('Perfect 3 charts with headers loaded!');
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>