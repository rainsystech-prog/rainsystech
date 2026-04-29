<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);  // ← Added trim
    $password = $_POST['password'];

    // Input validation
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=Username and password required");
        exit;
    }

    if (strlen($password) < 6) {
        header("Location: login.php?error=Password must be 6+ characters");
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM clients WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if password is hashed first
        if (password_get_info($user['password'])['algo'] !== 0) {
            // Hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                session_regenerate_id(true);  // ← Security bonus
                header("Location: dashboard.php");
                exit;
            }
        } else {
            // Legacy plaintext - TEMPORARY fallback (REMOVE after migration)
            if ($password === $user['password']) {
                // Auto-upgrade to hash
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE clients SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed, $user['id']);
                $update_stmt->execute();
            } else {
                header("Location: login.php?error=Invalid password");
                exit;
            }
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        session_regenerate_id(true);
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=User not found");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>