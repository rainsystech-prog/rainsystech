<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) { header("Location: login.php"); exit; }
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM appointments WHERE username = ? ORDER BY appointment_date DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$statusClasses = [
    'pending' => 'status-pending',
    'confirmed' => 'status-confirmed',
    'in-progress' => 'status-info',
    'completed' => 'status-completed'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Appointments - Rain Systems</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="manage-page bg-dark text-white">
  <div class="container py-5">
    <div class="manage-header mb-4">
      <h1>Your Appointments</h1>
      <a href="dashboard.php" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
    </div>

    <div class="table-responsive">
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <th>Service</th>
            <th>Device</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><strong><?php echo htmlspecialchars($row['service']); ?></strong></td>
            <td><?php echo htmlspecialchars($row['device']); ?></td>
            <td><?php echo date('M d, Y | h:i A', strtotime($row['appointment_date'])); ?></td>
            <td>
                <span class="badge <?php echo $statusClasses[$row['status']] ?? 'bg-secondary'; ?>">
                    <?php echo strtoupper($row['status']); ?>
                </span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>