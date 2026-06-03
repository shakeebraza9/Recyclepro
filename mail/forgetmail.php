<?php

header('Content-Type: application/json');


require_once '../includes/smtp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email      = isset($_POST['email']) ? trim($_POST['email']) : '';
    $token      = isset($_POST['token']) ? trim($_POST['token']) : '';
    $encoded_email = base64_encode($email);
    $reset_link = $_POST['link']
    . 'reset-password?email=' . urlencode($encoded_email)
    . '&token=' . urlencode($token);

    $user_id    = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';


    if (empty($email) || empty($reset_link)) {
        echo json_encode([
            'success' => false,
            'message' => 'Required parameters (email or reset link) are missing.'
        ]);
        exit;
    }

    $subject = "Password Reset Request - Recycle Pro";
    

    $body = "
    <div style='background-color: #f5f8fa; padding: 40px 20px; font-family: Arial, sans-serif; text-align: center;'>
        <div style='max-width: 500px; background: #ffffff; margin: 0 auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); text-align: left;'>
            
            <h2 style='color: #13564f; margin-top: 0; font-size: 24px; font-weight: 700; text-align: center;'>Recycle Pro</h2>
            <hr style='border: 0; border-top: 1px solid #f1f3f5; margin-bottom: 25px;'>
            
            <p style='font-size: 15px; color: #495057; line-height: 1.6;'>Hello,</p>
            <p style='font-size: 15px; color: #495057; line-height: 1.6;'>
                We received a request to reset the password for your account associated with <strong>" . htmlspecialchars($email) . "</strong>.
            </p>
            <p style='font-size: 15px; color: #495057; line-height: 1.6;'>
                Click the button below to change your password. This link will safely guide you through creating a secure new password.
            </p>
            
            <div style='text-align: center; margin: 30px 0;'>
                <a href='" . $reset_link . "' target='_blank' style='background-color: #13564f; color: #ffffff; text-decoration: none; padding: 12px 30px; font-weight: 600; font-size: 15px; border-radius: 6px; display: inline-block; box-shadow: 0 4px 6px rgba(19,86,79,0.2);'>
                    Reset My Password
                </a>
            </div>
            
            <p style='font-size: 13px; color: #6c757d; line-height: 1.5; background: #f8f9fa; padding: 12px; border-radius: 6px;'>
                <strong>If the button above doesn't work, copy and paste this URL into your browser:</strong><br>
                <a href='" . $reset_link . "' target='_blank' style='color: #13564f; word-break: break-all;'>" . $reset_link . "</a>
            </p>
            
            <hr style='border: 0; border-top: 1px solid #f1f3f5; margin-top: 25px; margin-bottom: 20px;'>
            <p style='font-size: 12px; color: #adb5bd; text-align: center; margin-bottom: 0;'>
                If you did not request this, please ignore this email. Your password will remain unchanged.
            </p>
        </div>
    </div>
    ";


    $mailResult = sendMail($email, $subject, $body);


    if ($mailResult['status'] === true) {
        echo json_encode([
            'success' => true,
            'message' => 'Email sent successfully through SMTP.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'SMTP Error: ' . $mailResult['message']
        ]);
    }

} else {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid Request Method.'
    ]);
}
?>