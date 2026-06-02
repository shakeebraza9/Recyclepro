<?php

require_once '../includes/smtp.php';

$body = '
<div style="max-width:600px;margin:auto;font-family:Arial,sans-serif;border:1px solid #ddd;">

    <div style="background:#13564f;padding:20px;text-align:center;">
        <h1 style="color:#fff;margin:0;">Order Confirmed</h1>
    </div>

    <div style="padding:30px;">
        <h2 style="color:#13564f;">Thank You For Your Order!</h2>

        <p>Your order has been received successfully.</p>

        <table width="100%" cellpadding="10" style="border-collapse:collapse;">
            <tr>
                <td><strong>Order ID</strong></td>
                <td>#12345</td>
            </tr>

            <tr>
                <td><strong>Date</strong></td>
                <td>'.date('d M Y').'</td>
            </tr>

            <tr>
                <td><strong>Total</strong></td>
                <td>$150</td>
            </tr>
        </table>

        <br>

        <a href="#"
           style="
           background:#13564f;
           color:white;
           text-decoration:none;
           padding:12px 20px;
           display:inline-block;
           border-radius:5px;">
           View Order
        </a>

    </div>

    <div style="background:#13564f;color:white;padding:15px;text-align:center;">
        © '.date('Y').' BK Recycle
    </div>

</div>
';

$response = sendMail(
    'shakeebraza90@gmail.com',
    'Order Confirmation #12345',
    $body
);

echo "<pre>";
print_r($response);
echo "</pre>";