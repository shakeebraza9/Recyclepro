<?php
/**
 * PayPal Checkout Payment Handler
 * Handles PayPal payments for orders - Creates WooCommerce orders first
 */

require_once __DIR__ . '/checkout-order-service.php';

// PayPal Credentials
define('PAYPAL_CLIENT_ID', 'BAA_EmaLUENvjlPx7_V0eET23DvCFeucJKvDFmzRiPoZtRVxZi-U42Zn7ZurIkOMryVaPmx-vH8XfKK7o8');
define('PAYPAL_SECRET_KEY', 'EPrakEjYhk4NVfoasyJ9CXvVtKv_QrMLfmZysG6A5ofdFmEYva9V_nJ75M5TG-4N2p-XwVIk42za1Gx0');
define('PAYPAL_API_BASE', 'https://api.sandbox.paypal.com'); // Use sandbox for testing, change to https://api.paypal.com for production
define('PAYPAL_MODE', 'sandbox'); // 'sandbox' or 'live'

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

    if (!is_array($data)) {
        throw new Exception('Invalid JSON input');
    }

    // Step 1: Create WooCommerce order first
    $service = new RecyclePro_Checkout_Order_Service();
    $order_result = $service->create_paypal_order($data);

    if (!$order_result['success']) {
        throw new Exception($order_result['message']);
    }

    $woo_order_id = $order_result['order_id'];

    // Step 2: Create PayPal order with WooCommerce order ID
    $paypal_result = create_paypal_order($data, $woo_order_id);

    echo json_encode($paypal_result);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}

/**
 * Create a PayPal order with WooCommerce order reference
 */
function create_paypal_order($data, $woo_order_id) {
    $access_token = get_paypal_access_token();

    if (!$access_token) {
        throw new Exception('Failed to obtain PayPal access token');
    }

    // Prepare order items for PayPal
    $items = [];
    $subtotal = 0;

    if (isset($data['items']) && is_array($data['items'])) {
        foreach ($data['items'] as $item) {
            $items[] = [
                'name' => $item['name'] ?? 'Product',
                'quantity' => (string) ($item['qty'] ?? 1),
                'unit_amount' => [
                    'currency_code' => 'GBP',
                    'value' => number_format((float)($item['price'] ?? 0), 2, '.', '')
                ]
            ];
            $subtotal += ((float)($item['price'] ?? 0) * (int)($item['qty'] ?? 1));
        }
    }

    // Create PayPal order payload
    $paypal_order = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'reference_id' => 'WOO_ORDER_' . $woo_order_id,
                'description' => 'RecyclePro Order #' . $woo_order_id,
                'custom_id' => (string) $woo_order_id,
                'amount' => [
                    'currency_code' => 'GBP',
                    'value' => number_format((float)($data['total'] ?? $subtotal), 2, '.', ''),
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => 'GBP',
                            'value' => number_format($subtotal, 2, '.', '')
                        ]
                    ]
                ],
                'items' => $items,
                'shipping' => [
                    'name' => [
                        'full_name' => ($data['shipping_first_name'] ?? '') . ' ' . ($data['shipping_last_name'] ?? '')
                    ],
                    'address' => [
                        'address_line_1' => $data['shipping_street'] ?? '',
                        'admin_area_2' => $data['shipping_city'] ?? '',
                        'admin_area_1' => $data['shipping_state'] ?? '',
                        'postal_code' => $data['shipping_postcode'] ?? '',
                        'country_code' => $data['shipping_country'] ?? 'GB'
                    ]
                ]
            ]
        ],
        'payer' => [
            'email_address' => $data['billing_email'] ?? '',
            'name' => [
                'given_name' => $data['billing_first_name'] ?? '',
                'surname' => $data['billing_last_name'] ?? ''
            ],
            'phone' => [
                'phone_number' => [
                    'national_number' => $data['billing_phone'] ?? ''
                ]
            ]
        ]
    ];

    // Add return URLs
    $paypal_order['application_context'] = [
        'return_url' => 'https://www.recyclepro.co.uk/shop/includes/paypal-return.php?order_id=' . $woo_order_id,
        'cancel_url' => 'https://www.recyclepro.co.uk/shop/checkout/?order_cancelled=1&order_id=' . $woo_order_id,
        'locale' => 'en-GB',
        'brand_name' => 'RecyclePro'
    ];

    // Create PayPal order via API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAYPAL_API_BASE . '/v2/checkout/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET_KEY);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Prefer: return=representation'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paypal_order));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $paypal_response = json_decode($response, true);

    if ($http_code !== 201) {
        throw new Exception('PayPal API Error: ' . ($paypal_response['message'] ?? 'Unable to create order'));
    }

    if (!isset($paypal_response['id'])) {
        throw new Exception('PayPal order creation failed: No order ID returned');
    }

    // Get approval link
    $approval_link = '';
    if (isset($paypal_response['links']) && is_array($paypal_response['links'])) {
        foreach ($paypal_response['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $approval_link = $link['href'];
                break;
            }
        }
    }

    return [
        'success' => true,
        'message' => 'PayPal order created successfully',
        'paypal_order_id' => $paypal_response['id'],
        'order_id' => $woo_order_id,
        'approval_link' => $approval_link,
        'status' => $paypal_response['status'] ?? 'CREATED'
    ];
}

/**
 * Get PayPal Access Token
 */
function get_paypal_access_token() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAYPAL_API_BASE . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET_KEY);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return null;
    }

    $data = json_decode($response, true);
    return $data['access_token'] ?? null;
}

/**
 * Capture PayPal Payment (called after user approves payment on PayPal site)
 */
function capture_paypal_payment($paypal_order_id) {
    $access_token = get_paypal_access_token();

    if (!$access_token) {
        throw new Exception('Failed to obtain PayPal access token');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAYPAL_API_BASE . '/v2/checkout/orders/' . urlencode($paypal_order_id) . '/capture');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'Prefer: return=representation'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $paypal_response = json_decode($response, true);

    if ($http_code !== 201) {
        throw new Exception('PayPal capture failed: ' . ($paypal_response['message'] ?? 'Unknown error'));
    }

    return [
        'success' => true,
        'paypal_order_id' => $paypal_response['id'],
        'status' => $paypal_response['status'],
        'transaction_id' => $paypal_response['purchase_units'][0]['payments']['captures'][0]['id'] ?? null
    ];
}
?>
