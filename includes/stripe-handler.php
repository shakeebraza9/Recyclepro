<?php
/**
 * Legacy Stripe route adapter.
 *
 * Older clients may still call recyclepro/v1/create-checkout-session. This
 * adapter keeps the route alive, but enforces the new rule: a WooCommerce
 * order_id must already exist before Stripe Checkout can be created.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('recyclepro/v1', '/create-checkout-session', [
        'methods' => 'POST',
        'callback' => function (WP_REST_Request $request) {
            $params = $request->get_json_params();
            $order_id = absint($params['order_id'] ?? $request->get_param('order_id'));

            if (!$order_id) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'order_id is required. Create the WooCommerce order before Stripe checkout.',
                ], 400);
            }

            $proxy = new WP_REST_Request('POST', '/wp/v2/stripe-checkout');
            $proxy->set_body_params(['order_id' => $order_id]);

            return rest_do_request($proxy);
        },
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('recyclepro/v1', '/process-stripe-payment', [
        'methods' => 'POST',
        'callback' => function () {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Direct frontend payment confirmation is disabled. Stripe webhook updates WooCommerce orders.',
            ], 410);
        },
        'permission_callback' => '__return_true',
    ]);
});
