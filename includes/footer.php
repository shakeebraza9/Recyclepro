<?php
$config = require_once __DIR__ . '/config.php';
$baseAPI = $config['API_URL'] ?? '';
?>
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h5>Privacy Policy</h5>
                <ul class="list-unstyled">
                    <li><a href="/shop/delivery-terms" class="text-white-50 text-decoration-none">Delivery Terms</a></li>
                    <li><a href="/shop/terms-conditions" class="text-white-50 text-decoration-none">Terms Conditions</a></li>
                    <li><a href="/shop/privacy-policy" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                    <li><a href="/shop/cookies-policy" class="text-white-50 text-decoration-none">Cookies Policy</a></li>
                    <li><a href="/shop/return-policy-warranty" class="text-white-50 text-decoration-none">Return Policy &amp; Warranty</a></li>
                    <li><a href="/shop/legal-hub-page" class="text-white-50 text-decoration-none">Legal Hub</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Get Involved</h5>
                <ul class="list-unstyled">
                       <li><a href="/shop/about-us" class="text-white-50 text-decoration-none">About Us</a></li>
                    <!-- <li><a href="/shop/orders-shipping" class="text-white-50 text-decoration-none">Orders &amp; Shipping</a></li> -->
                    <!-- <li><a href="/shop/affiliate-program" class="text-white-50 text-decoration-none">Affiliate Program</a></li> -->
                    <li><a href="/shop/shipment-payment" class="text-white-50 text-decoration-none">Shipment &amp; Payment</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled" id="quicklinks">
                    
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Customer Care</h5>
                <ul class="list-unstyled">
                <li><a href="/shop/contact-us" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    <!-- <li><a href="/shop/customer-service" class="text-white-50 text-decoration-none">Customer Service</a></li> -->
                    <li><a href="/shop/frequent-questions" class="text-white-50 text-decoration-none">Frequent Questions</a></li>
                    <!-- <li><a href="/shop/frequently-asked-questions" class="text-white-50 text-decoration-none">Frequently Asked Questions</a></li> -->
                </ul>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="row align-items-center">
            <div class="col-md-3">
                <img src="/shop/img/logo white f.png" alt="PayPal" style="height: 100px;" class="me-2 img-fluid">
            </div>
            <div class="col-md-6 text-center">
                <p class="mb-0" style="font-size: 21px;">
                    
                    <i class="bi bi-envelope"></i> order@recyclerpro.co.uk
                </p>
            </div>
            <div class="col-md-3 text-end">
                <span class="me-2">Follow Us:</span>
                <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white me-2"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="text-white me-2"><i class="bi bi-tiktok"></i></a>
                <a href="#" class="text-white me-2"><i class="bi bi-youtube"></i></a>
            </div>
        </div>

                    <div class="footer-bottom-bar">
                <p class="copyright-txt">&copy; 2026 RECYCLEPRO, ALL RIGHTS RESERVED</p>
                
                <div class="payment-icons">
                    <img src=<?php echo $baseAPI. "/shop/img/cards/pay_maestro.png"; ?> alt="Maestro">
                    <img src=<?php echo $baseAPI . "/shop/img/cards/pay_mastercard.png"; ?> alt="Mastercard">
                    <img src=<?php echo $baseAPI . "/shop/img/cards/pay_amex.png"; ?> alt="American Express">
                    <img src=<?php echo $baseAPI . "/shop/img/cards/pay_visa.avif"; ?> alt="Visa">
                    <img src=<?php echo $baseAPI . "/shop/img/cards/pay_paypal.webp"; ?> alt="PayPal">
                    <!-- <img src="storage/images/pay_ideal.png" alt="iDEAL"> -->
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
