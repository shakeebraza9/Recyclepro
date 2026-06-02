<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendMail($to, $subject, $body)
{
    try {

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'man411210@gmail.com';
        $mail->Password   = 'vddlaodorltijblu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('man411210@gmail.com', 'Website');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        return [
            'status'  => true,
            'message' => 'Email sent successfully'
        ];

    } catch (Exception $e) {

        return [
            'status'  => false,
            'message' => $mail->ErrorInfo
        ];
    }
}