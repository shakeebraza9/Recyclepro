<?php

require_once 'includes/smtp.php';

$response = sendMail(
    'shakeebraza90@gmail.com',
    'Welcome',
    '<h1>Hello User</h1>'
);

echo "<pre>";
print_r($response);
echo "</pre>";