<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$invoice_no = 'INV-1001';
$customer_name = 'Muhammad Shakeeb';
$date = date('d M Y');

$items = [
    [
        'name'  => 'Laptop',
        'qty'   => 1,
        'price' => 500
    ],
    [
        'name'  => 'Mobile',
        'qty'   => 2,
        'price' => 300
    ]
];

$grand_total = 1100;

$html = require 'templates/invoice-template.php';

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();



$dompdf->stream(
    $invoice_no . '.pdf',
    ['Attachment' => false]
);

exit;