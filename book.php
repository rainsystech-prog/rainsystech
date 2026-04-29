<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$message = "";

// Database connection
$conn = new mysqli("localhost", "root", "", "rainsystech");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $service = $_POST['service'];
    $appt_date = $_POST['date']; // HTML date input
    $appt_time = $_POST['time']; // HTML time input
    $device = $_POST['device'];
    $issue = $_POST['issue'];
    
    $full_datetime = $appt_date . ' ' . $appt_time . ':00';

    $stmt = $conn->prepare("INSERT INTO appointments (username, service, appointment_date, device, issue, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssss", $username, $service, $full_datetime, $device, $issue);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success shadow-sm'>Booking confirmed! Redirecting to dashboard...</div>";
        echo "<script>setTimeout(() => { window.location.href = 'dashboard.php'; }, 2000);</script>";
    } else {
        $message = "<div class='alert alert-danger shadow-sm'>Error: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment - Rain Systems</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="book.css">
  <style>
      body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }
      .book-form-card { border-radius: 15px; border: none; }
      .btn-primary { background-color: #0d6efd; border: none; }
  </style>
</head>

<body class="book-page">
  <div class="container py-5">
    <div class="book-header text-center mb-5">
      <h1 class="fw-bold">Book Your Service</h1>
      <p class="text-muted">Fill in the details below to schedule your repair</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card book-form-card shadow-lg p-4">
          
          <?php echo $message; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label fw-bold" style="color: white">Service Required</label>
              <select name="service" class="form-select" required>
                <option value="">Choose a service...</option>
                <option value="Laptop Repair">Laptop Repair</option>
                <option value="SSD Upgrade">SSD Upgrade</option>
                <option value="System Cleaning">System Cleaning</option>
                <option value="OS Reinstallation">OS Reinstallation</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold" style="color: white">Device Model</label>
              <input type="text" name="device" class="form-control" placeholder="e.g. ASUS ROG Zephyrus G14" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold" style="color: white">Preferred Date</label>
                <input type="date" name="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold" style="color: white">Preferred Time</label>
                <input type="time" name="time" class="form-control" required>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold" style="color: white">Problem/Issue Details</label>
              <textarea name="issue" class="form-control" rows="3" placeholder="Briefly describe what's wrong with your device..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
              Confirm Appointment
            </button>
            
            <div class="text-center mt-3">
              <a href="dashboard.php" class="text-muted small text-decoration-none">← Back to Dashboard</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>