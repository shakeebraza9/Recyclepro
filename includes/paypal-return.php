<?php
/**
 * PayPal Return Handler
 * User is redirected here after approving payment on PayPal
 * This captures the payment and updates the order status
 */

require_once __DIR__ . '/paypal-payment.php';
require_once __DIR__ . '/checkout-order-service.php';

// Clear output buffering
while (ob_get_level()) {
    ob_end_clean();
}

try {
    // Get PayPal approval token from redirect
    $paypal_order_id = $_GET['token'] ?? null;
    $order_id = intval($_GET['order_id'] ?? 0);

    if (!$paypal_order_id || !$order_id) {
        throw new Exception('Missing PayPal order token or WooCommerce order ID');
    }

    // Capture the PayPal payment
    $capture_result = capture_paypal_payment($paypal_order_id);

    if (!$capture_result['success']) {
        throw new Exception('Failed to capture PayPal payment: ' . ($capture_result['message'] ?? 'Unknown error'));
    }

    // Get transaction ID from capture result
    $transaction_id = $capture_result['transaction_id'] ?? $capture_result['paypal_order_id'];

    // Update WooCommerce order status to processing/completed
    $service = new RecyclePro_Checkout_Order_Service();
    $order_update = $service->complete_paypal_order($order_id, $transaction_id);

    if (!$order_update['success']) {
        throw new Exception('Order update failed: ' . ($order_update['message'] ?? 'Unknown error'));
    }

    // Redirect to order confirmation page
    header('Location: https://www.recyclepro.co.uk/shop/order-confirmation/?order_id=' . $order_id . '&payment_method=paypal&status=completed', true, 302);
    exit;

} catch (Exception $e) {
    // Log error
    error_log('PayPal Return Handler Error: ' . $e->getMessage());

    // Redirect to checkout with error
    header('Location: https://www.recyclepro.co.uk/shop/checkout/?payment_error=' . urlencode($e->getMessage()), true, 302);
    exit;
}
?>
