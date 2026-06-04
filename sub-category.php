<?php
session_start();

function sanitize_slug($slug) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
}

$parent_slug = '';
$sub_slug    = '';


if (!empty($_SERVER['REQUEST_URI'])) {
    if (preg_match('/\/category\/([a-z0-9-]+)\/([a-z0-9-]+)\/?(\?.*)?$/i', $_SERVER['REQUEST_URI'], $m)) {
        $parent_slug = sanitize_slug($m[1]);
        $sub_slug    = sanitize_slug($m[2]);
    }
}


if (!$parent_slug && isset($_GET['parent_slug'])) $parent_slug = sanitize_slug($_GET['parent_slug']);
if (!$sub_slug    && isset($_GET['sub_slug']))    $sub_slug    = sanitize_slug($_GET['sub_slug']);

if (!$parent_slug || !$sub_slug) {
    echo "Error: Parent and sub-category slugs required.";
    exit;
}

include 'includes/header.php';
?>
<style>

:root {
    --accent:        #13564f;
    --accent-light:  #80beb5;
    --surface:       #fff;
    --surface-2:     #f8fafc;
    --surface-3:     #f1f5f9;
    --border:        #e2e8f0;
    --border-strong: #cbd5e1;
    --text-primary:  #0f172a;
    --text-secondary:#475569;
    --text-muted:    #94a3b8;
    --green:         #16a34a;
    --green-light:   #dcfce7;
    --red:           #dc2626;
    --red-light:     #fee2e2;
    --amber:         #d97706;
    --amber-light:   #fef3c7;
    --radius:        8px;
    --radius-lg:     12px;
    --shadow-sm:     0 1px 3px rgba(0,0,0,.06);
    --shadow-md:     0 4px 12px rgba(0,0,0,.08);
}

body { font-family: 'Inter', sans-serif; background: var(--surface-2); color: var(--text-primary); }


.rp-breadcrumb { background: #f8f9fa; }
.breadcrumb-link {
    text-decoration: none; color: #4f5a66;
    font-size: 13px; font-weight: 500;
    transition: color .2s;
}
.breadcrumb-link:hover { color: var(--accent); }
.breadcrumb-current { color: #1a1a1a; font-size: 13px; font-weight: 600; }
.sep { font-size: 12px; margin: 0 4px; color: #6c757d; }


.filter-title { font-size: 13px; font-weight: 700; color: var(--text-primary); }
.custom-cat-select {
    border: 1px solid var(--border); border-radius: var(--radius);
    padding: 10px 14px; font-size: 13px; color: var(--text-secondary);
    background: var(--surface-3); cursor: pointer; transition: all .2s;
}
.custom-cat-select:focus {
    border-color: var(--accent-light);
    box-shadow: 0 0 0 3px rgba(19,86,79,.15);
    outline: none;
}


.subcategories-wrapper { background: var(--surface-3); border-radius: 6px; border: 1px dashed var(--border-strong); }
.sub-cat-link {
    display: block; font-size: 12px; color: var(--text-secondary);
    padding: 6px 10px; border-radius: 4px;
    text-decoration: none; transition: all .2s;
}
.sub-cat-link:hover, .sub-cat-link.active-sub {
    background: var(--green-light); color: var(--accent);
    font-weight: 600; padding-left: 14px;
}
.sub-cat-link.active-sub { pointer-events: none; }
.sub-cat-link i { font-size: 10px; color: var(--text-muted); }


.active-sub-info {
    background: var(--green-light);
    border: 1px solid #bbf7d0;
    border-radius: var(--radius);
    padding: 10px 14px;
    font-size: 12px;
    color: var(--accent);
    font-weight: 600;
    margin-bottom: 12px;
}


.form-check-input:checked { background-color: var(--accent) !important; border-color: var(--accent) !important; }
.form-check-input { border-color: var(--text-muted); border-radius: 3px !important; }
.form-check-label { font-size: 12px; color: var(--text-secondary); }


.price-input-field {
    font-size: 12px; padding: 5px 8px;
    border: 1px solid var(--border-strong);
    border-radius: 4px; width: 100%;
}
.btn-apply-price {
    background: var(--accent); color: var(--surface);
    font-size: 11px; font-weight: 600; border: none;
    border-radius: 4px; padding: 6px 12px; transition: background .2s;
}
.btn-apply-price:hover { background: var(--accent-light); color: var(--accent); }


.filter-header-toggle { cursor: pointer; user-select: none; }
.filter-header-toggle i { transition: transform .2s; }
.filter-header-toggle.collapsed i { transform: rotate(90deg); }


.product-card {
    border: 1px solid #edf2f7; border-radius: 16px;
    background: #fff; overflow: hidden;
    transition: all .3s ease;
}
.product-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.product-title { font-size: 12px; font-weight: 700; color: var(--text-primary); line-height: 1.4; min-height: 34px; }
.product-meta  { font-size: 11px; color: var(--text-secondary); }
.product-price { font-size: 16px; font-weight: 700; color: var(--accent); }
.btn-view-product {
    border: 1px solid var(--accent); color: var(--accent);
    font-size: 12px; font-weight: 600; border-radius: 6px;
    background: transparent; transition: all .2s;
}
.btn-view-product:hover { background: var(--accent); color: #fff; }


@keyframes pulse { 0%,100%{opacity:.6} 50%{opacity:1} }
.animate-pulse { animation: pulse 1.5s infinite ease-in-out; }
.skeleton-item { display:inline-block; width:100px; height:14px; background:#e0e0e0; border-radius:4px; vertical-align:middle; }


.mobile-filter-trigger {
    display: none;
    background: var(--accent); color: #fff;
    border-radius: 50px; font-weight: 600; font-size: 14px;
    box-shadow: var(--shadow-sm); transition: all .3s;
}
.mobile-filter-trigger:hover { background: #0a3d37; color: #fff; }


.sort-bar {
    background: #fff; border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 10px 16px;
    display: flex; align-items: center; gap: 12px;
    flex-wrap: wrap;
}
.sort-bar select {
    border: 1px solid var(--border); border-radius: var(--radius);
    font-size: 13px; padding: 6px 10px; color: var(--text-secondary);
    background: var(--surface-3); cursor: pointer;
}
.result-count { font-size: 13px; color: var(--text-muted); margin-left: auto; }

@media (max-width: 991.98px) {
    .desktop-sidebar { display: none !important; }
    .mobile-filter-trigger { display: inline-flex !important; }
}
@media (max-width: 575px) {
    .sort-bar { flex-direction: column; align-items: flex-start; }
    .result-count { margin-left: 0; }
}
</style>


<section class="rp-breadcrumb py-3">
    <div class="container">
        <nav aria-label="Breadcrumb" class="d-flex align-items-center flex-wrap gap-1 text-muted small">
            <a href="/shop/" class="text-secondary text-decoration-none">
                <i class="bi bi-house-fill" style="font-size:14px;"></i>
            </a>
            <span class="sep">/</span>
            <div id="breadcrumb-dynamic" class="d-inline-flex align-items-center flex-wrap gap-1">
                <span class="skeleton-item animate-pulse"></span>
                <span class="sep">/</span>
                <span class="skeleton-item animate-pulse" style="width:80px;"></span>
                <span class="sep">/</span>
                <span class="skeleton-item animate-pulse" style="width:60px;"></span>
            </div>
        </nav>
    </div>
</section>


<div class="container mt-3">
    <h1 id="page-heading" class="fw-bold mb-0" style="font-size:1.6rem; color:var(--accent);"> </h1>
    <p id="page-sub" class="text-muted mt-1 mb-0" style="font-size:13px;"></p>
</div>


<div class="container mt-3 mb-5">


    <div class="row d-lg-none mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center bg-white p-2 rounded shadow-sm border">
            <span class="fw-bold text-dark ps-2" style="font-size:14px;">Products</span>
            <button class="btn mobile-filter-trigger py-2 px-3 d-inline-flex align-items-center gap-2"
                    type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="bi bi-sliders"></i> Filters
            </button>
        </div>
    </div>

    <div class="row">


        <div class="col-lg-3 desktop-sidebar mb-4">
            <div class="p-3 bg-white rounded shadow-sm border">
                <div class="sidebar-content-wrapper"></div>
            </div>
        </div>


        <div class="col-lg-9 col-md-12">



            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="products-container"></div>
        </div>

    </div>
</div>


<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar"
     aria-labelledby="mobileSidebarLabel" style="width:300px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="mobileSidebarLabel" style="font-size:16px;">
            <i class="bi bi-sliders me-2"></i>Filters
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-light">
        <div class="mobile-sidebar-content-wrapper"></div>
    </div>
</div>


<template id="sidebar-template">


    <div class="active-sub-info d-flex align-items-center gap-2" id="active-sub-badge">
        <i class="bi bi-tag-fill"></i>
        <span id="active-sub-label">Loading...</span>
    </div>


    <div class="mb-3">
        <label class="d-block mb-2 filter-title">Select Category</label>
        <select class="form-select custom-cat-select w-100 cat-select-dropdown">
            <option value="" disabled selected>Loading categories...</option>
        </select>


        <div class="subcategories-container mt-2 d-none">
            <span class="d-block mb-1 text-muted fw-bold" style="font-size:11px;">Sub-Categories:</span>
            <div class="subcategories-wrapper p-2"></div>
        </div>

        <div class="text-end mt-2">
            <a href="#" class="text-secondary text-decoration-underline clear-all-filters-btn" style="font-size:11px;">
                Clear All Filters
            </a>
        </div>
    </div>

    <hr class="my-3 text-muted">


    <div class="mb-4 price-filter-section">
        <div class="d-flex justify-content-between align-items-center mb-2 filter-header-toggle"
             data-bs-toggle="collapse" data-bs-target="#priceFilterCollapse">
            <span class="filter-title">Price Range (£)</span>
            <i class="bi bi-dash-lg"></i>
        </div>
        <div id="priceFilterCollapse" class="collapse show">
            <div class="px-1">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-6">
                        <label class="text-muted mb-1" style="font-size:11px;">Min Price</label>
                        <input type="number" class="form-control price-input-field min-price-input" placeholder="Min">
                    </div>
                    <div class="col-6">
                        <label class="text-muted mb-1" style="font-size:11px;">Max Price</label>
                        <input type="number" class="form-control price-input-field max-price-input" placeholder="Max">
                    </div>
                </div>
                <button type="button" class="btn btn-apply-price w-100 apply-price-btn">Apply Price</button>
            </div>
        </div>
    </div>

    <hr class="my-3 text-muted">

    <span class="d-block mb-3 filter-title text-muted" style="font-size:12px;">Filter By Attributes</span>
    <div class="attribute-filters-container"></div>

</template>


<section style="background:#f8f9fa; padding:24px 0;">
    <div class="container">
        <h2 id="hero-category-title" class="mb-2" style="color:var(--accent); font-size:1.4rem; font-weight:800;"> </h2>
        <p id="hero-category-desc" class="text-muted mb-0" style="font-size:14px; max-width:700px;"></p>
    </div>
</section>

<script>
$(document).ready(function () {
    'use strict';


    const BASE_API_URL   = `${baseAPI}wp-json/wp/v2`;
    const PARENT_SLUG    = '<?php echo $parent_slug; ?>';
    const SUB_SLUG       = '<?php echo $sub_slug; ?>';


    let allFetchedProducts  = [];
    let filteredProducts    = [];
    let categoriesGlobalTree = [];
    let currentPage         = 1;
    const ITEMS_PER_PAGE    = 12;

    let globalMinPrice       = 0;
    let globalMaxPrice       = 2000;
    let currentMinPriceFilter = null;
    let currentMaxPriceFilter = null;
    let currentSortOrder     = 'default';


    const templateContent = $('#sidebar-template').html();
    $('.sidebar-content-wrapper, .mobile-sidebar-content-wrapper').html(templateContent);

    const $catDropdowns = $('.cat-select-dropdown');

    
    function formatSlug(str) {
        if (!str) return '';
        return str.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function productIcon(name) {
        const n = (name || '').toLowerCase();
        if (n.includes('macbook') || n.includes('laptop')) return 'bi-laptop';
        if (n.includes('ipad')   || n.includes('tablet'))  return 'bi-tablet';
        return 'bi-phone';
    }


    function renderBreadcrumb() {
        const parentName = formatSlug(PARENT_SLUG);
        const subName    = formatSlug(SUB_SLUG);
        const parentUrl  = `${BASE_URL}category/${PARENT_SLUG}/`;

        $('#breadcrumb-dynamic').html(`
            <a href="${parentUrl}" class="breadcrumb-link">${escHtml(parentName)}</a>
            <span class="sep">/</span>
            <span class="breadcrumb-current">${escHtml(subName)}</span>
        `);

        $('#page-heading').text(subName);
        $('#page-sub').text(`Browse all ${subName} products under ${parentName}`);
        $('#hero-category-title').text(subName);
        $('#hero-category-desc').text(`Explore our full range of ${subName} devices. Filter by grade, storage, colour and more.`);


        $('.active-sub-label, #active-sub-label').text(`Browsing: ${subName}`);
    }


    function loadCategoriesTree() {
        $.ajax({
            url: `${BASE_API_URL}/categories-tree`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                categoriesGlobalTree = response || [];

                let optionsHtml = '<option value="" disabled selected>Choose Category...</option>';
                categoriesGlobalTree.forEach(cat => {
                    if (cat.slug !== 'uncategorized') {
                        optionsHtml += `<option value="${escHtml(cat.slug)}">${escHtml(cat.name)} (${cat.count || 0})</option>`;
                    }
                });
                $catDropdowns.html(optionsHtml);

       
                $catDropdowns.val(PARENT_SLUG);
                renderSubCategoryLinks(PARENT_SLUG);

        
                fetchProducts(SUB_SLUG);
            },
            error: function (err) {
                console.error('Tree error:', err);
                $catDropdowns.html('<option value="">Failed to load categories</option>');
            }
        });
    }

 
    function renderSubCategoryLinks(parentSlug) {
        const $containers = $('.subcategories-container');
        const $wrappers   = $('.subcategories-wrapper');
        $wrappers.empty();

        if (parentSlug === 'all') { $containers.addClass('d-none'); return; }

        const parentData = categoriesGlobalTree.find(c => c.slug === parentSlug);

        if (parentData && parentData.children && parentData.children.length > 0) {
            let html = '';
            parentData.children.forEach(sub => {
                const isActive = sub.slug === SUB_SLUG ? 'active-sub' : '';
                const baseUrl  = (BASE_URL.endsWith('/') ? BASE_URL : BASE_URL + '/');
                html += `
                    <a href="${baseUrl}category/${escHtml(parentSlug)}/${escHtml(sub.slug)}"
                       class="sub-cat-link ${isActive}">
                        <i class="bi bi-chevron-right me-1"></i>
                        ${escHtml(sub.name)} (${sub.count || 0})
                    </a>
                `;
            });
            $wrappers.html(html);
            $containers.removeClass('d-none');
        } else {
            $containers.addClass('d-none');
        }
    }


    function fetchProducts(slug) {
        $('#products-container').html(`
            <div class="w-100 text-center py-5">
                <div class="spinner-border" role="status" style="color:var(--accent);"></div>
                <p class="mt-2 text-muted" style="font-size:13px;">Fetching products...</p>
            </div>
        `);
        $('#pagination-container').remove();
        $('#result-count').text('');

        $.ajax({
            url: `${BASE_API_URL}/category/${slug}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                allFetchedProducts = response.products || [];
                calculatePriceRange(allFetchedProducts);
                buildAttributeFilters(allFetchedProducts);
                applyFilters();
            },
            error: function (err) {
                console.error('Product fetch error:', err);
                $('#products-container').html('<div class="alert alert-danger m-2">Error loading products.</div>');
            }
        });
    }


    function calculatePriceRange(products) {
        const prices = products.map(p => parseFloat(p.price) || 0).filter(p => p > 0);
        globalMinPrice = prices.length ? Math.floor(Math.min(...prices)) : 0;
        globalMaxPrice = prices.length ? Math.ceil(Math.max(...prices))  : 2000;

        if (currentMinPriceFilter === null) currentMinPriceFilter = globalMinPrice;
        if (currentMaxPriceFilter === null) currentMaxPriceFilter = globalMaxPrice;

        $('.min-price-input').val(currentMinPriceFilter).attr('min', globalMinPrice);
        $('.max-price-input').val(currentMaxPriceFilter).attr('max', globalMaxPrice);
    }


    function buildAttributeFilters(products) {
        const $containers = $('.attribute-filters-container');
        $containers.empty();

        const matrix = {};
        products.forEach(item => {
            (item.product_attributes || []).forEach(attr => {
                if (!matrix[attr.name]) matrix[attr.name] = new Set();
                (attr.values || attr.options || []).forEach(v => matrix[attr.name].add(v));
            });
        });

        let idx = 0;
        Object.keys(matrix).forEach(key => {
            idx++;
            const collapseId = `collapse-attr-${idx}`;
            const attrKey    = key.replace(/\s+/g, '-').toLowerCase();

            let block = `
                <div class="mb-3 border-bottom pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2 filter-header-toggle"
                         data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="true">
                        <span class="filter-title">${escHtml(key)}</span>
                        <i class="bi bi-dash-lg"></i>
                    </div>
                    <div id="${collapseId}" class="collapse show">
            `;

            let c = 0;
            matrix[key].forEach(val => {
                c++;
                const chkId = `chk-${attrKey}-${idx}-${c}`;
                block += `
                    <div class="form-check mb-1">
                        <input class="form-check-input unified-filter-chk" type="checkbox"
                               value="${escHtml(val)}" data-parent-attr="${attrKey}" id="${chkId}">
                        <label class="form-check-label" for="${chkId}">${escHtml(val)}</label>
                    </div>
                `;
            });

            block += `</div></div>`;
            $containers.append(block);
        });
    }


    function sortProducts(products) {
        const arr = [...products];
        switch (currentSortOrder) {
            case 'price-asc':  return arr.sort((a,b) => (parseFloat(a.price)||0) - (parseFloat(b.price)||0));
            case 'price-desc': return arr.sort((a,b) => (parseFloat(b.price)||0) - (parseFloat(a.price)||0));
            case 'name-asc':   return arr.sort((a,b) => (a.name||'').localeCompare(b.name||''));
            case 'name-desc':  return arr.sort((a,b) => (b.name||'').localeCompare(a.name||''));
            default:           return arr;
        }
    }


    function applyFilters(resetPage = true) {
        if (resetPage) currentPage = 1;

  
        const activeFilters = {};
        $('.unified-filter-chk:checked').each(function () {
            const attr = $(this).data('parent-attr');
            const val  = $(this).val();
            if (!activeFilters[attr]) activeFilters[attr] = [];
            activeFilters[attr].push(val);
        });

        filteredProducts = allFetchedProducts.filter(product => {
            const price = parseFloat(product.price) || 0;
            if (currentMinPriceFilter !== null && price < currentMinPriceFilter) return false;
            if (currentMaxPriceFilter !== null && price > currentMaxPriceFilter) return false;

            for (const filterName in activeFilters) {
                const allowed = activeFilters[filterName];
                const attr    = (product.product_attributes || [])
                    .find(a => a.name.replace(/\s+/g,'-').toLowerCase() === filterName);
                if (!attr) return false;
                const vals = attr.values || attr.options || [];
                if (!vals.some(v => allowed.includes(v))) return false;
            }
            return true;
        });

        filteredProducts = sortProducts(filteredProducts);
        renderGrid();
    }

    function renderGrid() {
        const $container = $('#products-container');
        $container.empty();
        $('#pagination-container').remove();
        $('#dynamic-product-schema').remove();

        const total      = filteredProducts.length;
        const totalPages = Math.ceil(total / ITEMS_PER_PAGE);
        const start      = (currentPage - 1) * ITEMS_PER_PAGE;
        const slice      = filteredProducts.slice(start, start + ITEMS_PER_PAGE);


        $('#result-count').text(`${total} product${total !== 1 ? 's' : ''} found`);

        if (total === 0) {
            $container.html(`
                <div class="text-center w-100 py-5 text-muted">
                    <i class="bi bi-search" style="font-size:2.5rem;"></i>
                    <h5 class="mt-3">No products match your filters.</h5>
                    <p style="font-size:13px;">Try adjusting or clearing your filters.</p>
                </div>
            `);
            return;
        }


        const schema = {
            "@context": "https://schema.org",
            "@type": "ItemList",
            "name": formatSlug(SUB_SLUG),
            "numberOfItems": slice.length,
            "itemListElement": []
        };

        slice.forEach((p, i) => {
            const img        = p.image || 'https://via.placeholder.com/120x150?text=No+Image';
            const priceLabel = p.price ? `£${parseFloat(p.price).toFixed(2)}` : 'Contact Price';
            const productUrl = `${BASE_URL}buy/${p.slug || '#'}`;

  
            let gradeText = 'Pristine';
            if (p.product_attributes) {
                const gradeAttr = p.product_attributes.find(a => a.name.toLowerCase() === 'grade');
                if (gradeAttr) gradeText = (gradeAttr.values || gradeAttr.options || [])[0] || gradeText;
            }


            schema.itemListElement.push({
                "@type": "ListItem",
                "position": i + 1,
                "item": {
                    "@type": "Product",
                    "name": p.name || 'Device',
                    "image": img,
                    "url": productUrl,
                    "offers": p.price ? {
                        "@type": "Offer",
                        "priceCurrency": "GBP",
                        "price": parseFloat(p.price).toFixed(2),
                        "availability": "https://schema.org/InStock",
                        "itemCondition": gradeText.toLowerCase().includes('new')
                            ? "https://schema.org/NewCondition"
                            : "https://schema.org/UsedCondition"
                    } : undefined
                }
            });

            const heartColor = p.is_wishlisted ? '#dc3545' : '#6c757d';
            const heartIcon  = p.is_wishlisted ? 'bi-heart-fill' : 'bi-heart';

            const cardHtml = `
                <div class="col">
                    <div class="product-card d-flex flex-column h-100 position-relative"
                         onmouseenter="this.querySelector('.card-overlay').style.cssText='opacity:1;visibility:visible;transform:translateY(0)';"
                         onmouseleave="this.querySelector('.card-overlay').style.cssText='opacity:0;visibility:hidden;transform:translateY(10px)';'"
                    >
                        <!-- Wishlist button -->
                        <div class="position-absolute" style="top:12px;right:12px;z-index:10;">
                            <button class="btn btn-link p-0 border-0 bg-transparent fs-4 lh-1 wishlist-btn"
                                    style="color:${heartColor};"
                                    data-product='${JSON.stringify(p).replace(/'/g,'&apos;')}'
                                    onclick="toggleWishlist(this, JSON.parse(this.getAttribute('data-product')))"
                                    aria-label="Wishlist">
                                <i class="bi ${heartIcon}"></i>
                            </button>
                        </div>

                        <div class="p-3 text-center flex-grow-1 d-flex flex-column justify-content-between">

                            <!-- Product image with hover overlay -->
                            <div class="position-relative overflow-hidden d-flex align-items-center justify-content-center my-3"
                                 style="height:170px; background:#fff; border:1px solid #f1f3f5; border-radius:12px; padding:10px;">

                                <a href="${productUrl}" class="d-block w-100 h-100">
                                    <img src="${img}" alt="${escHtml(p.name || '')}"
                                         class="img-fluid h-100"
                                         style="object-fit:contain; max-width:100%;"
                                         loading="lazy">
                                </a>

                                <div class="card-overlay d-flex flex-column align-items-center justify-content-center gap-2
                                            position-absolute top-0 start-0 w-100 h-100"
                                     style="background:rgba(255,255,255,.88); backdrop-filter:blur(4px);
                                            opacity:0; visibility:hidden; transform:translateY(10px);
                                            transition:all .3s ease-in-out; z-index:2;
                                            border-radius:12px; padding:20px;">
                                    <a href="/shop/buy/${p.slug || '#'}"
                                       class="btn btn-sm text-white py-2 fw-bold d-flex align-items-center justify-content-center"
                                       style="min-width: 100% !important;  font-size:14px; background:#13564f; border:none; min-width:110px; border-radius:6px;">
                                        <i class="bi bi-bag-check me-2"></i> Shop
                                    </a>
                                    <a href="https://www.recyclepro.co.uk/"
                                       class="btn btn-sm text-white py-2 fw-bold d-flex align-items-center justify-content-center"
                                       style="min-width: 100% !important; font-size:14px; background:#004465; border:none; min-width:110px; border-radius:6px;">
                                        <i class="bi bi-arrow-left-right me-2"></i> Sell
                                    </a>
                                </div>
                            </div>

                            <!-- Product info -->
                            <div>
                                <h3 class="product-title text-start mb-1">
                                    ${escHtml(p.name || 'Device Catalog Item')}
                                </h3>
                                <div class="text-start product-meta mb-2">
                                    Condition - ${escHtml(gradeText)}
                                    <i class="bi bi-info-circle" style="font-size:10px;"></i>
                                </div>
                                <div class="text-start product-price mb-3">${priceLabel}</div>
                            </div>

                            <a href="${productUrl}" class="btn btn-view-product w-100 py-2">View Product</a>
                        </div>
                    </div>
                </div>
            `;
            $container.append(cardHtml);
        });


        $('<script>').attr({ id:'dynamic-product-schema', type:'application/ld+json' })
            .text(JSON.stringify(schema)).appendTo('head');

        buildPagination(totalPages);
    }


    function buildPagination(totalPages) {
        $('#pagination-container').remove();
        if (totalPages <= 1) return;

        let html = `
            <div id="pagination-container" class="col-12 d-flex justify-content-center mt-4">
                <nav aria-label="Product pages">
                    <ul class="pagination pagination-sm gap-1">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link boundary-prev-btn" href="#" aria-label="Previous"
                               style="color:var(--accent);">&laquo;</a>
                        </li>
        `;

        for (let p = 1; p <= totalPages; p++) {
            const active = currentPage === p;
            html += `
                <li class="page-item ${active ? 'active' : ''}">
                    <a class="page-link numeric-index-btn" href="#" data-target-page="${p}"
                       style="${active ? 'background:var(--accent);border-color:var(--accent);color:#fff;' : 'color:var(--accent);'}">
                        ${p}
                    </a>
                </li>
            `;
        }

        html += `
                        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                            <a class="page-link boundary-next-btn" href="#" aria-label="Next"
                               style="color:var(--accent);">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        `;
        $('#products-container').after(html);
    }


    $('#sort-select').on('change', function () {
        currentSortOrder = $(this).val();
        applyFilters(true);
    });


    $(document).on('click', '.apply-price-btn', function () {
        const $sec = $(this).closest('.price-filter-section');
        let min = parseFloat($sec.find('.min-price-input').val());
        let max = parseFloat($sec.find('.max-price-input').val());
        if (isNaN(min)) min = globalMinPrice;
        if (isNaN(max)) max = globalMaxPrice;
        currentMinPriceFilter = min;
        currentMaxPriceFilter = max;
        $('.min-price-input').val(min);
        $('.max-price-input').val(max);
        applyFilters(true);
    });

  
    $(document).on('change', '.unified-filter-chk', function () {
        const attr      = $(this).data('parent-attr');
        const val       = $(this).val();
        const isChecked = $(this).is(':checked');
  
        $('.unified-filter-chk').each(function () {
            if ($(this).data('parent-attr') === attr && $(this).val() === val) {
                $(this).prop('checked', isChecked);
            }
        });
        applyFilters(true);
    });

   
    $(document).on('click', '.clear-all-filters-btn', function (e) {
        e.preventDefault();
        $('.unified-filter-chk').prop('checked', false);
        $('.min-price-input, .max-price-input').val('');
        currentMinPriceFilter = null;
        currentMaxPriceFilter = null;
        currentSortOrder = 'default';
        $('#sort-select').val('default');
        applyFilters(true);
    });


    $catDropdowns.on('change', function () {
        const slug = $(this).val();
        if (!slug) return;
        $catDropdowns.not(this).val(slug);
        renderSubCategoryLinks(slug);
        if (slug === 'all') {
            window.location.href = BASE_URL;
        } else {
            const url = (BASE_URL.endsWith('/') ? BASE_URL : BASE_URL + '/') + 'category/' + slug + '/';
            window.location.href = url;
        }
    });


    $(document).on('show.bs.collapse', '.collapse', function () {
        $(this).prev('.filter-header-toggle').find('i').removeClass('bi-plus-lg').addClass('bi-dash-lg');
    });
    $(document).on('hide.bs.collapse', '.collapse', function () {
        $(this).prev('.filter-header-toggle').find('i').removeClass('bi-dash-lg').addClass('bi-plus-lg');
    });


    $(document).on('click', '.numeric-index-btn', function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('target-page'));
        renderGrid();
        $('html,body').animate({ scrollTop: 0 }, 'fast');
    });
    $(document).on('click', '.boundary-prev-btn', function (e) {
        e.preventDefault();
        if (currentPage > 1) { currentPage--; renderGrid(); }
    });
    $(document).on('click', '.boundary-next-btn', function (e) {
        e.preventDefault();
        const maxPages = Math.ceil(filteredProducts.length / ITEMS_PER_PAGE);
        if (currentPage < maxPages) { currentPage++; renderGrid(); }
    });


    renderBreadcrumb();
    loadCategoriesTree();
});
</script>

<?php include 'includes/footer.php'; ?>