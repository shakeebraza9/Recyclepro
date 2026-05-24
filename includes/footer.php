<?php
$config = require_once __DIR__ . '/config.php';
$baseAPI = $config['API_URL'] ?? '';
?>
<footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-sm-6 col-md-3">
                <h5 class="text-uppercase mb-3 tracking-wide text-white font-semibold">Privacy Policy</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/shop/delivery-terms" class="text-white-50 text-decoration-none footer-link">Delivery Terms</a></li>
                    <li class="mb-2"><a href="/shop/terms-conditions" class="text-white-50 text-decoration-none footer-link">Terms Conditions</a></li>
                    <li class="mb-2"><a href="/shop/privacy-policy" class="text-white-50 text-decoration-none footer-link">Privacy Policy</a></li>
                    <li class="mb-2"><a href="/shop/cookies-policy" class="text-white-50 text-decoration-none footer-link">Cookies Policy</a></li>
                    <li class="mb-2"><a href="/shop/return-policy-warranty" class="text-white-50 text-decoration-none footer-link">Return & Warranty</a></li>
                    <li class="mb-2"><a href="/shop/legal-hub-page" class="text-white-50 text-decoration-none footer-link">Legal Hub</a></li>
                </ul>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <h5 class="text-uppercase mb-3 tracking-wide text-white font-semibold">Get Involved</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/shop/about-us" class="text-white-50 text-decoration-none footer-link">About Us</a></li>
                    <li class="mb-2"><a href="/shop/shipment-payment" class="text-white-50 text-decoration-none footer-link">Shipment & Payment</a></li>
                </ul>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <h5 class="text-uppercase mb-3 tracking-wide text-white font-semibold">Quick Links</h5>
                <ul class="list-unstyled mb-0" id="quicklinks">
                    </ul>
            </div>
            
            <div class="col-sm-6 col-md-3">
                <h5 class="text-uppercase mb-3 tracking-wide text-white font-semibold">Customer Care</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/shop/contact-us" class="text-white-50 text-decoration-none footer-link">Contact Us</a></li>
                    <li class="mb-2"><a href="/shop/frequent-questions" class="text-white-50 text-decoration-none footer-link">Frequent Questions</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4 border-secondary">
        
        <div class="row align-items-center justify-content-between g-3 text-center text-md-start">
            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-start align-items-center header-brand">
                <a href="/shop/" class="header-logo-link">
                    <img class="logo img-fluid" src="/shop/img/rplogo.png" alt="Recycle Pro Logo" style="max-height: 50px;">
                </a>
                <button class="mobile-menu-toggle d-md-none ms-2" type="button" id="mobileMenuToggle" aria-controls="menu" aria-expanded="true" aria-label="Collapse menu">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="col-12 col-md-6 text-center">
                <p class="mb-0 footer-email-text">
                    <i class="bi bi-envelope me-1"></i> order@recyclerpro.co.uk
                </p>
            </div>
            
            <div class="col-12 col-md-3 text-center text-md-end social-container">
                <span class="me-2 text-white-50 small">Follow Us:</span>
                <div class="d-inline-flex gap-2 social-icons">
                    <a href="#" class="text-white footer-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white footer-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white footer-icon"><i class="bi bi-tiktok"></i></a>
                    <a href="#" class="text-white footer-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <div class="row align-items-center g-3 text-center text-sm-between " style="display: flex; display: flex; justify-content: space-between;">
            <div class="col-12 col-sm-auto">
                <p class="copyright-txt mb-0 text-white-50 small">&copy; 2026 RECYCLEPRO. ALL RIGHTS RESERVED.</p>
            </div>
            
            <div class="col-12 col-sm-auto payment-icons d-flex flex-wrap justify-content-center gap-2">
                <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_maestro.png" alt="Maestro" class="img-fluid" style="height: 24px;">
                <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_mastercard.png" alt="Mastercard" class="img-fluid" style="height: 24px;">
                <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_amex.png" alt="American Express" class="img-fluid" style="height: 24px;">
                <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_visa.avif" alt="Visa" class="img-fluid" style="height: 24px;">
                <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_paypal.webp" alt="PayPal" class="img-fluid" style="height: 24px;">
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateHeaderWishlistCount() {
    const badge = $("#globalWishlistCount");
    if (!badge.length) return;

    const wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    
    if (wishlist.length > 0) {
        badge.text(wishlist.length);
        badge.removeClass('d-none'); 
    } else {
        badge.addClass('d-none'); 
    }
}

let debounceTimer;

const $input = $("#header-search-input");
const $btn = $("#header-search-btn");
const $dropdown = $("#search-results-dropdown");



function searchProducts(query) {

    $.ajax({
        url: `${baseAPI}wp-json/custom/v1/search-products`,
        method: 'GET',
        data: { term: query },
        success: function (response) {

            console.log("DEBUG RESPONSE:", response);

            let products = Array.isArray(response) ? response : [response];

            if (products.length > 0) {

                let html = "";

                $.each(products, function (i, product) {

                    html += `
                        <a href="${product.permalink}" class="search-result-item">
                            <img src="${product.image || '/placeholder.png'}">
                            <div class="search-result-info">
                                <h4>${product.name}</h4>
                                <div>£${product.price}</div>
                            </div>
                        </a>
                    `;
                });

                $dropdown.html(html).removeClass("d-none");

            } else {
                $dropdown.html("<div class='p-3 text-center'>No product found</div>")
                         .removeClass("d-none");
            }
        },

        error: function () {
            $dropdown.html("<div class='p-3 text-danger text-center'>API Error</div>")
                     .removeClass("d-none");
        }
    });
}


$btn.on("click", function () {
    let query = $input.val().trim();
    if (!query) return;

    searchProducts(query);
});


$input.on("input", function () {

    let query = $(this).val().trim();

    clearTimeout(debounceTimer);

    if (query.length < 2) {
        $dropdown.addClass("d-none").html("");
        return;
    }

    debounceTimer = setTimeout(function () {
        searchProducts(query);
    }, 300);
});


$input.on("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();

        let first = $(".search-result-item").first();

        if (first.length) {
            window.location.href = first.attr("href");
        }
    }
});


$(document).on("click", function (e) {
    if (!$(e.target).closest(".header-search").length) {
        $dropdown.addClass("d-none");
    }
});


$input.on("focus", function () {
    if ($(this).val().trim().length >= 2) {
        $dropdown.removeClass("d-none");
    }
});

$.ajax({
    url: `${baseAPI}wp-json/wp/v2/categories-tree`,
    method: 'GET',
    success: function (response) {
        
        const quicklinksContainer = document.getElementById('quicklinks');
        if (!quicklinksContainer || !Array.isArray(response)) return;


        quicklinksContainer.innerHTML = '';


        const parentCategories = response.filter(category => {
            const isParent = !category.parent || category.parent === 0;
            const isNotUncategorized = category.slug !== 'uncategorized' && category.name.toLowerCase() !== 'uncategorized';
            return isParent && isNotUncategorized;
        });


        parentCategories.reverse()
        const topCategories = parentCategories.slice(0, 6);

        topCategories.forEach(category => {
            let cleanSlug = (category.slug || '').trim();
            const finalUrl = `${BASE_URL}category/${cleanSlug}/`;

            const li = document.createElement('li');
 
            li.innerHTML = `


                <li><a href="${finalUrl}" class="text-white-50 text-decoration-none">${category.name}</a></li>
            `;
            quicklinksContainer.appendChild(li);
        });
    },
    error: function () {
        console.error("Failed to load header data");
    }
});
</script>
</body>
</html>
