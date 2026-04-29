<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rain.systech@gmail.com';
    $mail->Password   = 'njxf utum wxtu iysu';      
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('rain.systech@gmail.com', 'Rain Systems & Technologies');
    $mail->addAddress('jhunbalawang20@example.com');     // Recipient
    $mail->addReplyTo('rain.systech@gmail.com', 'Rain Systems & Technologies');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Rain Systems & Technologies!';
    $mail->Body    = '
        <h2>Welcome!</h2>
        <p>Your account has been created successfully.</p>
        <p><strong>Username:</strong> testuser</p>
        <p><strong>Login:</strong> <a href="https://http://192.168.159.1/PROJ%20REVISION/login.php">Click Here</a></p>
    ';
    $mail->AltBody = 'Welcome! Your account is ready.';

    $mail->send();
    echo 'Email sent successfully!';
    
} catch (Exception $e) {
    echo "Email failed: {$mail->ErrorInfo}";
}
?>