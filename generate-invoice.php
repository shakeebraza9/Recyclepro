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

ob_start();
require 'templates/invoice-template.php';
$html = ob_get_clean();


$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdf_output = $dompdf->output();


$dir_path = __DIR__ . '/invoices/generated/';
if (!file_exists($dir_path)) {
    mkdir($dir_path, 0755, true);
}

$pdf_file_path = $dir_path . $invoice_no . '.pdf';
file_put_contents($pdf_file_path, $pdf_output);


return [
    'file_path'    => $pdf_file_path,
    'invoice_no'   => $invoice_no,
    'grand_total'  => $grand_total,
    'customer'     => $customer_name,
    'items'        => $items,
    'date'         => $date
];