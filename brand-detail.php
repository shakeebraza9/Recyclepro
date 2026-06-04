<?php
include __DIR__ . '/includes/header.php';
$brandSlug = $_GET['brand'] ?? null;

if (!$brandSlug) {
    die("Brand not found");
}

$brandApiUrl = $config['API_URL']."wp-json/wp/v2/brand/" . urlencode($brandSlug);
$brandResponse = @file_get_contents($brandApiUrl);

if (!$brandResponse) {
    header("Location: /shop/");
    exit; 
}

$brandData = json_decode($brandResponse, true);

$brandName = $brandData['name'] ?? ucfirst($brandSlug);
$brandProducts = $brandData['products'] ?? [];


function renderProductsGrid($products, $brandName) {
    if (!empty($products)) {
        foreach ($products as $p) {
            $productSlug = $p['slug'] ?? '';
            $productLink = $productSlug ? '/shop/buy/?slug=' . rawurlencode($productSlug) : '#';
            $price = $p['price'] ?? '0';
            $img = $p['image'] ?? '/shop/img/no-image.png';
            
            // Wishlist data pass karne ke liye safe JSON encoding
            $escapedProductJson = htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8');
            
            echo '
            <div class="col product-card-item">
                <div class="product-card d-flex flex-column h-100 position-relative" 
                    style="transition: all 0.3s ease; background: #ffffff; border: 1px solid #edf2f7; border-radius: 16px; overflow: hidden;"
                    onmouseenter="this.querySelector(\'.custom-overlay\').style.opacity=\'1\'; this.querySelector(\'.custom-overlay\').style.visibility=\'visible\'; this.querySelector(\'.custom-overlay\').style.transform=\'translateY(0)\';"
                    onmouseleave="this.querySelector(\'.custom-overlay\').style.opacity=\'0\'; this.querySelector(\'.custom-overlay\').style.visibility=\'hidden\'; this.querySelector(\'.custom-overlay\').style.transform=\'translateY(10px)\';">
                    
                    <div class="position-absolute" style="top: 15px; right: 15px; z-index: 10;">
                        <button class="btn btn-link p-0 border-0 bg-transparent fs-4 lh-1 wishlist-btn d-inline-flex align-items-center" 
                                style="color: #cbd5e1;" 
                                aria-label="Add to Wishlist" 
                                data-product=\''.$escapedProductJson.'\'
                                onclick="toggleWishlist(this, JSON.parse(this.getAttribute(\'data-product\')))">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>

                    <div class="p-3 text-center flex-grow-1 d-flex flex-column justify-content-between">
                        
                        <div class="my-3 position-relative overflow-hidden d-flex align-items-center justify-content-center" 
                            style="height: 170px; background-color: #ffffff; border: 1px solid #f1f3f5; border-radius: 12px; padding: 10px;">
                            
                            <a href="'.$productLink.'" class="d-block w-100 h-100">
                                <img src="'.htmlspecialchars($img, ENT_QUOTES, 'UTF-8').'" alt="'.htmlspecialchars($p['name'] ?? '', ENT_QUOTES, 'UTF-8').'" class="img-fluid h-100 mix-blend-multiply card-main-thumb" style="object-fit: contain; max-width: 100%;">
                            </a>

                            <div class="custom-overlay d-flex flex-column align-items-center justify-content-center gap-2 position-absolute top-0 start-0 w-100 h-100"
                                style="background-color: rgba(255, 255, 255, 0.88); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s ease-in-out; z-index: 2; border-radius: 12px; padding: 20px;">
                                
                                <a href="'.$productLink.'" class="btn btn-sm text-white w-70 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                                style="min-width: 100% !important; font-size: 14px; background-color: #13564f; border: none; border-radius: 6px;">
                                    <i class="bi bi-bag-check me-2" style="font-size: 15px;"></i> Shop
                                </a>
                                
                                <a href="https://www.recyclepro.co.uk/" class="btn btn-sm text-white w-70 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center" 
                                style="min-width: 100% !important; font-size: 14px; background-color: #004465; border: none; border-radius: 6px;">
                                    <i class="bi bi-arrow-left-right me-2" style="font-size: 15px;"></i> Sell
                                </a>
                            </div>
                        </div>

                        <div>
                            <h3 class="product-title text-start mb-1 text-truncate-2" style="font-size: 1rem; font-weight: 700; min-height: 2.4rem; line-height: 1.2;">
                                '.htmlspecialchars($p['name'] ?? 'Device Catalog Item', ENT_QUOTES, 'UTF-8').'
                            </h3>
                            
                             <div class="text-start product-meta mb-2">
                                     Condition - Pristine
                          
                                </div>
                            
                            <div class="text-start product-colors mb-3">
                                <span class="d-block text-muted mb-1" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Brand: <span style="color: #13564f; font-weight: 700;">'.htmlspecialchars($brandName).'</span>
                                </span>
                            </div>
                            
                            <div class="text-start product-price mb-2">
                                <span class="fw-bold fs-5 text-dark">£'.htmlspecialchars($price, ENT_QUOTES, 'UTF-8').'</span>
                            </div>
                        </div>

                        <div class="mt-2">
                            <a href="'.$productLink.'" class="btn btn-view-product w-100 py-2 mb-2" style="border-radius: 8px; font-weight: 600;">View product</a>
                        </div>
                    </div>

                </div>
            </div>';
        }
    } else {
        echo '
        <div class="col-12 text-center py-5">
            <i class="bi bi-box-sealer text-muted fs-1 mb-3 d-block"></i>
            <h4 class="h5 fw-semibold text-dark">No Products Found</h4>
            <p class="text-muted small">Try removing your stock toggle or filter constraints.</p>
        </div>';
    }
}


if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $sort = $_GET['sort'] ?? 'default';
    $inStockOnly = ($_GET['in_stock'] ?? 'false') === 'true';

    // Stock Status Filtering Logic
    if ($inStockOnly) {
        $brandProducts = array_filter($brandProducts, function($p) {
            return isset($p['in_stock']) ? (bool)$p['in_stock'] : true;
        });
    }

    // Price Sorting Configurations
    if ($sort === 'low-high') {
        usort($brandProducts, function($a, $b) { return (float)($a['price'] ?? 0) <=> (float)($b['price'] ?? 0); });
    } elseif ($sort === 'high-low') {
        usort($brandProducts, function($a, $b) { return (float)($b['price'] ?? 0) <=> (float)($a['price'] ?? 0); });
    }

    // Direct loop call then immediate layout process kill
    renderProductsGrid($brandProducts, $brandName);
    exit;
}


$pageTitle = htmlspecialchars($brandName) . " Products";

?>

<style>
#brandProductsGrid {
    transition: opacity 0.2s ease-in-out;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    border-color: #cbd5e1 !important;
}
.card-main-thumb {
    transition: transform 0.3s ease;
}
.product-card:hover .card-main-thumb {
    transform: scale(1.04);
}
.btn-view-product {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    transition: all 0.2s ease;
}
.btn-view-product:hover {
    background-color: #13564f;
    border-color: #13564f;
    color: #ffffff;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.mix-blend-multiply {
    mix-blend-mode: multiply;
}
.tracking-wider {
    letter-spacing: 0.05em;
}
</style>

<section class="breadcrumb-nav py-3 border-bottom bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/shop/" class="text-decoration-none text-muted"><i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="/shop/brands" class="text-decoration-none text-muted">Brands</a></li>
                <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page"><?= htmlspecialchars($brandName) ?></li>
            </ol>
        </nav>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        
        <div class="col-lg-3 col-md-4">
            <div class="p-4 bg-white border rounded-3 sticky-top shadow-sm" style="top: 20px; z-index: 10; border-radius: 16px !important;">
                <h1 class="h4 fw-bold text-dark mb-3"><?= htmlspecialchars($brandName) ?></h1>
                <p class="text-muted small mb-4">Viewing premium refurbished products categorized under <?= htmlspecialchars($brandName) ?> line.</p>
                
                <hr class="text-muted opacity-25">
                
                <div class="filter-group mt-3">
                    <h5 class="h6 fw-semibold mb-3 text-uppercase tracking-wider" style="font-size: 11px; color: #8c8c8c;">Availability</h5>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input filter-trigger" type="checkbox" id="inStockCheck">
                        <label class="form-check-label text-muted small fw-medium" for="inStockCheck">In Stock Only</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
         

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="brandProductsGrid">
                <?php 

                renderProductsGrid($brandProducts, $brandName); 
                ?>
            </div>
        </div>

    </div>
</div>

<script>

</script>

<?php include __DIR__ . '/includes/footer.php'; ?>