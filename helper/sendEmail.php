<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendWelcomeEmail(string $toEmail, string $username): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rain.systech@gmail.com';
        $mail->Password   = 'njxf utum wxtu iysu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('rain.systech@gmail.com', 'Rain Systems & Technologies');
        $mail->addAddress($toEmail, $username);
        $mail->addReplyTo('rain.systech@gmail.com', 'Rain Systems & Technologies');

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Rain Systems & Technologies!';
        $mail->Body    = '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background-color:#e8edf2;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#e8edf2">
<tr><td align="center" style="padding:40px 0;">

  <table width="560" cellpadding="0" cellspacing="0" bgcolor="#0d47a1">
    <tr><td align="center" style="padding:36px 40px 28px;">
      <table cellpadding="0" cellspacing="0"><tr>
        <td align="center" bgcolor="#ffffff" style="width:70px;height:70px;border-radius:50%;">
          <table cellpadding="0" cellspacing="0" width="70" height="70"><tr><td align="center" valign="middle" style="font-size:30px;font-weight:bold;color:#0d47a1;">R</td></tr></table>
        </td>
      </tr></table>
      <br>
      <span style="color:#ffffff;font-size:22px;font-weight:bold;">Rain Systems &amp; Technologies</span><br>
      <span style="color:#90caf9;font-size:13px;">Your trusted technology partner</span>
    </td></tr>
  </table>

  <table width="560" cellpadding="0" cellspacing="0"><tr><td bgcolor="#1565c0" height="5" style="font-size:0;line-height:0;">&nbsp;</td></tr></table>

  <table width="560" cellpadding="0" cellspacing="0" bgcolor="#e3f2fd">
    <tr><td align="center" style="padding:30px 40px;">
      <table cellpadding="0" cellspacing="0"><tr>
        <td align="center" bgcolor="#0d47a1" style="width:60px;height:60px;border-radius:50%;">
          <table width="60" height="60" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle" style="color:#ffffff;font-size:28px;font-weight:bold;">&#10003;</td></tr></table>
        </td>
      </tr></table>
      <br>
      <span style="color:#0d47a1;font-size:21px;font-weight:bold;">Welcome Aboard!</span><br><br>
      <span style="color:#555555;font-size:14px;">Your account has been created successfully.</span>
    </td></tr>
  </table>

  <table width="560" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
    <tr><td style="padding:32px 40px;">
      <p style="font-size:15px;color:#333333;line-height:1.8;margin:0 0 24px;">
        Hi <strong>' . htmlspecialchars($username) . '</strong>,<br><br>
        We are glad to have you on board. Your account is now active and ready to use. Here are your account details:
      </p>

      <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f4f8" style="border:1px solid #dde3ed;">
        <tr><td style="padding:10px 20px;border-bottom:1px solid #dde3ed;">
          <span style="font-size:11px;color:#0d47a1;font-weight:bold;text-transform:uppercase;letter-spacing:1px;">Account Details</span>
        </td></tr>
        <tr><td bgcolor="#ffffff" style="padding:16px 20px;border-bottom:1px solid #dde3ed;">
          <span style="font-size:11px;color:#888888;text-transform:uppercase;letter-spacing:0.8px;">Username</span><br>
          <strong style="font-size:15px;color:#1a1a2e;">' . htmlspecialchars($username) . '</strong>
        </td></tr>
        <tr><td bgcolor="#ffffff" style="padding:16px 20px;border-bottom:1px solid #dde3ed;">
          <span style="font-size:11px;color:#888888;text-transform:uppercase;letter-spacing:0.8px;">Email</span><br>
          <strong style="font-size:15px;color:#1a1a2e;">' . htmlspecialchars($toEmail) . '</strong>
        </td></tr>
        <tr><td bgcolor="#ffffff" style="padding:16px 20px;">
          <span style="font-size:11px;color:#888888;text-transform:uppercase;letter-spacing:0.8px;">Account Status</span><br><br>
          <table cellpadding="0" cellspacing="0"><tr>
            <td bgcolor="#e8f5e9" style="padding:5px 14px;border:1px solid #a5d6a7;">
              <span style="font-size:12px;font-weight:bold;color:#1b5e20;">&#10003; ACTIVE</span>
            </td>
          </tr></table>
        </td></tr>
      </table>

      <br><br>

      <table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center">
        <table cellpadding="0" cellspacing="0"><tr>
          <td bgcolor="#0d47a1" style="padding:15px 40px;">
            <a href="http://192.168.159.1/PROJ%20REVISION/login.php"
               style="color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;letter-spacing:0.5px;">
              Login to Your Account &rarr;
            </a>
          </td>
        </tr></table>
      </td></tr></table>

    </td></tr>
  </table>

  <table width="560" cellpadding="0" cellspacing="0"><tr><td bgcolor="#0d47a1" height="5" style="font-size:0;line-height:0;">&nbsp;</td></tr></table>

  <table width="560" cellpadding="0" cellspacing="0" bgcolor="#f0f4f8">
    <tr><td align="center" style="padding:24px 40px;">
      <span style="font-size:11px;color:#888888;line-height:1.8;">
        &copy; 2025 Rain Systems &amp; Technologies. All rights reserved.<br>
        If you did not request this account, please ignore this email.
      </span>
    </td></tr>
  </table>

</td></tr></table>

</body>
</html>';
        $mail->AltBody = "Welcome to Rain Systems & Technologies, {$username}!\nUsername: {$username}\nEmail: {$toEmail}\nLogin at http://192.168.159.1/PROJ%20REVISION/login.php";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Rain Systems mailer error: ' . $mail->ErrorInfo);
        return false;
    }
}