// function toggleWishlist(element, product) {
//     let wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    

//     const pKey = product.slug || product.permalink || '';
//     const productIndex = wishlist.findIndex(item => (item.slug === pKey || item.permalink === pKey));
    
//     const icon = element.querySelector('i');

//     if (productIndex > -1) {
  
//         wishlist.splice(productIndex, 1);
        
       
//         element.style.color = '#212529';
//         icon.classList.remove('bi-heart-fill');
//         icon.classList.add('bi-heart');
        
//         console.log(`${product.name} wishlist se remove ho gaya.`);
//     } else {
  
//         wishlist.push({
//             id: product.id,
//             name: product.name,
//             price: product.price,
//             image: product.image,
//             slug: product.slug || pKey,
//             permalink: product.permalink || pKey,
//             category: product.category,
//             url: product.url || product.permalink || '#',
//             addedAt: new Date().toISOString()
//         });
        

//         element.style.color = '#13564f';
//         icon.classList.remove('bi-heart');
//         icon.classList.add('bi-heart-fill');
        
//         console.log(`${product.name} wishlist mein save ho gaya.`);
//     }

//     localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
// }


async function toggleWishlist(element, product) {

    const token = localStorage.getItem('auth_token'); 
    const accountData = localStorage.getItem('recycleproAccount');

    if (!token || !accountData) {

        showToast("Please login first to manage your wishlist!", "warning"); 
        return;
    }


    const user = JSON.parse(accountData);
    const userId = user.email || user.email || '';      
    const username = user.username || user.name || ''; 


    if (!userId) {
        showToast("User session expired. Please login again.", "warning");
        return;
    }

    let wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    const pKey = product.slug || product.permalink || '';
    const productIndex = wishlist.findIndex(item => (item.slug === pKey || item.permalink === pKey));
    const icon = element.querySelector('i');
    
    let isAdding = productIndex === -1; 

    try {
        const apiUrl = isAdding ? '/api/wishlist/add' : `/api/wishlist/remove/${product.id}`;
        const method = isAdding ? 'POST' : 'DELETE';
        

        const requestBody = {
            userId: userId,
            username: username,
            productId: product.id,
            productName: product.name || ''
        };

        const response = await fetch(apiUrl, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}` 
            },
            
            body: isAdding ? JSON.stringify(requestBody) : JSON.stringify({ userId: userId, productId: product.id })
        });

        if (!response.ok) {
            throw new Error('API call failed!');
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

            element.style.color = '#13564f';
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');
            
            showToast(`"${product.name}" added to your wishlist!`, 'success');
        } else {
            wishlist.splice(productIndex, 1);

            element.style.color = '#212529';
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');
            
            showToast(`"${product.name}" removed from wishlist.`, 'warning');
        }

        localStorage.setItem('user_wishlist', JSON.stringify(wishlist));

    } catch (error) {
        console.error("Wishlist update karne mein masla aya:", error);
        showToast("Failed to update wishlist. Server error!", "error");
    }
}


