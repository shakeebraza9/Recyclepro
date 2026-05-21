<?php
$pageTitle = 'View Cart';
include __DIR__ . '/includes/header.php';
?>

<main class="cart-page py-5">
    <div class="container">
    <div class="cart-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="cart-eyebrow">Shopping Bag</span>
            <h1 class="mb-1">Your Cart</h1>
            <p class="mb-0">Review selected products, update quantities, and confirm your total.</p>
        </div>
        <a href="/shop/category" class="btn btn-outline-dark cart-continue-btn">
            <i class="bi bi-arrow-left"></i>
            Continue Shopping
        </a>
    </div>

    <div id="cart-loading" class="cart-state text-center py-5">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 mb-0">Loading your cart...</p>
    </div>

    <div id="empty-cart" class="cart-empty cart-state text-center py-5 d-none">
        <i class="bi bi-cart-x display-1"></i>
        <h3 class="mt-3">Your cart is empty</h3>
        <p class="text-muted">Add a product and it will appear here.</p>
        <a href="/shop/category" class="btn btn-dark">Shop Products</a>
    </div>

    <form id="cart-form" class="d-none">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-responsive cart-table-wrap">
                    <table class="table align-middle mb-0 cart-table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col" class="text-center">Quantity</th>
                                <th scope="col" class="text-end">Price</th>
                                <th scope="col" class="text-end">Total</th>
                                <th scope="col" class="text-end">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items"></tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary">
                    <span class="cart-summary-label">Order Summary</span>
                    <h5 class="mb-3">Cart Total</h5>
                    <div class="cart-summary-row">
                        <span>Items</span>
                        <strong id="summary-count">0</strong>
                    </div>
                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <strong id="summary-subtotal">£0.00</strong>
                    </div>
                    <div class="cart-summary-row">
                        <span>Shipping</span>
                        <strong>Free</strong>
                    </div>
                    <hr>
                    <div class="cart-summary-total">
                        <span>Total</span>
                        <strong id="summary-total">£0.00</strong>
                    </div>
                    <a href="/shop/checkout" class="btn btn-dark btn-lg w-100 mb-2">Proceed to Checkout</a>
                    <button type="button" class="btn btn-outline-secondary w-100" id="clear-cart">Clear Cart</button>
                </div>
            </div>
        </div>
    </form>
    </div>
</main>

<script>
const currency = new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP'
});

document.addEventListener('DOMContentLoaded', () => {
    loadCartView();
    document.getElementById('clear-cart').addEventListener('click', clearCart);
});

function loadCartView() {
    cartManager.load()
        .then(renderCart)
        .catch((err) => {
            console.error('Cart load failed:', err);
            document.getElementById('cart-loading').innerHTML = '<p class="text-danger mb-0">Could not load your cart. Please try again.</p>';
        });
}

function renderCart(data) {
    const items = data.items || [];
    const cartLoading = document.getElementById('cart-loading');
    const emptyCart = document.getElementById('empty-cart');
    const cartForm = document.getElementById('cart-form');
    const cartItems = document.getElementById('cart-items');

    cartLoading.classList.add('d-none');

    if (!items.length) {
        cartForm.classList.add('d-none');
        emptyCart.classList.remove('d-none');
        cartItems.innerHTML = '';
        return;
    }

    emptyCart.classList.add('d-none');
    cartForm.classList.remove('d-none');

    cartItems.innerHTML = items.map((item, index) => {
        const price = Number(item.price || 0);
        const qty = Number(item.qty || 1);
        const lineTotal = price * qty;
        const productUrl = item.permalink || '#';

        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3 cart-product">
                        <img src="${escapeAttribute(item.image || '')}" alt="${escapeAttribute(item.name || 'Product')}" class="cart-product-img">
                        <div>
                            <a href="${escapeAttribute(productUrl)}" class="cart-product-name">${escapeHtml(item.name || 'Product')}</a>
                            <div class="cart-product-meta">Unit price: ${currency.format(price)}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <input type="number" class="form-control cart-qty mx-auto" min="1" value="${qty}" data-index="${index}" aria-label="Quantity for ${escapeAttribute(item.name || 'Product')}">
                </td>
                <td class="text-end">${currency.format(price)}</td>
                <td class="text-end fw-semibold">${currency.format(lineTotal)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger cart-remove" data-index="${index}" aria-label="Remove ${escapeAttribute(item.name || 'Product')}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');

    document.getElementById('summary-count').textContent = data.count || 0;
    document.getElementById('summary-subtotal').textContent = currency.format(Number(data.total || 0));
    document.getElementById('summary-total').textContent = currency.format(Number(data.total || 0));

    document.querySelectorAll('.cart-qty').forEach((input) => {
        input.addEventListener('change', () => {
            const qty = Math.max(1, parseInt(input.value, 10) || 1);
            input.value = qty;
            cartManager.updateQuantity(input.dataset.index, qty).then(renderCart);
        });
    });

    document.querySelectorAll('.cart-remove').forEach((button) => {
        button.addEventListener('click', () => {
            cartManager.removeItem(button.dataset.index).then(renderCart);
        });
    });
}

function clearCart() {
    cartManager.clearCart().then(renderCart);
}

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));
}

function escapeAttribute(value) {
    return escapeHtml(value).replace(/`/g, '&#096;');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
