<?php
$pageTitle = 'Order Confirmation';
include __DIR__ . '/includes/header.php';

// Get parameters from URL
$session_id = $_GET['session_id'] ?? null;
$order_id = intval($_GET['order_id'] ?? 0);
$payment_method = $_GET['payment_method'] ?? 'stripe'; // 'stripe' or 'paypal'
$payment_status = $_GET['status'] ?? null;

// Verify we have required parameters
if (!$order_id) {
    ?>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="alert alert-danger">
                    <h4>Invalid Request</h4>
                    <p>Missing order ID. Please try again or contact support.</p>
                    <a href="/shop/checkout/" class="btn btn-primary">Return to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch order from WordPress API
$wp_api_url = 'https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2';

$order_response = @file_get_contents($wp_api_url . '/order-info/' . intval($order_id));
$order_data = $order_response ? json_decode($order_response, true) : null;

// Extract order information
$order_verified = false;
$order_status = 'pending';
$order_total = 0;
$order_items = [];
$billing_email = '';
$billing_name = '';
$transaction_id = '';

if ($order_data && isset($order_data['success']) && $order_data['success']) {
    $order_verified = true;
    $order_status = $order_data['status'] ?? 'pending';
    $order_total = $order_data['total'] ?? 0;
    $order_items = $order_data['items'] ?? [];
    $billing_email = $order_data['billing_email'] ?? '';
    $billing_name = ($order_data['billing_first_name'] ?? '') . ' ' . ($order_data['billing_last_name'] ?? '');
    $transaction_id = $order_data['transaction_id'] ?? '';
}

// Determine if payment was successful
$payment_success = ($payment_status === 'completed' || ($order_status !== 'pending' && $order_status !== 'failed'));

?>

<style>
    .confirmation-container {
        padding: 40px 0;
    }

    .confirmation-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
    }

    .confirmation-icon {
        font-size: 60px;
        color: #10b981;
        margin-bottom: 20px;
    }

    .order-number {
        font-size: 24px;
        font-weight: bold;
        color: #1f2937;
        margin: 20px 0;
    }

    .session-id {
        font-family: monospace;
        font-size: 12px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 10px;
        border-radius: 5px;
        word-break: break-all;
        margin: 15px 0;
    }

    .order-summary {
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        margin: 30px 0;
        text-align: left;
    }

    .order-summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-summary-item:last-child {
        border-bottom: none;
    }

    .order-total {
        font-size: 20px;
        font-weight: bold;
        color: #13564f;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #13564f;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        margin: 15px 0;
        text-transform: uppercase;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-processing {
        background-color: #dbeafe;
        color: #0c4a6e;
    }

    .status-completed {
        background-color: #dcfce7;
        color: #166534;
    }

    .next-steps {
        background: #f0fdf4;
        border-left: 4px solid #10b981;
        padding: 20px;
        margin: 30px 0;
        border-radius: 5px;
    }

    .next-steps h5 {
        color: #166534;
        margin-bottom: 15px;
    }

    .next-steps ol {
        margin: 0;
        padding-left: 20px;
    }

    .next-steps li {
        margin: 8px 0;
        color: #1f2937;
    }

    .action-buttons {
        margin-top: 30px;
    }

    .action-buttons a {
        margin: 0 10px;
    }
</style>

<div class="container confirmation-container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="confirmation-card">
                <!-- Success Icon -->
                <div class="confirmation-icon">
                    ✓
                </div>

                <!-- Main Message -->
                <h1>Order Confirmed!</h1>
                <p class="text-muted" style="font-size: 16px; margin-bottom: 30px;">
                    Thank you for your order. We've received your payment and will start processing your items.
                </p>

                <!-- Order Number -->
                <div class="order-number">
                    Order #<?php echo htmlspecialchars($order_id); ?>
                </div>

                <!-- Status Badge -->
                <span class="status-badge <?php echo $order_status === 'completed' ? 'status-completed' : ($order_status === 'processing' ? 'status-processing' : 'status-pending'); ?>">
                    <?php 
                    $status_map = [
                        'pending' => 'Payment Pending',
                        'processing' => 'Processing',
                        'completed' => 'Order Confirmed',
                        'cancelled' => 'Cancelled',
                        'failed' => 'Failed'
                    ];
                    echo $status_map[$order_status] ?? ucfirst($order_status);
                    ?>
                </span>

                <!-- Session ID (for Stripe) or Payment Method (for PayPal) -->
                <?php if ($payment_method === 'stripe' && $session_id): ?>
                <div class="session-id">
                    <small>Session ID: <?php echo htmlspecialchars($session_id); ?></small>
                </div>
                <?php elseif ($payment_method === 'paypal'): ?>
                <div class="session-id">
                    <small>Payment Method: PayPal</small>
                    <?php if ($transaction_id): ?>
                    <br><small>Transaction ID: <?php echo htmlspecialchars(substr($transaction_id, 0, 20)); ?>...</small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="order-summary-item">
                        <strong>Customer Email:</strong>
                        <span><?php echo htmlspecialchars($billing_email); ?></span>
                    </div>
                    <div class="order-summary-item">
                        <strong>Order Date:</strong>
                        <span><?php echo date('F j, Y \a\t g:i A'); ?></span>
                    </div>
                    <div class="order-summary-item">
                        <strong>Order Status:</strong>
                        <span style="text-transform: capitalize;">
                            <?php 
                            $status_map = [
                                'pending' => 'Pending Payment',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'failed' => 'Failed'
                            ];
                            echo $status_map[$order_status] ?? ucfirst($order_status);
                            ?>
                        </span>
                    </div>
                    <div class="order-summary-item">
                        <strong>Payment Method:</strong>
                        <span style="text-transform: capitalize;">
                            <?php echo $payment_method === 'paypal' ? 'PayPal' : 'Credit Card (Stripe)'; ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($order_items)): ?>
                    <div style="border-top: 1px solid #e5e7eb; margin-top: 15px; padding-top: 15px;">
                        <strong style="display: block; margin-bottom: 10px;">Items Ordered:</strong>
                        <?php foreach ($order_items as $item): ?>
                        <div class="order-summary-item">
                            <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo intval($item['quantity']); ?></span>
                            <span>£<?php echo number_format((float)$item['total'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="order-summary-item" style="border-top: 2px solid #13564f; margin-top: 10px; padding-top: 10px;">
                        <strong style="font-size: 16px;">Total Amount:</strong>
                        <span style="font-size: 16px; font-weight: bold; color: #13564f;">£<?php echo number_format($order_total, 2); ?></span>
                    </div>
                </div>

                <!-- Next Steps -->
                <?php if ($order_status !== 'failed' && $order_status !== 'cancelled'): ?>
                <div class="next-steps">
                    <h5>What Happens Next?</h5>
                    <ol>
                        <li><strong>Order Confirmation Email:</strong> We'll send you a detailed receipt to <?php echo htmlspecialchars($billing_email); ?></li>
                        <li><strong>Payment Verification:</strong> Our system is verifying your <?php echo $payment_method === 'paypal' ? 'PayPal' : 'card'; ?> payment</li>
                        <li><strong>Processing:</strong> Once verified, our team will begin preparing your order for shipment</li>
                        <li><strong>Shipping Updates:</strong> You'll receive tracking information once your items ship</li>
                        <li><strong>Delivery:</strong> Your items will be delivered to your specified address</li>
                    </ol>
                </div>
                <?php else: ?>
                <div class="alert alert-danger" style="margin-top: 20px;">
                    <h5>Payment Issue</h5>
                    <p>There was a problem processing your payment. Please contact our support team or try again.</p>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/shop/user/order/" class="btn btn-primary">
                        View Order Details
                    </a>
                    <a href="/shop/" class="btn btn-outline-secondary">
                        Continue Shopping
                    </a>
                </div>

                <!-- Support -->
                <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <p style="color: #6b7280; font-size: 14px;">
                        Need help? <a href="/shop/page/" class="text-primary">Contact our support team</a> or check your email for more information.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/includes/footer.php';
?>
