<?php
$brandSlug = $_GET['brand'] ?? null;

if (!$brandSlug) {
    die("Brand not found");
}


$brandApiUrl = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/brand/" . urlencode($brandSlug);
$brandResponse = @file_get_contents($brandApiUrl);

if (!$brandResponse) {
    header("Location: /shop/");
    exit; 
}

$brandData = json_decode($brandResponse, true);


$brandName = $brandData['name'] ?? ucfirst($brandSlug);
$brandProducts = $brandData['products'] ?? [];

$pageTitle = htmlspecialchars($brandName) . " Products";
include __DIR__ . '/includes/header.php';
?>
<style>/* Card Container Adjustments */
.custom-product-card {
    background: transparent;
    transition: transform 0.25s ease-in-out;
}

/* Image Wrapper Box */
.custom-product-card .card-img-wrapper {
    height: 240px;
    background-color: #fcfcfc;
    border: 1px solid #f1f5f9;
    transition: background-color 0.25s ease, border-color 0.25s ease;
}

/* Image Uniform Aspect Scales */
.custom-product-card .card-main-thumb {
    max-height: 180px;
    object-fit: contain;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Hover Accent States: Card shifts slightly & Image scales cleanly */
.custom-product-card:hover {
    transform: translateY(-4px);
}

.custom-product-card:hover .card-img-wrapper {
    background-color: #f8fafc;
    border-color: #e2e8f0;
}

.custom-product-card:hover .card-main-thumb {
    transform: scale(1.04);
}

.custom-product-card:hover .btn-outline-dark {
    background-color: #13564f; /* Eco Brand Accent Color */
    border-color: #13564f;
    color: #ffffff !important;
}

/* Sidebar Custom Tuning */
.brand-sidebar {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
}

.tracking-wider {
    letter-spacing: 0.05em;
}

/* Mix Blend Mode logic fixes canvas rendering checks */
.mix-blend-multiply {
    mix-blend-mode: multiply;
}</style>
<section class="breadcrumb-nav py-3 border-bottom bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 data-filters-row">
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
            <div class="brand-sidebar p-4 bg-white border rounded-3 sticky-top" style="top: 20px; z-index: 10;">
                <h1 class="h4 fw-bold text-dark mb-3"><?= htmlspecialchars($brandName) ?></h1>
                <p class="text-muted small mb-4">Viewing premium refurbished products categorized under <?= htmlspecialchars($brandName) ?> line.</p>
                
                <hr class="text-muted opacity-25">
                
                <div class="filter-group mt-3">
                    <h5 class="h6 fw-semibold mb-3 text-uppercase tracking-wider" style="font-size: 12px; color: #8c8c8c;">Availability</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="inStockCheck" checked>
                        <label class="form-check-label text-muted small" for="inStockCheck">In Stock Only</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-between align-items-center mb-4 flex-wrap gap-2">
                <p class="text-muted mb-0 small">Found <span class="fw-semibold text-dark"><?= count($brandProducts) ?></span> matching devices</p>
                <select class="form-select form-select-sm w-auto" aria-label="Sort products">
                    <option selected>Default Sorting</option>
                    <option value="low-high">Price: Low to High</option>
                    <option value="high-low">Price: High to Low</option>
                </select>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="brandProductsGrid">
                <?php if (!empty($brandProducts)): ?>
                    <?php foreach ($brandProducts as $p): ?>
                        <?php 
                        $productSlug = $p['slug'] ?? '';
                        $productLink = $productSlug ? '/shop/buy/?slug=' . rawurlencode($productSlug) : '#';
                        $price = $p['price'] ?? '0';
                        $img = $p['image'] ?? '/shop/img/no-image.png';
                        ?>
                        <div class="col product-card-item">
                            <a href="<?= htmlspecialchars($productLink, ENT_QUOTES, 'UTF-8') ?>" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 position-relative custom-product-card transition-all">
                                    
                                    <div class="card-img-wrapper p-4 d-flex align-items-center justify-content-center bg-light-subtle rounded-3 overflow-hidden">
                                        <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" class="img-fluid mix-blend-multiply card-main-thumb" alt="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                    
                                    <div class="card-body px-1 pt-3 pb-0">
                                        <h3 class="card-title h6 fw-semibold text-truncate mb-2" title="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </h3>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="fw-bold fs-5 text-dark">£<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?></span>
                                            <span class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-arrow-right-short fs-5"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-box-sealer text-muted fs-1 mb-3 d-block"></i>
                        <h4 class="h5 fw-semibold text-dark">No Products Available</h4>
                        <p class="text-muted small">We currently don't have any items registered under this brand name.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>