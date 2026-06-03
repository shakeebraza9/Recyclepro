<?php
require_once __DIR__ . '/checkout-order-service.php';

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $payload = file_get_contents('php://input');
    $data = json_decode($payload, true);
    $action = $_GET['action'] ?? ($data['action'] ?? '');

    if (!$action) {
        throw new Exception('Action is required');
    }

    $service = new RecyclePro_Checkout_Order_Service();

    if ($action === 'create_order') {
        if (!is_array($data)) {
            throw new Exception('Invalid JSON input');
        }

        $result = $service->create_order($data);
    } elseif ($action === 'stripe_checkout') {
        if (!is_array($data)) {
            throw new Exception('Invalid JSON input');
        }

        $result = $service->create_stripe_checkout((int) ($data['order_id'] ?? 0));
    } elseif ($action === 'stripe_webhook') {
        $result = $service->handle_webhook($payload, $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '');
    } else {
        throw new Exception('Unknown checkout action');
    }

    http_response_code(200);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
