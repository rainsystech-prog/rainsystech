<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
        header("Location: register.php?error=All fields required");
        exit;
    }

    if ($password !== $confirm_password) {
        header("Location: register.php?error=Passwords do not match");
        exit;
    }

    if (strlen($password) < 6) {
        header("Location: register.php?error=Password must be 6+ characters");
        exit;
    }

    // Check duplicate
    $check = $conn->prepare("SELECT id FROM clients WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        header("Location: register.php?error=Username or Email already exists");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("
        INSERT INTO clients 
        (first_name, last_name, birthday, email, phone, address, username, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssss",
        $first_name,
        $last_name,
        $birthday,
        $email,
        $phone,
        $address,
        $username,
        $hashed_password
    );

    if ($stmt->execute()) {
        
        // Send welcome email
        if (file_exists('vendor/autoload.php')) {
            require_once 'vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rain.systech@gmail.com';
                $mail->Password = 'njxf utum wxtu iysu';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                $mail->setFrom('rain.systech@gmail.com', 'Rain Systems & Technologies');
                $mail->addAddress($email);
                $mail->addReplyTo('rain.systech@gmail.com', 'Support');
                
                $mail->isHTML(true);
                $mail->Subject = 'Welcome to Rain Systems & Technologies!';
                $mail->Body = "
                    <h2>Welcome $first_name $last_name!</h2>
                    <p>Your account has been created successfully!</p>
                    <p><strong>Username:</strong> $username</p>
                    <p><strong>Email:</strong> $email</p>
                    <br>
                    <a href='http://192.168.159.1/PROJ%20REVISION/login.php' 
                       style='background:#007bff;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;font-weight:bold;'>Login Now</a>
                ";
                
                $mail->send();
            } catch (Exception $e) {
            }
        }
        
        header("Location: register.php?success=Account created successfully! Check your email.");
        exit;
    } else {
        header("Location: register.php?error=Registration failed. Try again.");
        exit;
    }
}
?>