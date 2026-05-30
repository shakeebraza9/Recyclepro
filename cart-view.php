<?php
$pageTitle = 'View Cart';
include __DIR__ . '/includes/header.php';
?>
<style>

.cart-page {
    background-color: #fcfcfc !important;
}

.tracking-wider {
    letter-spacing: 0.06em;
}


.cart-custom-head {
    background-color: #13564f;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
}


.custom-cart-summary .summary-head {
    background-color: #13564f;
}


.custom-cart-card, .custom-cart-summary {
    border-color: #e5e7eb !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
}


.cart-row-item {
    border-bottom-color: #f3f4f6 !important;
    transition: background-color 0.2s ease;
}

.cart-row-item:last-child {
    border-bottom: none !important;
}


.btn-remove-item {
    color: #13564f !important;
    line-height: 1;
    transition: color 0.15s ease;
    margin-right: 5px;
}

.btn-remove-item:hover {
    color: #ef4444 !important;
}


.mockup-qty-container {
    background-color: #ffffff;
    border-color: #e5e7eb !important;
}

.qty-action-btn {
    font-size: 16px;
    line-height: 1;
    color: #9ca3af !important;
    transition: color 0.15s ease;
}

.qty-action-btn:hover {
    color: #111827 !important;
}


.btn-checkout {
    background-color: #052237;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.btn-checkout:hover {
    background-color: #0b3452;
    color: #ffffff;
}

.btn-checkout:active {
    transform: scale(0.99);
}

.summary-item-row span {
    font-size: 13px;
}


.section-title-layout {
    font-size: 22px;
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: -0.02em;
}

.suggestion-product-card {
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}


.suggestion-img-frame {
    background-color: #a6a6a6;
    height: 280px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: filter 0.2s ease;
}

.suggestion-thumb {
    max-height: 200px;
    object-fit: contain;
    transition: transform 0.3s ease;
}


.badge-discount-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    background-color: #2d2d2d;
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    z-index: 2;
}

.suggestion-product-card:hover {
    transform: translateY(-4px);
}

.suggestion-product-card:hover .suggestion-thumb {
    transform: scale(1.05);
}

.suggestion-action-arrow {
    font-size: 18px;
    color: #052237;
    display: inline-flex;
    align-items: center;
    transition: color 0.15s ease, transform 0.15s ease;
}

.suggestion-product-card:hover .suggestion-action-arrow {
    color: #13564f;
    transform: translateX(2px);
}

.mix-blend-multiply {
    mix-blend-mode: multiply;
}
</style>
<main class="cart-page py-5 bg-light-subtle">
    <div class="container">
        
        <div class="cart-hero mb-5">
            <span class="text-uppercase tracking-wider text-muted small fw-semibold">Shopping Bag</span>
            <h1 class="fw-bold text-dark m-0">Your Cart</h1>
        </div>

        <div id="cart-loading" class="cart-state text-center py-5">
            <div class="spinner-border text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0 text-muted small">Loading your cart...</p>
        </div>

        <div id="empty-cart" class="cart-empty cart-state text-center py-5 d-none bg-white border rounded-3 p-5">
            <i class="bi bi-cart-x text-muted display-3 mb-3 d-block"></i>
            <h3 class="fw-bold text-dark">Your cart is empty</h3>
            <p class="text-muted small mb-4">Add a product to start syncing items.</p>
            <a href="/shop/category" class="btn btn-dark px-4 py-2">Shop Products</a>
        </div>

        <form id="cart-form" class="d-none">
            <div class="row g-4 align-items-start">
                
                <div class="col-lg-8">
                    <div class="custom-cart-card border rounded-3 overflow-hidden bg-white">
                        <div class="cart-custom-head d-flex align-items-center justify-content-between px-4 py-3">
                            <div class="head-col text-start flex-grow-1" style="max-width: 45%;">Product</div>
                            <div class="head-col text-center" style="width: 20%;">Price</div>
                            <div class="head-col text-center" style="width: 20%;">Quantity</div>
                            <div class="head-col text-end" style="width: 15%;">Total</div>
                        </div>
                        
                        <div id="cart-items" class="cart-items-body"></div>
                    </div>
                    
                    <div class="text-start mt-3">
                        <button type="button" class="btn btn-link text-muted text-decoration-none small p-0" id="clear-cart">
                            <i class="bi bi-trash3 small me-1"></i> Clear Shopping Bag
                        </button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="custom-cart-summary border rounded-3 overflow-hidden bg-white">
                        <div class="summary-head px-4 py-3">
                            <h2 class="h6 fw-semibold text-white m-0">Cart Total</h2>
                        </div>
                        
                        <div class="summary-body p-4">
                            <div class="summary-item-row d-flex justify-content-between align-items-center mb-3">
                                <span class="text-uppercase text-muted fw-semibold">Subtotal</span>
                                <strong id="summary-subtotal" class="text-dark fw-semibold">£0.00</strong>
                            </div>
                            
                            <div class="summary-item-row d-flex justify-content-between align-items-center mb-3">
                                <span class="text-uppercase text-muted fw-semibold">Discount</span>
                                <span id="summary-discount" class="text-muted">—</span>
                            </div>
                            
                            <hr class="my-4 opacity-10">
                            
                            <div class="summary-item-row d-flex justify-content-between align-items-center mb-4">
                                <span class="text-uppercase text-dark fw-bold">Total</span>
                                <strong id="summary-total" class="text-dark fw-bold fs-5">£0.00</strong>
                            </div>
                            
                            <button type="button" id="checkoutBtn" onclick="handleCheckout()" class="btn btn-checkout w-100 py-3 text-uppercase tracking-wider fw-semibold">
                                Proceed To Checkout
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div id="cart-suggestions-section" class="similar-products-wrap mt-5 pt-4 d-none">
            <h2 class="h4 fw-bold text-dark mb-4 section-title-layout">You May Also Like</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4" id="suggested-products-grid">
                </div>
        </div>
        
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
            console.error('Cart load error asset:', err);
            document.getElementById('cart-loading').innerHTML = '<p class="text-danger small py-3">Could not sync shopping view template right now.</p>';
        });
}

function renderCart(data) {
    const items = data.items || [];
    const cartLoading = document.getElementById('cart-loading');
    const emptyCart = document.getElementById('empty-cart');
    const cartForm = document.getElementById('cart-form');
    const cartItems = document.getElementById('cart-items');
    
    const suggestionsSection = document.getElementById('cart-suggestions-section');
    const suggestionsGrid = document.getElementById('suggested-products-grid');

    cartLoading.classList.add('d-none');

    if (!items.length) {
        cartForm.classList.add('d-none');
        suggestionsSection.classList.add('d-none');
        emptyCart.classList.remove('d-none');
        cartItems.innerHTML = '';
        return;
    }

    emptyCart.classList.add('d-none');
    cartForm.classList.remove('d-none');
    suggestionsSection.classList.remove('d-none');


    cartItems.innerHTML = items.map((item, index) => {
        const price = Number(item.price || 0);
        const qty = Number(item.qty || 1);
        const lineTotal = price * qty;
        

        let productUrl = item.permalink || '#';
        if (productUrl.includes('shop//buy/')) {
            productUrl = productUrl.replace('shop//buy/', 'shop/buy/');
        }

        return `
            <div class="cart-row-item d-flex align-items-center justify-content-between px-4 py-4 border-bottom">
                
                <div class="d-flex align-items-center gap-3" style="max-width: 45%; flex-grow: 1;">
                    <button type="button" class="btn-remove-item cart-remove p-0 border-0 bg-transparent text-muted" data-index="${index}" aria-label="Remove item">
                        <i class="bi bi-x fs-4"></i>
                    </button>
                    <div class="product-thumb-container bg-light rounded d-flex align-items-center justify-content-center p-2" style="width: 64px; height: 64px; flex-shrink: 0;">
                        <img src="${escapeAttribute(item.image || '')}" alt="${escapeAttribute(item.name || 'Device')}" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                    </div>
                    <div class="text-truncate">
                        <a href="${escapeAttribute(productUrl)}" class="text-decoration-none text-dark fw-semibold small d-block text-truncate" title="${escapeHtml(item.name)}">
                            ${escapeHtml(item.name || 'Product Device')}
                        </a>
                    </div>
                </div>

                <div class="text-center text-muted small fw-medium" style="width: 20%;">
                    ${currency.format(price)}
                </div>

                <div class="text-center d-flex align-items-center justify-content-center" style="width: 20%;">
                    <div class="mockup-qty-container d-flex align-items-center justify-content-between border rounded-pill px-2 py-1" style="width: 100px;">
                        <button type="button" class="qty-action-btn dec-qty border-0 bg-transparent p-0 px-1 text-muted" data-index="${index}">-</button>
                        <span class="qty-display text-dark fw-medium small">${qty}</span>
                        <button type="button" class="qty-action-btn inc-qty border-0 bg-transparent p-0 px-1 text-muted" data-index="${index}">+</button>
                    </div>
                </div>

                <div class="text-end text-muted small fw-medium" style="width: 15%;">
                    ${currency.format(lineTotal)}
                </div>

            </div>
        `;
    }).join('');

 
    const dynamicSuggestions = data.suggestions || items; 
    
    suggestionsGrid.innerHTML = dynamicSuggestions.slice(0, 4).map((prod) => {
        const productPrice = Number(prod.price || 0);
        const dummyOldPrice = productPrice + 30; 
        
        let targetUrl = prod.permalink || '#';
        if (targetUrl.includes('shop//buy/')) {
            targetUrl = targetUrl.replace('shop//buy/', 'shop/buy/');
        }

        return `
            <div class="col">
                <div class="suggestion-product-card position-relative">
                    <span class="badge-discount-tag">-13%</span>
                    
                    <a href="${escapeAttribute(targetUrl)}" class="text-decoration-none d-block">
                        <div class="suggestion-img-frame rounded-3 mb-3 p-3 d-flex align-items-center justify-content-center">
                            <img src="${escapeAttribute(prod.image || '')}" alt="${escapeAttribute(prod.name)}" class="img-fluid mix-blend-multiply suggestion-thumb">
                        </div>
                        
                        <div class="suggestion-meta px-1">
                            <h3 class="h6 text-dark fw-semibold text-truncate mb-1">${escapeHtml(prod.name)}</h3>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="price-block-wrapper">
                                    <span class="text-muted text-decoration-line-through small me-2">${currency.format(dummyOldPrice)}</span>
                                    <span class="text-dark fw-bold">${currency.format(productPrice)}</span>
                                </div>
                                <span class="suggestion-action-arrow">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        `;
    }).join('');

 
    document.getElementById('summary-subtotal').textContent = currency.format(Number(data.total || 0));
    document.getElementById('summary-total').textContent = currency.format(Number(data.total || 0));


    bindQuantityTriggers(items);
}

function bindQuantityTriggers(items) {

    document.querySelectorAll('.inc-qty').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
    document.querySelectorAll('.dec-qty').forEach(btn => btn.replaceWith(btn.cloneNode(true)));
    document.querySelectorAll('.cart-remove').forEach(btn => btn.replaceWith(btn.cloneNode(true)));

    document.querySelectorAll('.inc-qty').forEach(btn => {
        btn.addEventListener('click', () => {
            const index = btn.dataset.index;
            cartManager.updateQuantity(index, Number(items[index].qty || 1) + 1).then(renderCart);
        });
    });

    document.querySelectorAll('.dec-qty').forEach(btn => {
        btn.addEventListener('click', () => {
            const index = btn.dataset.index;
            const currentQty = Number(items[index].qty || 1);
            if (currentQty > 1) {
                cartManager.updateQuantity(index, currentQty - 1).then(renderCart);
            }
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
    return String(value).replace(/[&<>"']/g, (char) => {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char];
    });
}

function escapeAttribute(value) {
    return escapeHtml(value).replace(/`/g, '&#096;');
}

function handleCheckout() {

    const userAccount = localStorage.getItem('recycleproAccount');

    if (userAccount) {

        window.location.href = "/shop/checkout";
    } else {

        const accountModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('accountModal'));
        accountModal.show();
        showToast("You need to be logged in before you can checkout",'warning');
        
        sessionStorage.setItem('redirectAfterLogin', '/shop/checkout');
    }
}

function checkPostLoginRedirect() {
    const redirectTo = sessionStorage.getItem('redirectAfterLogin');
    if (redirectTo) {
        sessionStorage.removeItem('redirectAfterLogin'); 
        window.location.href = redirectTo; 
    }
}
</script>



<?php include __DIR__ . '/includes/footer.php'; ?>