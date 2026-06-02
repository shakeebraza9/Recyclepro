<?php
$param = $_GET['slug'] ?? null;

if (!$param) {
    die("Product not found");
}

$apiUrl = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/product/" . $param;
$response = @file_get_contents($apiUrl);

if (!$response) {
    die("API error");
}

$product = json_decode($response, true);

if (!$product || !isset($product['name'])) {
    die("Product not found");
}

$selectedVariation = null;
if (!empty($product['variations']) && is_array($product['variations'])) {
    $selectedVariation = $product['variations'][0];
}

$productImage = $product['image'] ?? $selectedVariation['image'] ?? '';
$displayPrice = $selectedVariation['price'] ?? $product['price'];

$pageTitle = htmlspecialchars($product['name']);
include __DIR__ . '/../includes/header.php';
?>

<?php
// Build breadcrumb data
$breadcrumbs = [];
$categories = $product['categories'] ?? [];
$mainCategory = null;
$subCategory = null;

if (!empty($categories) && is_array($categories)) {
    // First category is the main category
    $mainCategory = $categories[0];
    
    // Check if there's a parent-child relationship
    if (count($categories) > 1) {
        $subCategory = $categories[1];
    }
}

$productImages = [];

$addProductImage = function ($image) use (&$productImages) {
    if (!empty($image) && is_string($image) && !in_array($image, $productImages, true)) {
        $productImages[] = $image;
    }
};

$addProductImage($productImage);

if (!empty($product['gallery']) && is_array($product['gallery'])) {
    foreach ($product['gallery'] as $image) {
        $addProductImage($image);
    }
}

if (!empty($product['images']) && is_array($product['images'])) {
    foreach ($product['images'] as $image) {
        if (is_array($image)) {
            $image = $image['url'] ?? $image['src'] ?? '';
        }

        if (!empty($image) && !in_array($image, $productImages, true)) {
            $productImages[] = $image;
        }
    }
}
if (!empty($product['variations']) && is_array($product['variations'])) {
    foreach ($product['variations'] as $variation) {
        $addProductImage($variation['image'] ?? '');
    }
}
if (empty($productImages)) {
    $productImages[] = '/shop/img/no-image.png';
}

$featureList = !empty($product['features']) && is_array($product['features'])
    ? $product['features']
    : [
        'Condition Pristine',
        'Full functionality tested',
        'Free shipping and easy returns',
    ];

$reviewCount = !empty($product['review_count']) ? intval($product['review_count']) : 32;

$similarProducts = [];
$categorySlug = !empty($product['categories']) ? ($product['categories'][0]['slug'] ?? 'sell-phone') : 'sell-phone';
$categoryApiUrl = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/category/" . urlencode($categorySlug);
$categoryResponse = @file_get_contents($categoryApiUrl);
if ($categoryResponse) {
    $categoryData = json_decode($categoryResponse, true);
    if (!empty($categoryData['products']) && is_array($categoryData['products'])) {
        $similarProducts = array_slice($categoryData['products'], 0, 4);
    }
}
while (count($similarProducts) < 4) {
    $similarProducts[] = [
        'name' => $product['name'],
        'slug' => $param,
        'price' => $displayPrice,
        'image' => $productImage,
    ];
}
?>


<style>
.product-display {
    position: relative;
    overflow: hidden; 
    border: 1px solid #eee;
    border-radius: 8px;
    background-color: #fff;
    cursor: zoom-in;
}

.product-main-img.zoomable {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.1s ease-out; /* Smooth tracking response */
    transform-origin: center center;
}

.product-display:hover .product-main-img.zoomable {
    transform: scale(2); /* Magnification level (2x zoom) */
}
</style>

<section class="breadcrumb">
    <div class="container">
        <nav class="content-breadcrumb" aria-label="Breadcrumb">
            <a href="/shop/"><i class="bi bi-house"></i></a>
            <span>/</span>
            
            <?php if ($mainCategory): ?>
                <a href="/shop/category/<?php echo htmlspecialchars($mainCategory['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($mainCategory['name'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <span>/</span>
            <?php endif; ?>
            
            <?php if ($subCategory): ?>
                <a href="/shop/category/<?php echo htmlspecialchars($mainCategory['slug'], ENT_QUOTES, 'UTF-8'); ?>/<?php echo htmlspecialchars($subCategory['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($subCategory['name'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <span>/</span>
            <?php endif; ?>
            
            <span><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></span>
        </nav>
    </div>
</section>

<div class="container py-5">
    <div class="product-page">
        <div class="row gx-4 gy-4 align-items-start">
            
            <div class="col-12 col-xl-1 order-2 order-xl-1">
                <div class="thumb-column">
                    <?php foreach ($productImages as $index => $image): ?>
                        <button type="button" class="thumb-btn<?= $index === 0 ? ' active' : '' ?>" data-image="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" aria-label="Thumbnail <?= $index + 1 ?>">
                            <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?> thumbnail <?= $index + 1 ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-12 col-xl-6 order-1 order-xl-2">
                <div class="product-display" id="zoomContainer">
                    <img id="mainImage" src="<?= htmlspecialchars($productImages[0], ENT_QUOTES, 'UTF-8') ?>" class="product-main-img zoomable" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>


            <div class="col-12 col-xl-5 order-3 order-xl-3">
                <div class="product-summary">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4 flex-column flex-sm-row">
                        <div>
                            <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                            <div class="product-price-line">
                                <span class="product-price">£<span id="priceBox"><?= htmlspecialchars($displayPrice) ?></span></span>
                                <span class="product-rating ms-2">
                                    <?php for ($i = 0; $i < 4; $i++): ?>
                                        <i class="bi bi-star-fill"></i>
                                    <?php endfor; ?>
                                    <i class="bi bi-star-half"></i>
                                    <span class="text-muted">(<?= $reviewCount ?> review<?= $reviewCount === 1 ? '' : 's' ?>)</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-muted mb-4 product-short-description">
                        <?= !empty($product['short_description']) ? $product['short_description'] : 'No short description available.' ?>
                    </div>

                    <?php if (!empty($product['variations']) && count($product['variations']) > 1): ?>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Choose option</label>
                            <div class="variation-buttons-wrapper d-flex flex-wrap gap-2" id="variationSelectContainer">
                                <?php foreach ($product['variations'] as $index => $variation): ?>
                                    <?php 
                                    $optionLabel = trim($variation['attributes']['storage'] ?? '') ?: 'Variation ' . ($index + 1) . ' - £' . $variation['price']; 
                                    $variationId = htmlspecialchars($variation['id']);
                                    ?>
                                    <div class="variation-btn-item">
                                        <input type="radio" 
                                               name="product_variation" 
                                               id="var_<?= $variationId ?>" 
                                               value="<?= $variationId ?>"
                                               class="btn-check variation-radio btn-var" 
                                               data-price="<?= htmlspecialchars($variation['price']) ?>"
                                               data-image="<?= htmlspecialchars($variation['image'] ?: $productImages[0], ENT_QUOTES, 'UTF-8') ?>"
                                               <?= $index === 0 ? 'checked' : '' ?>>
                                               
                                        <label class="btn btn-outline-secondary variation-btn-label py-2 px-3 fs-6" for="var_<?= $variationId ?>">
                                            <?= htmlspecialchars($optionLabel) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <ul class="feature-list mb-4">
                        <?php foreach ($featureList as $feature): ?>
                            <li><i class="bi bi-check-circle"></i> <?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="d-flex gap-2 gap-sm-3 align-items-center flex-nowrap mb-3 w-100">
                        <div class="quantity-box flex-shrink-0">
                            <button type="button" class="qty-btn" id="decreaseQty" aria-label="Decrease quantity">-</button>
                            <input type="text" id="quantityInput" value="1" readonly aria-label="Quantity">
                            <button type="button" class="qty-btn" id="increaseQty" aria-label="Increase quantity">+</button>
                        </div>
                        <button id="addToCart" class="btn btn-dark btn-lg px-3 px-sm-5 flex-grow-1 text-nowrap" style="background-color: #13564f;">Add to Cart</button>
                    </div>

                    <button id="buyNow" class="btn btn-outline-dark btn-lg w-100 mb-4">Buy Now</button>

                    <div class="product-info-list">
                        <div><i class="bi bi-truck"></i> Free worldwide shipping on all orders over £100</div>
                        <div><i class="bi bi-shield-check"></i> Delivered in 2-5 Working Days <a href="<?php echo $config['BASE_URL']; ?>return-policy-warranty">Shipping & Return</a></div>
                    </div>
                </div>
            </div> </div> <div class="product-details-tabs-container py-5 my-4">
            <ul class="custom-nav-tabs mb-4" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-tab-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                </li>
                <li class="nav-tab-separator">|</li>
                <li class="nav-item" role="presentation">
                    <button class="nav-tab-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                </li>
            </ul>

            <div class="tab-content pt-2">
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <div class="product-description-content">
                        <?= !empty($product['description']) ? $product['description'] : 'No description available.' ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <p class="text-muted m-0">No reviews yet. Be the first to review this product.</p>
                </div>
            </div>
        </div>

        <div class="similar-products py-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="h4 mb-0">Similar Products</h2>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($similarProducts as $similar): ?>
                    <?php
                        $similarSlug = $similar['slug'] ?? '';
                        $similarLink = $similarSlug ? '/shop/buy/?slug=' . rawurlencode($similarSlug) : '#';
                    ?>
                    <div class="col">
                        <a href="<?= htmlspecialchars($similarLink, ENT_QUOTES, 'UTF-8') ?>" class="similar-product-link">
                            <div class="card position-relative overflow-hidden h-100">
                                <img src="<?= htmlspecialchars($similar['image'], ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="<?= htmlspecialchars($similar['name'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="card-body product-card-body">
                                    <h3 class="card-title h6"><?= htmlspecialchars($similar['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <div class="price-row align-items-center">
                                        <span class="fw-semibold">£<?= htmlspecialchars($similar['price'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<script>
const thumbButtons = document.querySelectorAll('.thumb-btn');
const mainImage = document.getElementById('mainImage');
const zoomContainer = document.getElementById('zoomContainer');
const priceBox = document.getElementById('priceBox');
const addToCartButton = document.getElementById('addToCart');
const buyNowButton = document.getElementById('buyNow');
const quantityInput = document.getElementById('quantityInput');
const increaseQty = document.getElementById('increaseQty');
const decreaseQty = document.getElementById('decreaseQty');

// Dynamic Hover-to-Zoom Tracking Logic
if (zoomContainer && mainImage) {
    zoomContainer.addEventListener('mousemove', function(e) {
        const rect = e.currentTarget.getBoundingClientRect();
        
        // Calculate cursor position percentage inside container bounds
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        
        // Dynamically alter the zoom anchor point to map target details
        mainImage.style.transformOrigin = `${x}% ${y}%`;
    });

    zoomContainer.addEventListener('mouseleave', function() {
        // Reset anchor point back to absolute center when cursor steps away
        mainImage.style.transformOrigin = 'center center';
    });
}

function updateProductDisplay(price, image) {
    if (priceBox) {
        priceBox.innerText = price ? price : '0';
    }
    if (mainImage && image) {
        mainImage.src = image;
    }
}

document.querySelectorAll('.variation-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.checked) {
            const price = this.dataset.price || (priceBox ? priceBox.innerText : '0');
            const image = this.dataset.image || (mainImage ? mainImage.src : '');
            updateProductDisplay(price, image);
            
            const targetedThumb = document.querySelector(`.thumb-btn[data-image="${image}"]`);
            if (targetedThumb) {
                setActiveThumb(targetedThumb);
            }
        }
    });
});

function setActiveThumb(button) {
    thumbButtons.forEach((btn) => btn.classList.remove('active'));
    button.classList.add('active');
}

thumbButtons.forEach((button) => {
    button.addEventListener('click', function () {
        const image = this.dataset.image;
        if (image) {
            mainImage.src = image;
            setActiveThumb(this);
            
            const correspondingRadio = document.querySelector(`.variation-radio[data-image="${image}"]`);
            if (correspondingRadio) {
                correspondingRadio.checked = true;
                if (correspondingRadio.dataset.price && priceBox) {
                    priceBox.innerText = correspondingRadio.dataset.price;
                }
            }
        }
    });
});

if (increaseQty) {
    increaseQty.addEventListener('click', () => {
        const qty = Number(quantityInput.value) || 1;
        quantityInput.value = qty + 1;
    });
}

if (decreaseQty) {
    decreaseQty.addEventListener('click', () => {
        const qty = Number(quantityInput.value) || 1;
        quantityInput.value = Math.max(1, qty - 1);
    });
}

function getSelectedProductData() {
    let selectedProductId = <?= json_encode((string)$product['id']) ?>;
    let selectedImage = <?= json_encode($productImages[0]) ?>;
    let selectedPrice = priceBox ? priceBox.innerText : <?= json_encode($displayPrice) ?>;
    let selectedVariationId = selectedProductId;

    const activeThumb = document.querySelector('.thumb-btn.active');
    if (activeThumb) {
        selectedImage = activeThumb.dataset.image || selectedImage;
    }

    const activeRadio = document.querySelector('.variation-radio:checked');
    if (activeRadio) {
        selectedPrice = activeRadio.dataset.price || selectedPrice;
        selectedImage = activeRadio.dataset.image || selectedImage;
        selectedVariationId = activeRadio.value || selectedVariationId;
    }

    return {
        product_id: selectedVariationId,
        parent_id: <?= json_encode((string)$product['id']) ?>,
        name: <?= json_encode($product['name']) ?>,
        price: selectedPrice,
        image: selectedImage,
        quantity: Number(quantityInput ? quantityInput.value : 1),
        permalink: window.location.href
    };
}

if (addToCartButton) {
    addToCartButton.addEventListener('click', function () {
        if (window.cartManager && typeof window.cartManager.addItem === 'function') {
            window.cartManager.addItem(getSelectedProductData()).then(() => {
                showToast('Product added to cart!','success');
            }).catch((error) => {
                console.error('Add to cart failed:', error);
                showToast('Unable to add to cart right now. Please try again.','error');
            });
        } else {
            showToast('Cart is not available right now.','error');
        }
    });
}

if (buyNowButton) {
    buyNowButton.addEventListener('click', function () {
        const redirectToCheckout = () => {
            window.location.href = '/shop/checkout';
        };

        if (window.cartManager && typeof window.cartManager.addItem === 'function') {
            window.cartManager.addItem(getSelectedProductData()).then(() => {
                redirectToCheckout();
            }).catch((error) => {
                console.error('Buy Now failed:', error);
                redirectToCheckout();
            });
        } else {
            redirectToCheckout();
        }
    });
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>