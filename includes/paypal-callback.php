<?php
/**
 * PayPal Callback Handler
 * Handles PayPal payment capture and order confirmation
 */

require_once __DIR__ . '/paypal-payment.php';

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if ($action === 'capture') {
        $paypal_order_id = $_POST['paypal_order_id'] ?? '';
        
        if (!$paypal_order_id) {
            throw new Exception('PayPal order ID is required');
        }

        $result = capture_paypal_payment($paypal_order_id);
        
        echo json_encode($result);
    } 
    elseif ($action === 'return') {
        // User was redirected back from PayPal
        $token = $_GET['token'] ?? '';
        
        if (!$token) {
            throw new Exception('Missing PayPal token');
        }

        // Optionally verify the order details
        echo json_encode([
            'success' => true,
            'message' => 'Payment returned from PayPal',
            'token' => $token
        ]);
    } 
    elseif ($action === 'cancel') {
        // User cancelled the payment
        echo json_encode([
            'success' => false,
            'message' => 'Payment cancelled by user'
        ]);
    }
    else {
        throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>
