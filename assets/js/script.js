function toggleWishlist(element, product) {
    let wishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
    

    const pKey = product.slug || product.permalink || '';
    const productIndex = wishlist.findIndex(item => (item.slug === pKey || item.permalink === pKey));
    
    const icon = element.querySelector('i');

    if (productIndex > -1) {
  
        wishlist.splice(productIndex, 1);
        
       
        element.style.color = '#212529';
        icon.classList.remove('bi-heart-fill');
        icon.classList.add('bi-heart');
        
        console.log(`${product.name} wishlist se remove ho gaya.`);
    } else {
  
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
        
        console.log(`${product.name} wishlist mein save ho gaya.`);
    }

    localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
}