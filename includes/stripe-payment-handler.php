<?php
/**
 * Compatibility include for older references.
 *
 * The active Stripe flow now lives in woocommerce-order-api.php and always
 * creates a WooCommerce order before Stripe Checkout.
 */

require_once __DIR__ . '/stripe-handler.php';
