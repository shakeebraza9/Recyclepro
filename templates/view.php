<?php
require '../vendor/autoload.php';
$config =require '../includes/config.php';


use Dompdf\Dompdf;
use Dompdf\Options;

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
if (!$order_id) { die("Order ID is missing."); }

$api_url = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/order-info/" . $order_id;
$response = file_get_contents($api_url);
$order_data = json_decode($response, true);

if (!$order_data || !isset($order_data['success']) || $order_data['success'] !== true) {
    die("Unable to fetch order details.");
}

$order = $order_data['order'];
$url = $config['BASE_URL'];

$invoice_no    = $order['order_number'] ?? $order['order_id'];
$date          = date('F d, Y', strtotime($order['date_created']));
$status        = $order['status'] ?? 'pending';
$currency      = ($order['currency'] === 'GBP') ? '£' : '$';


$billing       = $order['billing'];
$customer_name = $billing['first_name'] . ' ' . $billing['last_name'];
$customer_email = $billing['email'];
$customer_phone = $billing['phone'];
$billing_address = $billing['address_1'] . ($billing['address_2'] ? ', ' . $billing['address_2'] : '');
$billing_location = $billing['city'] . ', ' . $billing['state'] . ' ' . $billing['postcode'];
$country = $order['shipping_address']['country'] ?? '';

$subtotal      = (float)$order['subtotal'];
$grand_total   = (float)$order['total'];
$shipping_cost = 0.00; 
$tax_amount    = 0.00; 


$items = [];
if (isset($order['items'])) {
    foreach ($order['items'] as $item) {
        $items[] = [
            'name'  => $item['name'],
            'qty'   => (int)$item['quantity'],
            'price' => (float)$item['total'],
            'image' => $item['image'] ?? ''
        ];
    }
}


$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

ob_start();

require 'invoice-template.php'; 
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("INV-" . $invoice_no . ".pdf", ["Attachment" => false]);
exit;