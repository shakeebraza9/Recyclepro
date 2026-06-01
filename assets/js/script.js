async function toggleWishlist(element, product) {

    const accountData = localStorage.getItem('recycleproAccount');
    console.log(accountData);

    if (!accountData) {
        showToast("Please login first to manage your wishlist!", "warning"); 
        return;
    }

    const user = JSON.parse(accountData);
    const customerId = user.wp_user_id || user.id || ''; 

    if (!customerId) {
        showToast("User session profile incomplete. Please login again.", "warning");
        return;
    }
    
    let wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    
    const pKey = product.slug || product.permalink || '';
    const productIndex = wishlist.findIndex(item => 
        item.id === product.id || 
        (pKey && (item.slug === pKey || item.permalink === pKey))
    );
    
    const icon = element.querySelector('i');
    let isAdding = productIndex === -1; 

    const BASE_URL = 'https://www.recyclepro.co.uk/rp-dashboard/wp-json/wishlist/v1';
    let apiUrl = isAdding ? `${BASE_URL}/add` : `${BASE_URL}/status`;
    
    let requestBody = isAdding ? {
        "customer_id": String(customerId),
        "product_id": String(product.id),
        "product_price": String(product.price || '0')
    } : {
        "customer_id": String(customerId),
        "product_id": String(product.id),
        "status": "inactive"
    };

    try {
        let response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestBody)
        });


        const responseText = await response.text();
        let result = {};
        
        try {
            result = JSON.parse(responseText);
        } catch(e) {
            console.error("JSON parse nahi ho saka:", responseText);
        }


        if (isAdding && result && result.success === false && result.message && result.message.includes("already exists")) {
            console.log("Product backend pe inactive para tha. Forcefully activating now...");
            
  
            apiUrl = `${BASE_URL}/status`;
            requestBody = {
                "customer_id": String(customerId),
                "product_id": String(product.id),
                "status": "active" 
            };

            response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestBody)
            });

            result = await response.json();
        }


        if (!response.ok || (result && result.success === false)) {
            throw new Error((result && result.message) || 'API call failed!');
        }
        
   
        if (isAdding) {
            wishlist.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                slug: product.slug || pKey,
                permalink: product.permalink || pKey,
                category: product.category,
                url: product.url || product.permalink || '#',
                addedAt: new Date().toISOString()
            });

            if (element && icon) {
                element.style.color = '#13564f';
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
            }
            
            showToast(`"${product.name}" added to your wishlist!`, 'success');
        } else {
            if (productIndex > -1) {
                wishlist.splice(productIndex, 1); 
            }

            if (element) {
                const productCard = element.closest('.wishlist-item-container') || element.closest('.card') || element.closest('tr');
                
                if (productCard && window.location.pathname.includes('wishlist')) {
                    productCard.style.transition = 'all 0.3s ease';
                    productCard.style.opacity = '0';
                    productCard.style.transform = 'scale(0.9)';
                    setTimeout(() => productCard.remove(), 300);
                } else if (icon) {
                    element.style.color = '#212529';
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                }
            }
            
            showToast(`"${product.name}" removed from wishlist.`, 'warning');
        }

        localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
        updateGlobalWishlistCount();

    } catch (error) {
        console.error("Wishlist update karne mein masla aya:", error);
        showToast(error.message || "Failed to update wishlist. Server error!", "error");
    }
}

function updateGlobalWishlistCount() {
    const badge = document.getElementById('globalWishlistCount');
    if (badge) {
        const wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
        badge.innerText = wishlist.length;
    }
}

document.addEventListener('DOMContentLoaded', updateGlobalWishlistCount);