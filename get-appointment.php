<?php
session_start();
header('Content-Type: application/json');
require '
db.php';

if (!isset($_SESSION['username']) || !isset($_GET['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$appt = $result->fetch_assoc();

if ($appt) {
    echo json_encode(['success' => true, 'appointment' => $appt]);
} else {
    echo json_encode(['success' => false]);
}
?>