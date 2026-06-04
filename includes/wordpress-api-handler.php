<?php
/**
 * WordPress REST API Handler for Order Creation
 * Endpoint: /wp-json/wp/v2/create-order
 * Handles order creation from WordPress and routes to Stripe checkout
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RecycleProOrderAPI {
    
    public static function register_routes() {
        register_rest_route('wp/v2', '/create-order', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'handle_order_creation'),
            'permission_callback' => '__return_true',
        ));
    }

    public static function handle_order_creation(WP_REST_Request $request) {
        try {
            $body = $request->get_json_params();

            // Validate required fields
            if (empty($body['items']) || !is_array($body['items'])) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Items array is required'
                ], 400);
            }

            if (empty($body['billing']) || !is_array($body['billing'])) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Billing information is required'
                ], 400);
            }

            if (empty($body['shipping']) || !is_array($body['shipping'])) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Shipping information is required'
                ], 400);
            }

            // Validate billing fields
            $required_billing_fields = ['first_name', 'last_name', 'email', 'address', 'city', 'postcode', 'country'];
            foreach ($required_billing_fields as $field) {
                if (empty($body['billing'][$field])) {
                    return new WP_REST_Response([
                        'success' => false,
                        'message' => "Billing {$field} is required"
                    ], 400);
                }
            }

            // Validate shipping fields
            $required_shipping_fields = ['first_name', 'last_name', 'address', 'city', 'postcode', 'country'];
            foreach ($required_shipping_fields as $field) {
                if (empty($body['shipping'][$field])) {
                    return new WP_REST_Response([
                        'success' => false,
                        'message' => "Shipping {$field} is required"
                    ], 400);
                }
            }

            // Process items and fetch product information
            $processed_items = [];
            $total = 0;

            foreach ($body['items'] as $item) {
                if (empty($item['product_id']) || empty($item['qty'])) {
                    return new WP_REST_Response([
                        'success' => false,
                        'message' => 'Each item must have product_id and qty'
                    ], 400);
                }

                // Fetch product from WooCommerce or database
                $product_data = self::get_product_data($item['product_id']);

                if (!$product_data) {
                    return new WP_REST_Response([
                        'success' => false,
                        'message' => "Product {$item['product_id']} not found"
                    ], 404);
                }

                $item_total = $product_data['price'] * $item['qty'];
                $total += $item_total;

                $processed_items[] = [
                    'name' => $product_data['name'],
                    'price' => $product_data['price'],
                    'qty' => $item['qty'],
                    'product_id' => $item['product_id']
                ];
            }

            // Transform data into Stripe checkout format
            $stripe_payload = [
                'total' => $total,
                'items' => $processed_items,
                'shipping_first_name' => $body['shipping']['first_name'],
                'shipping_last_name' => $body['shipping']['last_name'],
                'shipping_street' => $body['shipping']['address'],
                'shipping_city' => $body['shipping']['city'],
                'shipping_state' => $body['shipping']['state'] ?? '',
                'shipping_postcode' => $body['shipping']['postcode'],
                'shipping_country' => $body['shipping']['country'],
                'billing_different' => !self::are_addresses_same($body['billing'], $body['shipping']) ? 1 : 0,
                'billing_first_name' => $body['billing']['first_name'],
                'billing_last_name' => $body['billing']['last_name'],
                'billing_street' => $body['billing']['address'],
                'billing_city' => $body['billing']['city'],
                'billing_state' => $body['billing']['state'] ?? '',
                'billing_postcode' => $body['billing']['postcode'],
                'billing_country' => $body['billing']['country'],
            ];

            // Add optional company field if provided
            if (!empty($body['billing']['company'])) {
                $stripe_payload['billing_company'] = $body['billing']['company'];
            }
            if (!empty($body['shipping']['company'])) {
                $stripe_payload['shipping_company'] = $body['shipping']['company'];
            }

            // Store order metadata for later retrieval
            $order_metadata = [
                'customer_email' => $body['billing']['email'],
                'customer_phone' => $body['billing']['phone'] ?? '',
                'items' => $processed_items,
                'billing' => $body['billing'],
                'shipping' => $body['shipping'],
                'total' => $total,
            ];

            // Call Stripe checkout handler
            $stripe_response = self::call_stripe_checkout($stripe_payload, $order_metadata);

            if (!$stripe_response['success']) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => $stripe_response['message']
                ], 400);
            }

            return new WP_REST_Response([
                'success' => true,
                'message' => 'Checkout session created',
                'checkout_url' => $stripe_response['checkout_url'],
                'session_id' => $stripe_response['session_id'] ?? '',
                'total' => $total,
                'items_count' => count($processed_items)
            ], 200);

        } catch (Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch product data from WooCommerce or custom database
     */
    private static function get_product_data($product_id) {
        // Try WooCommerce if available
        if (function_exists('wc_get_product')) {
            $product = wc_get_product($product_id);
            if ($product) {
                return [
                    'name' => $product->get_name(),
                    'price' => (float) $product->get_price(),
                    'sku' => $product->get_sku(),
                ];
            }
        }

        // Fallback: query custom database table or post meta
        global $wpdb;
        
        // Check if product exists in posts
        $product = $wpdb->get_row($wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE ID = %d AND post_type IN ('product', 'recyclepro_product')",
            $product_id
        ));

        if ($product) {
            // Fetch price from postmeta
            $price = $wpdb->get_var($wpdb->prepare(
                "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_price'",
                $product_id
            ));

            return [
                'name' => $product->post_title,
                'price' => (float) ($price ?? 0),
                'sku' => '',
            ];
        }

        return null;
    }

    /**
     * Check if billing and shipping addresses are the same
     */
    private static function are_addresses_same($billing, $shipping) {
        return (
            $billing['first_name'] === $shipping['first_name'] &&
            $billing['last_name'] === $shipping['last_name'] &&
            $billing['address'] === $shipping['address'] &&
            $billing['city'] === $shipping['city'] &&
            $billing['postcode'] === $shipping['postcode'] &&
            $billing['country'] === $shipping['country']
        );
    }

    /**
     * Call Stripe checkout handler via cURL
     */
    private static function call_stripe_checkout($payload, $metadata) {
        $stripe_handler_url = home_url('/includes/stripe-checkout.php');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $stripe_handler_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'message' => 'Failed to create Stripe checkout session'
            ];
        }

        $result = json_decode($response, true);
        return $result ?? ['success' => false, 'message' => 'Invalid response from Stripe handler'];
    }
}

// Register the route when WordPress REST API is initialized
add_action('rest_api_init', array('RecycleProOrderAPI', 'register_routes'));
