<?php
$pageTitle = 'Checkout';
include __DIR__ . '/includes/header.php';
?>
<style>

.payment-card-option {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 15px 20px;
    background-color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.payment-card-option:hover {
    border-color: #cbd5e1;
    background-color: #f8fafc;
}


.payment-card-option.selected {
    border-color: #13564f;
    background-color: rgba(19, 86, 79, 0.03);
}


.custom-radio-circle {
    width: 18px;
    height: 18px;
    border: 2px solid #cbd5e1;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s ease;
}

.payment-card-option.selected .custom-radio-circle {
    border-color: #13564f;
    background-color: #13564f;
}

.payment-card-option.selected .custom-radio-circle::after {
    content: '';
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #ffffff;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Brand Palette Icons Dynamic */
.bi-paypal { color: #003087; }
.bi-credit-card-2-front { color: #635bff; }

/* Stripe Inner Card Helper Layout */
.card-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.card-brands {
    position: absolute;
    right: 12px;
    display: flex;
    gap: 4px;
}
</style>
<main class="checkout-page py-5">
    <div class="container">
        <div class="checkout-heading mb-4">
            <span class="checkout-eyebrow">Secure Checkout</span>
            <h1>Checkout</h1>
            <p class="text-muted mb-0">Add your shipping details, review your order, and choose how you want to pay.</p>
        </div>

        <div id="checkout-empty" class="checkout-empty text-center py-5 d-none">
            <i class="bi bi-bag-x display-1 text-muted"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted">Add a product before checkout.</p>
            <a href="/shop/category" class="btn btn-dark">Browse Products</a>
        </div>

        <form id="checkout-form" class="checkout-form">
            <div class="row g-4">
                <div class="col-lg-7">
                    <section class="checkout-panel">
                        <h2>Shipping Address</h2>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="shipping_first_name" class="form-label">First name <span>*</span></label>
                                <input type="text" class="form-control" id="shipping_first_name" name="shipping_first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_last_name" class="form-label">Last name <span>*</span></label>
                                <input type="text" class="form-control" id="shipping_last_name" name="shipping_last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_email" class="form-label">Email <span>*</span></label>
                                <input type="email" class="form-control" id="billing_email" name="billing_email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="billing_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="billing_phone" name="billing_phone">
                            </div>
                            <div class="col-12">
                                <label for="shipping_company" class="form-label">Company name</label>
                                <input type="text" class="form-control" id="shipping_company" name="shipping_company">
                            </div>
                            <div class="col-12">
                                <label for="shipping_street" class="form-label">Street address <span>*</span></label>
                                <input type="text" class="form-control" id="shipping_street" name="shipping_street" required>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_city" class="form-label">Town/City <span>*</span></label>
                                <input type="text" class="form-control" id="shipping_city" name="shipping_city" required>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_state" class="form-label">State/County <span>*</span></label>
                                <input type="text" class="form-control" id="shipping_state" name="shipping_state" required>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_postcode" class="form-label">Postcode/ZIP <span>*</span></label>
                                <input type="text" class="form-control" id="shipping_postcode" name="shipping_postcode" required>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_country" class="form-label">Country <span>*</span></label>
                                <select class="form-select" disabled id="shipping_country" name="shipping_country" required>
                                    <option value="GB" selected>United Kingdom</option>
                      
                                </select>
                            </div>
                        </div>

                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" value="1" id="billingDifferent">
                            <label class="form-check-label fw-semibold" for="billingDifferent">
                                Billing address is different?
                            </label>
                        </div>
                    </section>

                    <section class="checkout-panel billing-panel d-none" id="billingPanel">
                        <h2>Billing Address</h2>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="billing_first_name" class="form-label">First name</label>
                                <input type="text" class="form-control billing-field" id="billing_first_name" name="billing_first_name">
                            </div>
                            <div class="col-md-6">
                                <label for="billing_last_name" class="form-label">Last name</label>
                                <input type="text" class="form-control billing-field" id="billing_last_name" name="billing_last_name">
                            </div>
                            <div class="col-12">
                                <label for="billing_company" class="form-label">Company name</label>
                                <input type="text" class="form-control" id="billing_company" name="billing_company">
                            </div>
                            <div class="col-12">
                                <label for="billing_street" class="form-label">Street address</label>
                                <input type="text" class="form-control billing-field" id="billing_street" name="billing_street">
                            </div>
                            <div class="col-md-6">
                                <label for="billing_city" class="form-label">Town/City</label>
                                <input type="text" class="form-control billing-field" id="billing_city" name="billing_city">
                            </div>
                            <div class="col-md-6">
                                <label for="billing_state" class="form-label">State/County</label>
                                <input type="text" class="form-control billing-field" id="billing_state" name="billing_state">
                            </div>
                            <div class="col-md-6">
                                <label for="billing_postcode" class="form-label">Postcode/ZIP</label>
                                <input type="text" class="form-control billing-field" id="billing_postcode" name="billing_postcode">
                            </div>
                            <div class="col-md-6">
                                <label for="billing_country"  class="form-label">Country</label>
                                <select class="form-select billing-field" id="billing_country" disabled name="billing_country">
                                    <option value="GB" selected>United Kingdom</option>
                             
                                </select>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-lg-5">
                    <aside class="checkout-panel order-panel">
                        <h2>Your Order</h2>

                        <div id="checkout-loading" class="text-center py-4">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 mb-0">Loading your order...</p>
                        </div>

                        <div id="checkout-order" class="d-none">
                            <div class="order-head">
                                <span>Product</span>
                                <span class="text-center">Qty</span>
                                <span class="text-end">Price</span>
                                <span>Total</span>
                            </div>
                            <div id="checkout-items" class="checkout-items"></div>

                            <div class="order-total-row">
                                <span>Subtotal</span>
                                <strong id="checkout-subtotal">£0.00</strong>
                            </div>
                            <div class="order-total-row">
                                <span>Shipping</span>
                                <strong>Free</strong>
                            </div>
                            <div class="order-grand-total">
                                <span>Total</span>
                                <strong id="checkout-total">£0.00</strong>
                            </div>

                       <div class="payment-method mt-4">
    <h3>Payment Method</h3>
    <label class="form-label mb-3">Select Payment Method <span>*</span></label>
    
    <select class="d-none" id="payment_method" name="payment_method" required>
        <option value="">Select Here</option>
        <option value="paypal">PayPal Checkout</option>
        <option value="stripe">Stripe</option>
    </select>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6">
            <div class="payment-card-option" data-target="paypal">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="custom-radio-circle"></div>
                        <span class="fw-semibold">PayPal</span>
                    </div>
                    <span class="payment-icon"><i class="bi bi-paypal fs-4"></i></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6">
            <div class="payment-card-option" data-target="stripe">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="custom-radio-circle"></div>
                        <span class="fw-semibold">Credit Card </span>
                    </div>
                    <span class="payment-icon"><i class="bi bi-credit-card-2-front fs-4"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div id="paypalPanel" class="payment-panel paypal-panel d-none">
        <button type="submit" class="paypal-checkout-btn">
            <span class="paypal-mark">P</span>
            <strong>PayPal</strong>
            <span>Checkout</span>
        </button>
        <p class="text-muted mt-2 small">The safer, easier way to pay</p>
    </div>

    <div id="stripePanel" class="payment-panel stripe-panel d-none">
        <button type="submit" class="stripe-checkout-btn btn w-100 mt-2" style="background-color: #635bff; border: none; color: white; padding: 12px 20px; font-weight: 600; border-radius: 6px;">
            <i class="bi bi-credit-card-2-front me-2"></i>
            <strong>Stripe Checkout</strong>
        </button>
        <p class="text-muted mt-2 small">Fast, secure payments powered by Stripe</p>
    </div>
</div>
                        </div>
                    </aside>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
const checkoutCurrency = new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP'
});

const checkoutForm = document.getElementById('checkout-form');
const checkoutEmpty = document.getElementById('checkout-empty');
const checkoutLoading = document.getElementById('checkout-loading');
const checkoutOrder = document.getElementById('checkout-order');
const checkoutItems = document.getElementById('checkout-items');
const billingDifferent = document.getElementById('billingDifferent');
const billingPanel = document.getElementById('billingPanel');
const billingFields = document.querySelectorAll('.billing-field');
const paymentMethod = document.getElementById('payment_method');
const paypalPanel = document.getElementById('paypalPanel');
const stripePanel = document.getElementById('stripePanel');
const stripeFields = [];  // No longer using stripe form fields
const checkoutApiUrl = '/shop/includes/checkout-api.php';

// Global variable cart items hold karne ke liye
let globalCartItems = [];
let globalCartTotal = 0;

document.addEventListener('DOMContentLoaded', () => {
    loadCheckoutCart();
});

billingDifferent.addEventListener('change', () => {
    const isDifferent = billingDifferent.checked;
    billingPanel.classList.toggle('d-none', !isDifferent);
    billingFields.forEach((field) => {
        field.required = isDifferent;
    });
});

paymentMethod.addEventListener('change', updatePaymentPanel);

function updatePaymentPanel() {
    const selectedPaymentMethod = paymentMethod.value;
    const isPaypal = selectedPaymentMethod === 'paypal';
    const isStripe = selectedPaymentMethod === 'stripe';

    paypalPanel.classList.toggle('d-none', !isPaypal);
    stripePanel.classList.toggle('d-none', !isStripe);
}

function loadCheckoutCart() {
    cartManager.load()
        .then((data) => {
            // Data ko global variables mein save kar rahe hain taake submission ke waqt milein
            globalCartItems = data.items || [];
            globalCartTotal = data.total || 0;
            renderCheckoutCart(data);
        })
        .catch((error) => {
            console.error('Checkout cart load failed:', error);
            checkoutLoading.innerHTML = '<p class="text-danger mb-0">Could not load your order. Please try again.</p>';
        });
}

function renderCheckoutCart(data) {
    const items = data.items || [];
    checkoutLoading.classList.add('d-none');

    if (!items.length) {
        checkoutForm.classList.add('d-none');
        checkoutEmpty.classList.remove('d-none');
        return;
    }

    checkoutOrder.classList.remove('d-none');

    checkoutItems.innerHTML = items.map((item) => {
        const price = Number(item.price || 0);
        const qty = Number(item.qty || 1);
        const lineTotal = price * qty;

        return `
            <div class="checkout-item">
                <div class="checkout-item__product">
                    <strong>${escapeCheckoutHtml(item.name || 'Product')}</strong>
                </div>
                <span class="checkout-item__qty">${qty}</span>
                <span class="checkout-item__price">${checkoutCurrency.format(price)}</span>
                <strong class="checkout-item__total">${checkoutCurrency.format(lineTotal)}</strong>
            </div>
        `;
    }).join('');

    document.getElementById('checkout-subtotal').textContent = checkoutCurrency.format(Number(globalCartTotal));
    document.getElementById('checkout-total').textContent = checkoutCurrency.format(Number(globalCartTotal));
}

function escapeCheckoutHtml(value) {
    return String(value).replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));
}


checkoutForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (!checkoutForm.checkValidity()) {
        checkoutForm.reportValidity();
        return;
    }

    const selectedPayment = paymentMethod.value;
    const submitButton = selectedPayment === 'stripe'
        ? stripePanel.querySelector('button[type="submit"]')
        : paypalPanel.querySelector('button[type="submit"]');

    if (!selectedPayment) {
        alert('Please select a payment method.');
        return;
    }

    const payload = {
        payment_method: selectedPayment,
        shipping_first_name: document.getElementById('shipping_first_name').value.trim(),
        shipping_last_name: document.getElementById('shipping_last_name').value.trim(),
        shipping_company: document.getElementById('shipping_company').value.trim(),
        shipping_street: document.getElementById('shipping_street').value.trim(),
        shipping_city: document.getElementById('shipping_city').value.trim(),
        shipping_state: document.getElementById('shipping_state').value.trim(),
        shipping_postcode: document.getElementById('shipping_postcode').value.trim(),
        shipping_country: document.getElementById('shipping_country').value,
        billing_different: billingDifferent.checked ? 1 : 0,
        billing_first_name: (billingDifferent.checked ? document.getElementById('billing_first_name').value : document.getElementById('shipping_first_name').value).trim(),
        billing_last_name: (billingDifferent.checked ? document.getElementById('billing_last_name').value : document.getElementById('shipping_last_name').value).trim(),
        billing_company: (billingDifferent.checked ? document.getElementById('billing_company').value : document.getElementById('shipping_company').value).trim(),
        billing_street: (billingDifferent.checked ? document.getElementById('billing_street').value : document.getElementById('shipping_street').value).trim(),
        billing_city: (billingDifferent.checked ? document.getElementById('billing_city').value : document.getElementById('shipping_city').value).trim(),
        billing_state: (billingDifferent.checked ? document.getElementById('billing_state').value : document.getElementById('shipping_state').value).trim(),
        billing_postcode: (billingDifferent.checked ? document.getElementById('billing_postcode').value : document.getElementById('shipping_postcode').value).trim(),
        billing_country: billingDifferent.checked ? document.getElementById('billing_country').value : document.getElementById('shipping_country').value,
        billing_email: document.getElementById('billing_email').value.trim(),
        billing_phone: document.getElementById('billing_phone').value.trim(),
        items: globalCartItems.map((item) => ({
            product_id: item.product_id || item.id,
            qty: Number(item.qty || 1)
        })),
        total: globalCartTotal,
        shipping: {
            first_name: document.getElementById('shipping_first_name').value.trim(),
            last_name: document.getElementById('shipping_last_name').value.trim(),
            company: document.getElementById('shipping_company').value.trim(),
            address: document.getElementById('shipping_street').value.trim(),
            city: document.getElementById('shipping_city').value.trim(),
            state: document.getElementById('shipping_state').value.trim(),
            postcode: document.getElementById('shipping_postcode').value.trim(),
            country: document.getElementById('shipping_country').value
        },
        billing: {
            first_name: (billingDifferent.checked ? document.getElementById('billing_first_name').value : document.getElementById('shipping_first_name').value).trim(),
            last_name: (billingDifferent.checked ? document.getElementById('billing_last_name').value : document.getElementById('shipping_last_name').value).trim(),
            company: (billingDifferent.checked ? document.getElementById('billing_company').value : document.getElementById('shipping_company').value).trim(),
            address: (billingDifferent.checked ? document.getElementById('billing_street').value : document.getElementById('shipping_street').value).trim(),
            city: (billingDifferent.checked ? document.getElementById('billing_city').value : document.getElementById('shipping_city').value).trim(),
            state: (billingDifferent.checked ? document.getElementById('billing_state').value : document.getElementById('shipping_state').value).trim(),
            postcode: (billingDifferent.checked ? document.getElementById('billing_postcode').value : document.getElementById('shipping_postcode').value).trim(),
            country: billingDifferent.checked ? document.getElementById('billing_country').value : document.getElementById('shipping_country').value,
            email: document.getElementById('billing_email').value.trim(),
            phone: document.getElementById('billing_phone').value.trim()
        }
    };

    try {
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.dataset.originalText = submitButton.innerHTML;
            submitButton.innerHTML = 'Creating order...';
        }

        // For Stripe, redirect to Stripe Checkout
        if (selectedPayment === 'stripe') {
            const orderRes = await fetch(`${checkoutApiUrl}?action=create_order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const orderData = await orderRes.json();

            if (!orderRes.ok || !orderData.success || !orderData.order_id) {
                throw new Error(orderData.message || 'Failed to create WooCommerce order');
            }

            if (submitButton) {
                submitButton.innerHTML = 'Redirecting to Stripe...';
            }

            const stripeRes = await fetch(`${checkoutApiUrl}?action=stripe_checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderData.order_id })
            });

            const stripeData = await stripeRes.json();

            if (stripeRes.ok && stripeData.success && stripeData.checkout_url) {
                window.location.href = stripeData.checkout_url;
                return;
            } else {
                throw new Error(stripeData.message || 'Failed to create Stripe checkout session');
            }
        } 
        // For PayPal, process normally or redirect
        else if (selectedPayment === 'paypal') {
            const res = await fetch('/shop/includes/paypal-payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (data.success) {
                alert('Order placed successfully!');
                console.log(data);
                cartManager.clear();
                window.location.href = 'https://www.recyclepro.co.uk/shop/order-confirmation/?order_id=' + data.order_id;
            } else {
                alert('Order failed: ' + (data.message || 'Unknown Error'));
            }
        }

    } catch (error) {
        console.error(error);
        alert('API error: ' + error.message);
    } finally {
        if (submitButton && !document.location.href.includes('checkout.stripe.com')) {
            submitButton.disabled = false;
            if (submitButton.dataset.originalText) {
                submitButton.innerHTML = submitButton.dataset.originalText;
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const paymentCards = document.querySelectorAll('.payment-card-option');
    const hiddenSelect = document.getElementById('payment_method');

    // Set Stripe as default selected payment method
    const stripeCard = document.querySelector('.payment-card-option[data-target="stripe"]');
    if (stripeCard) {
        stripeCard.classList.add('selected');
        hiddenSelect.value = 'stripe';
        updatePaymentPanel();
    }

    paymentCards.forEach(card => {
        card.addEventListener('click', () => {

            paymentCards.forEach(c => c.classList.remove('selected'));
            
          
            card.classList.add('selected');
            

            const targetValue = card.getAttribute('data-target');
            hiddenSelect.value = targetValue;
            

            updatePaymentPanel();
        });
    });
});
</script>

<script src="https://js.stripe.com/v3/"></script>

<script>
let stripe = null;

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Stripe
    stripe = Stripe('pk_test_HfujjRKczwxJUwv5AAUl8aW400vLww7gX5');
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>