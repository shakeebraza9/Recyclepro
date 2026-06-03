<?php
/**
 * Shared WooCommerce + Stripe checkout service for shop-side endpoints.
 */

class RecyclePro_Checkout_Order_Service {
    private $stripe_secret_key = '';
    private $stripe_webhook_secret = '';
    private $currency = 'gbp';

    public function __construct() {
        $this->load_wordpress();

        if (defined('RECYCLEPRO_STRIPE_SECRET_KEY')) {
            $this->stripe_secret_key = RECYCLEPRO_STRIPE_SECRET_KEY;
        }

        if (defined('RECYCLEPRO_STRIPE_WEBHOOK_SECRET')) {
            $this->stripe_webhook_secret = RECYCLEPRO_STRIPE_WEBHOOK_SECRET;
        }
    }

    public function create_order(array $data) {
        if (!function_exists('wc_create_order')) {
            throw new Exception('WooCommerce is not available');
        }

        $this->validate_order_payload($data);

        $order = wc_create_order();

        foreach ($data['items'] as $item) {
            $product_id = absint($item['product_id'] ?? 0);
            $quantity = max(1, absint($item['qty'] ?? 1));
            $product = wc_get_product($product_id);

            if (!$product) {
                throw new Exception('Product ID ' . $product_id . ' not found');
            }

            $order->add_product($product, $quantity);
        }

        $this->set_order_addresses($order, $data);
        $order->set_payment_method('stripe');
        $order->set_payment_method_title('Stripe');
        $order->calculate_totals();
        $order->set_status('pending');
        $order->add_order_note('Order created before Stripe Checkout. Awaiting webhook payment confirmation.');
        $order->save();

        return [
            'success' => true,
            'message' => 'Order created and set to pending payment',
            'order_id' => $order->get_id(),
            'status' => $order->get_status(),
            'total' => (float) $order->get_total(),
        ];
    }

    public function create_stripe_checkout($order_id) {
        $order_id = absint($order_id);
        if (!$order_id) {
            throw new Exception('order_id is required before Stripe checkout');
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            throw new Exception('Order not found');
        }

        if ($order->get_status() !== 'pending') {
            throw new Exception('Only pending payment orders can be sent to Stripe');
        }

        $line_items = $this->build_stripe_line_items($order);
        if (!$line_items) {
            throw new Exception('Order has no payable items');
        }

        $stripe_response = $this->create_stripe_checkout_session($order, $line_items);
        if (!$stripe_response['success']) {
            throw new Exception($stripe_response['message']);
        }

        $order->update_meta_data('_stripe_session_id', $stripe_response['session_id']);
        $order->add_order_note('Stripe Checkout session created. Session: ' . $stripe_response['session_id']);
        $order->save();

        return [
            'success' => true,
            'message' => 'Stripe checkout session created for existing order',
            'order_id' => $order_id,
            'checkout_url' => $stripe_response['checkout_url'],
            'session_id' => $stripe_response['session_id'],
        ];
    }

    public function handle_webhook($payload, $signature_header = '') {
        $event = json_decode($payload, true);

        if (!$event || empty($event['type'])) {
            throw new Exception('Invalid Stripe webhook payload');
        }

        if ($this->stripe_webhook_secret && !$this->is_valid_stripe_signature($payload, $signature_header)) {
            throw new Exception('Invalid Stripe webhook signature');
        }

        $object = $event['data']['object'] ?? [];
        $order_id = absint($object['metadata']['order_id'] ?? 0);

        if (!$order_id && !empty($object['id'])) {
            $order_id = $this->find_order_id_by_stripe_object($object['id']);
        }

        if (!$order_id) {
            return ['success' => true, 'message' => 'Webhook received without matching order'];
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return ['success' => true, 'message' => 'Webhook order not found'];
        }

        switch ($event['type']) {
            case 'checkout.session.completed':
                if (($object['payment_status'] ?? '') === 'paid') {
                    $this->mark_order_paid($order, $object);
                }
                break;

            case 'payment_intent.succeeded':
                $this->mark_order_paid($order, $object);
                break;

            case 'checkout.session.expired':
            case 'payment_intent.payment_failed':
            case 'payment_intent.canceled':
                if ($order->get_status() === 'pending') {
                    $order->add_order_note('Stripe payment was not completed. Order remains pending payment.');
                    $order->save();
                }
                break;
        }

        return ['success' => true];
    }

    private function load_wordpress() {
        if (defined('ABSPATH')) {
            return;
        }

        $candidates = [];

        if (getenv('RECYCLEPRO_WP_LOAD_PATH')) {
            $candidates[] = getenv('RECYCLEPRO_WP_LOAD_PATH');
        }

        $candidates[] = __DIR__ . '/../../rp-dashboard/wp-load.php';
        $candidates[] = __DIR__ . '/../rp-dashboard/wp-load.php';
        $candidates[] = __DIR__ . '/../../wp-load.php';
        $candidates[] = __DIR__ . '/../wp-load.php';

        foreach ($candidates as $path) {
            if ($path && file_exists($path)) {
                require_once $path;
                return;
            }
        }

        throw new Exception('Could not load WordPress. Set RECYCLEPRO_WP_LOAD_PATH to wp-load.php.');
    }

    private function validate_order_payload(array $data) {
        if (empty($data['items']) || !is_array($data['items'])) {
            throw new Exception('Items are required');
        }

        foreach ($data['items'] as $item) {
            if (empty($item['product_id']) || empty($item['qty'])) {
                throw new Exception('Each item must include product_id and qty');
            }
        }

        foreach (['billing', 'shipping'] as $address_type) {
            if (empty($data[$address_type]) || !is_array($data[$address_type])) {
                throw new Exception(ucfirst($address_type) . ' address is required');
            }
        }

        foreach (['first_name', 'last_name', 'email', 'address', 'city', 'postcode', 'country'] as $field) {
            if (empty($data['billing'][$field])) {
                throw new Exception('Billing ' . $field . ' is required');
            }
        }

        foreach (['first_name', 'last_name', 'address', 'city', 'postcode', 'country'] as $field) {
            if (empty($data['shipping'][$field])) {
                throw new Exception('Shipping ' . $field . ' is required');
            }
        }
    }

    private function set_order_addresses(WC_Order $order, array $data) {
        $billing = $data['billing'];
        $shipping = $data['shipping'];

        $order->set_billing_first_name(sanitize_text_field($billing['first_name']));
        $order->set_billing_last_name(sanitize_text_field($billing['last_name']));
        $order->set_billing_company(sanitize_text_field($billing['company'] ?? ''));
        $order->set_billing_address_1(sanitize_text_field($billing['address']));
        $order->set_billing_city(sanitize_text_field($billing['city']));
        $order->set_billing_state(sanitize_text_field($billing['state'] ?? ''));
        $order->set_billing_postcode(sanitize_text_field($billing['postcode']));
        $order->set_billing_country(sanitize_text_field($billing['country']));
        $order->set_billing_email(sanitize_email($billing['email']));
        $order->set_billing_phone(sanitize_text_field($billing['phone'] ?? ''));

        $order->set_shipping_first_name(sanitize_text_field($shipping['first_name']));
        $order->set_shipping_last_name(sanitize_text_field($shipping['last_name']));
        $order->set_shipping_company(sanitize_text_field($shipping['company'] ?? ''));
        $order->set_shipping_address_1(sanitize_text_field($shipping['address']));
        $order->set_shipping_city(sanitize_text_field($shipping['city']));
        $order->set_shipping_state(sanitize_text_field($shipping['state'] ?? ''));
        $order->set_shipping_postcode(sanitize_text_field($shipping['postcode']));
        $order->set_shipping_country(sanitize_text_field($shipping['country']));
    }

    private function build_stripe_line_items(WC_Order $order) {
        $line_items = [];

        foreach ($order->get_items() as $item) {
            $quantity = max(1, (int) $item->get_quantity());
            $unit_amount = (int) round(((float) $item->get_total() / $quantity) * 100);

            if ($unit_amount < 1) {
                continue;
            }

            $line_items[] = [
                'price_data' => [
                    'currency' => $this->currency,
                    'product_data' => ['name' => $item->get_name()],
                    'unit_amount' => $unit_amount,
                ],
                'quantity' => $quantity,
            ];
        }

        return $line_items;
    }

    private function create_stripe_checkout_session(WC_Order $order, array $line_items) {
        $post_data = [
            'payment_method_types[]' => 'card',
            'mode' => 'payment',
            'customer_email' => $order->get_billing_email(),
            'success_url' => 'https://www.recyclepro.co.uk/shop/order-confirmation/?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->get_id(),
            'cancel_url' => 'https://www.recyclepro.co.uk/shop/checkout/?order_cancelled=1&order_id=' . $order->get_id(),
            'metadata[order_id]' => $order->get_id(),
            'payment_intent_data[metadata][order_id]' => $order->get_id(),
        ];

        foreach ($line_items as $index => $item) {
            $post_data["line_items[$index][price_data][currency]"] = $item['price_data']['currency'];
            $post_data["line_items[$index][price_data][product_data][name]"] = $item['price_data']['product_data']['name'];
            $post_data["line_items[$index][price_data][unit_amount]"] = $item['price_data']['unit_amount'];
            $post_data["line_items[$index][quantity]"] = $item['quantity'];
        }

        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->stripe_secret_key . ':');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            return ['success' => false, 'message' => 'Stripe cURL error: ' . $curl_error];
        }

        $stripe_response = json_decode($response, true);
        if ($http_code < 200 || $http_code >= 300) {
            return ['success' => false, 'message' => $stripe_response['error']['message'] ?? 'Stripe checkout failed'];
        }

        if (empty($stripe_response['url']) || empty($stripe_response['id'])) {
            return ['success' => false, 'message' => 'Stripe did not return a checkout URL'];
        }

        return [
            'success' => true,
            'checkout_url' => $stripe_response['url'],
            'session_id' => $stripe_response['id'],
        ];
    }

    private function mark_order_paid(WC_Order $order, array $stripe_object) {
        if (!$order->has_status(['pending', 'failed', 'on-hold'])) {
            return;
        }

        $transaction_id = $stripe_object['payment_intent'] ?? $stripe_object['id'] ?? '';
        if ($transaction_id) {
            $order->set_transaction_id($transaction_id);
        }

        $order->payment_complete($transaction_id);
        $order->add_order_note('Stripe webhook confirmed successful payment.');

        if ($order->get_status() === 'processing' && !$order->needs_processing()) {
            $order->update_status('completed', 'Order completed automatically because no processing is required.');
        }

        $order->save();
    }

    private function find_order_id_by_stripe_object($stripe_id) {
        $orders = wc_get_orders([
            'limit' => 1,
            'return' => 'ids',
            'meta_key' => '_stripe_session_id',
            'meta_value' => sanitize_text_field($stripe_id),
        ]);

        return !empty($orders[0]) ? absint($orders[0]) : 0;
    }

    private function is_valid_stripe_signature($payload, $signature_header) {
        $parts = [];

        foreach (explode(',', $signature_header) as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
            $parts[$key][] = $value;
        }

        $timestamp = $parts['t'][0] ?? '';
        $signatures = $parts['v1'] ?? [];
        if (!$timestamp || !$signatures) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $this->stripe_webhook_secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }
}
