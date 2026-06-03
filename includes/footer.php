<?php
$config = require_once __DIR__ . '/../config.php';
$baseAPI = $config['API_URL'] ?? '';
?>
<footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-sm-6 col-md-3">
                <h5 class="text-uppercase mb-3 tracking-wide text-white font-semibold">Privacy Policy</h5>
                <ul class="list-unstyled mb-0">
                    
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
                    <li class="mb-2"><a href="/shop/frequently-asked-questions" class="text-white-50 text-decoration-none footer-link">Frequent Questions</a></li>
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
                    <a href="https://www.facebook.com/RecycleProUK" target="_blank" class="text-white footer-icon">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="https://www.instagram.com/recycleprouk/?hl=en" target="_blank" class="text-white footer-icon">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="https://uk.pinterest.com/recyclepro/" target="_blank" class="text-white footer-icon">
                        <i class="bi bi-pinterest"></i>
                    </a>

                    <a href="https://www.youtube.com/channel/UCyM1xzNK8YzVFNYmFEIBH5A" target="_blank" class="text-white footer-icon">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <div class="row align-items-center g-3 text-center text-sm-between " style="display: flex; display: flex; justify-content: space-between;">
            <div class="col-12 col-sm-auto">
                <p class="copyright-txt mb-0 text-white-50 small">&copy; 2026 RECYCLEPRO. ALL RIGHTS RESERVED.</p>
            </div>
            
            <div class="col-12 col-sm-auto payment-icons d-flex flex-wrap justify-content-center gap-2">
    <div class="payment-card-box">
        <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_maestro.png" alt="Maestro" class="img-fluid">
    </div>
    <div class="payment-card-box">
        <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_mastercard.png" alt="Mastercard" class="img-fluid">
    </div>
    <div class="payment-card-box">
        <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_amex.png" alt="American Express" class="img-fluid">
    </div>
    <div class="payment-card-box">
        <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_visa.avif" alt="Visa" class="img-fluid">
    </div>
    <div class="payment-card-box">
        <img src="<?php echo $baseAPI; ?>/shop/img/cards/pay_paypal.webp" alt="PayPal" class="img-fluid">
    </div>
</div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo (isset($config['BASE_URL']) && is_array($config)) ? $config['BASE_URL'] : '/shop'; ?>/assets/js/script.js"></script>
<script>
    (function() {
        const toastScript = document.createElement('script');
        const rootPath = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
        
        toastScript.src = rootPath + "assets/js/toast.js";
        document.body.appendChild(toastScript);
    })();
</script>
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
    const $dropdown = $("#search-results-dropdown"); // Ensure this variable is defined

    $.ajax({
        url: `${baseAPI}wp-json/custom/v1/search-products`,
        method: 'GET',
        data: { term: query },
        success: function (response) {
            console.log("DEBUG RESPONSE:", response);

            let products = Array.isArray(response) ? response : [response];

            if (products.length > 0 && products[0] !== null) {
                let html = "";

                $.each(products, function (i, product) {
                    if (!product) return;
                    
                    html += `
                        <a href="${product.permalink || '#'}" class="search-result-item">
                            <img src="${product.image || '/placeholder.png'}" alt="${product.name}">
                            <div class="search-result-info">
                                <h4 class="search-result-title">${product.name}</h4>
                                <div class="search-result-price">£${product.price}</div>
                            </div>
                        </a>
                    `;
                });

                $dropdown.html(html).removeClass("d-none");

            } else {
                $dropdown.html(`
                    <div class="search-dropdown-status">
                        <i class="bi bi-search text-muted d-block fs-3 mb-2"></i>
                        <span>No products found matching "${query}"</span>
                    </div>
                `).removeClass("d-none");
            }
        },

        error: function () {
            $dropdown.html(`
                <div class="search-dropdown-status text-danger">
                    <i class="bi bi-exclamation-circle d-block fs-3 mb-2"></i>
                    <span>Failed to fetch results. API Error.</span>
                </div>
            `).removeClass("d-none");
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


                <li class="mb-2"><a href="${finalUrl}" class="text-white-50 text-decoration-none">${category.name}</a></li>
            `;
            quicklinksContainer.appendChild(li);
        });
    },
    error: function () {
        console.error("Failed to load header data");
    }
});

$(document).ready(function() {
    $(document).on('click', '.toggle-password', function() {
        var passwordInput = $(this).closest('.input-group').find('.password-input');
        var icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});

document.getElementById('triggerForgotModal').addEventListener('click', function() {

    var loginModalEl = document.getElementById('accountModal');
    var loginModal = bootstrap.Modal.getInstance(loginModalEl);
    if (loginModal) {
        loginModal.hide();
    }
    

    var forgotModalEl = document.getElementById('forgotPasswordModal');
    var forgotModal = new bootstrap.Modal(forgotModalEl);
    forgotModal.show();
});


$(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(e) {
        e.preventDefault();

        var emailInput = $('#forgotEmail').val();
        var $submitBtn = $('#forgotSubmitBtn');
        var $statusBox = $('#forgotStatus');
        var $btnText = $submitBtn.find('.btn-text');
        var $spinner = $submitBtn.find('.spinner-border');


        $submitBtn.prop('disabled', true);
        $btnText.text('Verifying User...');
        $spinner.removeClass('d-none');
        $statusBox.hide().removeClass('alert-danger alert-success');


        $.ajax({
            url: `${baseAPI}wp-json/wp/v2/forgot-password`, 
            method: 'POST',
            data: { email: emailInput },
            dataType: 'json',
            success: function(response) {
                
                if (response.success) {
                    $btnText.text('Sending Email...');
                    
                    $.ajax({
                        url: `${BASE_URL}forgetmail`, 
                        method: 'POST',
                        data: {
                            token: response.token,
                            link: BASE_URL,
                            user_id: response.user_id,
                            email: response.email,
                            message: response.message
                        },
                        success: function(mailResponse) {

                            $spinner.addClass('d-none');
                            $statusBox.addClass('alert-success')
                                      .html('<i class="bi bi-check-circle-fill me-2"></i> Mail sent successfully! Redirecting to home...')
                                      .fadeIn();
                            
                            $('.input-wrapper').hide();
                            $('.form-instruction').hide();
                            $submitBtn.hide();
                            // setTimeout(function() {
                            //     window.location.href = '/shop/'; 
                            // }, 2000);
                        },
                        error: function() {
                            resetButton();
                            $statusBox.addClass('alert-danger')
                                      .html('<i class="bi bi-exclamation-triangle-fill me-2"></i> Verification done, but failed to execute forgetmail.php.')
                                      .fadeIn();
                        }
                    });

                } else {
                    resetButton();
                    $statusBox.addClass('alert-danger')
                              .html('<i class="bi bi-exclamation-triangle-fill me-2"></i> ' + (response.message || 'User not found.'))
                              .fadeIn();
                }
            },
            error: function() {
                resetButton();
                $statusBox.addClass('alert-danger')
                          .html('<i class="bi bi-exclamation-triangle-fill me-2"></i> Connection failed with Verification Server.')
                          .fadeIn();
            }
        });

        function resetButton() {
            $submitBtn.prop('disabled', false);
            $btnText.text('Send Reset Link');
            $spinner.addClass('d-none');
        }
    });
});

</script>
</body>
</html>
