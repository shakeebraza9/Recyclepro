<?php
$pageTitle = 'Recycle Pro';
include __DIR__ . '/includes/header.php';
?>

<div class="container py-5" style="min-height: 60px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold text-dark m-0">My Wishlist <span id="wishlistCount" class="badge bg-dark rounded-pill fs-6 ms-2">0</span></h2>
        <button class="btn btn-outline-danger btn-sm rounded-3" onclick="clearFullWishlist()">
            <i class="bi bi-trash3 me-1"></i> Clear All
        </button>
    </div>

    <div id="wishlistContainer" class="row g-4">
        </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Page load hote hi wishlist render karein
    renderWishlistPage();
});

function renderWishlistPage() {
    const container = $("#wishlistContainer");
    const countBadge = $("#wishlistCount");
    
    if (!container.length) return;

    // LocalStorage se wishlist array nikalen
    let wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    
    // Total count update karein
    countBadge.text(wishlist.length);

    // --- CONDITION 1: Agar Wishlist Khali Hai ---
    if (wishlist.length === 0) {
        container.html(`
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-heartbreak text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold text-secondary">Aapki Wishlist Khali Hai!</h4>
                <p class="text-muted">Apni pasandida products ko save karne ke liye shopping jari rakhein.</p>
                <a href="/shop" class="btn text-white px-4 py-2 rounded-3 mt-2" style="background-color: #f26500;">
                    Continue Shopping
                </a>
            </div>
        `);
        return;
    }

    // --- CONDITION 2: Agar Products Maujood Hain ---
    container.html(""); // Container clear karein
    
    wishlist.forEach((p, index) => {
        // Safe slug/permalink fallback link generate karne ke liye
        const productLink = `/shop/buy/${p.slug || p.permalink || '#'}`;
        
        container.append(`
            <div class="col-6 col-md-4 col-lg-3 product-card-item" id="wishlist-item-${index}">
                <div class="card h-100 border border-light-subtle rounded-4 p-3 d-flex flex-column justify-content-between shadow-sm bg-white" style="min-height: 380px;">
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong class="text-uppercase text-muted d-block small fw-semibold tracking-wider" style="font-size: 0.75rem; font-family: monospace;">
                                ${p.category || 'Product'}
                            </strong>
                            
                            <button class="btn btn-link p-0 border-0 bg-transparent fs-5 lh-1" style="color: #13564f;"
                                    aria-label="Remove from Wishlist" 
                                    onclick="removeFromWishlistPage('${p.slug || p.permalink}', ${index})">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>

                        <div class="text-center d-flex align-items-center justify-content-center my-2" style="height: 180px; overflow: hidden;">
                            <a href="${productLink}" class="d-block w-100 h-100">
                                <img 
                                    src="${p.image}" 
                                    class="img-fluid h-100" 
                                    alt="${p.name}" 
                                    style="object-fit: contain; max-width: 100%;"
                                >
                            </a>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6 class="fw-bold text-dark mb-2 text-truncate-2-lines" style="font-size: 0.95rem; min-height: 2.4rem; line-height: 1.2;">
                            <a href="${productLink}" class="text-decoration-none text-dark hover-primary">
                                ${p.name}
                            </a>
                        </h6>

                        <div class="d-flex justify-content-between align-items-end pt-1">
                            <strong class="fw-bold fs-5 text-dark">£${p.price}</strong>
                            
                            <a href="${productLink}" class="text-dark fs-4 lh-1 p-1" aria-label="View Product">
                                <i class="bi bi-cart3"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        `);
    });
}

// Single item delete functionality directly inside the page view
function removeFromWishlistPage(productKey, index) {
    let wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    
    // Array se specific product filter out karein
    wishlist = wishlist.filter(item => (item.slug !== productKey && item.permalink !== productKey));
    
    // Storage update karein
    localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
    
    // Card item ko screen se smooth tarike se remove karne ke liye animation drop effect
    $(`#wishlist-item-${index}`).fadeOut(300, function() {
        $(this).remove();
        // Poore layout state ko refresh karein (Check if it became empty now)
        renderWishlistPage();
    });
}

// Poori wishlist ko ek click par saaf karne ke liye
function clearFullWishlist() {
    if(confirm("Kya aap poori wishlist delete karna chahte hain?")) {
        localStorage.removeItem('user_wishlist');
        renderWishlistPage();
    }
}



</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
