<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit; }
$username = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "rainsystech");

$sql = "SELECT * FROM appointments WHERE username = ? ORDER BY created_at DESC LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$appointments = $stmt->get_result();

$statusMap = [
    'confirmed'   => 'bg-success shadow-success',
    'pending'     => 'bg-warning text-dark shadow-warning',
    'in-progress' => 'bg-info text-dark shadow-info',
    'completed'   => 'bg-primary shadow-primary',
    'cancelled'   => 'bg-danger shadow-danger'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Rain Systems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #181a29 0%, #23263a 50%, #2c2f4a 100%);
            min-height: 100vh; font-family: 'Montserrat', sans-serif; color: #fff;
        }
        .navbar { background: rgba(24, 26, 41, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .appointment-card {
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px; transition: 0.3s;
        }
        .appointment-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.08); }
        .badge { font-weight: 600; letter-spacing: 0.5px; border-radius: 6px; }
        .shadow-primary { box-shadow: 0 0 10px rgba(13, 110, 253, 0.4); }
        .action-card {
            background: rgba(13, 110, 253, 0.05); border: 1px solid rgba(13, 110, 253, 0.2);
            border-radius: 15px; padding: 30px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark px-4 py-3">
    <span class="navbar-brand fw-bold text-primary">RAIN SYSTEMS</span>
    <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
</nav>

<div class="container mt-5">
    <div class="mb-5">
        <h2 class="fw-bold">Welcome, <?php echo htmlspecialchars($username); ?></h2>
        <p class="text-muted">Track your repair status in real-time below.</p>
    </div>

    <div class="row g-4">
        <?php if ($appointments->num_rows > 0): ?>
            <?php while($appt = $appointments->fetch_assoc()): 
                $badgeClass = $statusMap[$appt['status']] ?? 'bg-secondary';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="appointment-card p-4 h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold m-0"><?php echo htmlspecialchars($appt['service']); ?></h5>
                        <span class="badge <?php echo $badgeClass; ?>"><?php echo strtoupper($appt['status']); ?></span>
                    </div>
                    <p class="small opacity-75 mb-2"><i class="fas fa-laptop me-2 text-primary"></i><?php echo htmlspecialchars($appt['device']); ?></p>
                    <p class="small opacity-50"><i class="fas fa-clock me-2"></i><?php echo date('M d, Y', strtotime($appt['appointment_date'])); ?></p>
                    <button class="btn btn-sm btn-outline-primary w-100 mt-3 view-details-btn" data-id="<?php echo $appt['id']; ?>" data-bs-toggle="modal" data-bs-target="#appointmentModal">View Full Details</button>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 opacity-50">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p>No appointments found.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="row mt-5 g-4 text-center">
        <div class="col-md-6"><div class="action-card"><h4>New Repair</h4><a href="book.php" class="btn btn-primary mt-3 w-100">Schedule Now</a></div></div>
        <div class="col-md-6"><div class="action-card"><h4>History</h4><a href="manage.php" class="btn btn-outline-light mt-3 w-100">View All</a></div></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>