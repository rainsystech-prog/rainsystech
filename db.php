<?php
$conn = new mysqli("localhost", "root", "", "rainsystech");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>