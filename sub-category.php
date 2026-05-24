<?php

session_start();

function sanitize_slug($slug) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
}

$slug     = '';
$sub_slug = '';

if (isset($_GET['slug'])) {
    $slug = sanitize_slug($_GET['slug']);
}
if (isset($_GET['sub_slug'])) {
    $sub_slug = sanitize_slug($_GET['sub_slug']);
} elseif (!empty($_SERVER['REQUEST_URI'])) {
    if (preg_match('/\/category\/([a-z0-9-]+)(?:\/([a-z0-9-]+))?\/?(\?.*)?$/i', $_SERVER['REQUEST_URI'], $m)) {
        $slug     = sanitize_slug($m[1]);
        $sub_slug = isset($m[2]) ? sanitize_slug($m[2]) : '';
    }
}

if (!$slug) {
    echo "Error: No category slug provided";
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<style>
    :root {
        --accent: #13564f;
        --accent-light: #80beb5;
        --accent-dark: #1d4ed8;
        --surface: #fff;
        --surface-2: #f8fafc;
        --surface-3: #f1f5f9;
        --border: #e2e8f0;
        --border-strong: #cbd5e1;
        --text-primary: #0f172a;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --green: #16a34a;
        --green-light: #dcfce7;
        --red: #dc2626;
        --red-light: #fee2e2;
        --amber: #d97706;
        --amber-light: #fef3c7;
        --radius: 8px;
        --radius-lg: 12px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
        --shadow-md: 0 4px 12px rgba(0,0,0,.08);
        --shadow-lg: 0 10px 30px rgba(0,0,0,.12), 0 4px 6px rgba(0,0,0,.05);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--surface-2);
        color: var(--text-primary);
    }
    .filter-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .custom-cat-select {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 10px 14px;
        font-size: 13px;
        color: var(--text-secondary);
        background-color: var(--surface-3);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .custom-cat-select:focus {
        border-color: var(--accent-light);
        box-shadow: 0 0 0 3px rgba(19, 86, 79, 0.15);
        outline: none;
    }
    

    .subcategories-wrapper {
        background-color: var(--surface-3);
        border-radius: 6px;
        border: 1px dashed var(--border-strong);
    }
    .sub-cat-link {
        display: block;
        font-size: 12px;
        color: var(--text-secondary);
        padding: 6px 10px;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .sub-cat-link:hover {
        background-color: var(--green-light);
        color: var(--accent);
        font-weight: 600;
        padding-left: 14px;
    }
    .sub-cat-link i {
        font-size: 10px;
        color: var(--text-muted);
    }

  
    .form-check-input:checked {
        background-color: var(--accent) !important;
        border-color: var(--accent) !important;
    }
    .form-check-input {
        border-color: var(--text-muted);
        border-radius: 3px !important;
    }
    .form-check-label {
        font-size: 12px;
        color: var(--text-secondary);
    }
    .product-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: transform 0.2s, box-shadow 0.2s;
        background: var(--surface);
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .product-card-header {
        background-color: var(--surface-3);
        height: 25px;
        border-top-left-radius: calc(var(--radius) - 1px);
        border-top-right-radius: calc(var(--radius) - 1px);
    }
    .product-title {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.4;
        min-height: 34px;
    }
    .product-meta {
        font-size: 11px;
        color: var(--text-secondary);
    }
    .product-price {
        font-size: 16px;
        font-weight: 700;
        color: var(--accent);
    }
    .btn-view-product {
        border: 1px solid var(--accent);
        color: var(--accent);
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        background: transparent;
        transition: all 0.2s ease;
    }
    .btn-view-product:hover {
        background-color: var(--accent);
        color: var(--surface);
    }
    .finance-info-box {
        background-color: var(--red-light);
        border: 1px solid var(--border);
        border-radius: 4px;
        font-size: 10px;
        color: var(--red);
    }
    .mobile-filter-trigger {
        display: none;
        background-color: var(--accent);
        color: var(--surface);
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }
    .mobile-filter-trigger:hover, .mobile-filter-trigger:focus {
        background-color: var(--accent-dark);
        color: var(--surface);
    }


    .filter-header-toggle {
        cursor: pointer;
        user-select: none;
    }
    .filter-header-toggle i {
        transition: transform 0.2s ease;
    }
    .filter-header-toggle.collapsed i {
        transform: rotate(90deg);
    }


    .price-input-field {
        font-size: 12px;
        padding: 5px 8px;
        border: 1px solid var(--border-strong);
        border-radius: 4px;
        width: 100%;
    }
    .btn-apply-price {
        background-color: var(--accent);
        color: var(--surface);
        font-size: 11px;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        transition: background-color 0.2s;
    }
    .btn-apply-price:hover {
        background-color: var(--accent-light);
        color: var(--accent);
    }

    @media (max-width: 991.98px) {
        .desktop-sidebar {
            display: none !important;
        }
        .mobile-filter-trigger {
            display: inline-flex !important;
        }
    }
    .rp-breadcrumb .sep {
    font-size: 12px;
    margin: 0 4px;
    color: #6c757d;
}


@keyframes pulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
}

.animate-pulse {
    animation: pulse 1.5s infinite ease-in-out;
}


.skeleton-item {
    display: inline-block;
    width: 100px;
    height: 14px;
    background-color: #e0e0e0;
    border-radius: 4px;
    vertical-align: middle;
}


.breadcrumb-link {
    text-decoration: none;
    color: #4f5a66;
    font-size: 13px;
    font-weight: 500;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: #0d6efd; 
}

.breadcrumb-current {
    color: #1a1a1a;
    font-size: 13px;
    font-weight: 600;
}

</style>
<section class="rp-breadcrumb py-3 bg-light">
    <div class="container">
        <nav aria-label="Breadcrumb" class="d-flex align-items-center flex-wrap gap-2 text-muted small">
            <a href="/shop/" class="text-secondary decoration-none d-inline-flex align-items-center">
                <i class="bi bi-house-fill" style="font-size: 14px;"></i>
            </a>
            
            <span class="sep text-black-50">/</span>

            <div id="breadcrumb-dynamic" class="d-inline-flex align-items-center flex-wrap gap-2">
                <div class="breadcrumb-skeleton d-flex align-items-center gap-2">
                    <span class="skeleton-item animate-pulse"></span>
                    <span class="sep text-black-50">/</span>
                    <span class="skeleton-item animate-pulse" style="width: 80px;"></span>
                </div>
            </div>
        </nav>
    </div>
</section>

<div class="container">
    <h2 id="category-heading"> </h2>
    
    <div class="row d-lg-none mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center bg-white p-2 rounded shadow-sm border">
            <span class="fw-bold text-dark ps-2" style="font-size: 14px;">Catalog Products</span>
            <button class="btn mobile-filter-trigger py-2 px-3 d-inline-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="bi bi-sliders"></i> Filters & Categories
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

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="width: 300px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="mobileSidebarLabel" style="font-size: 16px;"><i class="bi bi-sliders me-2"></i>Filters Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-light">
        <div class="mobile-sidebar-content-wrapper"></div>
    </div>
</div>

<template id="sidebar-template">
    <div class="mb-3">
        <label class="d-block mb-2 filter-title">Select Category</label>
        <select class="form-select custom-cat-select w-100 cat-select-dropdown">
            <option value="" disabled selected>Loading categories...</option>
        </select>
        
        <div class="subcategories-container mt-2 d-none">
            <span class="d-block mb-1 text-muted fw-bold" style="font-size: 11px;">Sub-Categories:</span>
            <div class="subcategories-wrapper p-2"></div>
        </div>

        <div class="text-end mt-2">
            <a href="#" class="text-secondary text-decoration-underline clear-all-filters-btn" style="font-size: 11px;">Clear All Filters</a>
        </div>
    </div>

    <hr class="my-3 text-muted">

    <div class="mb-4 price-filter-section">
        <div class="d-flex justify-content-between align-items-center mb-2 filter-header-toggle" data-bs-toggle="collapse" data-bs-target="#priceFilterCollapse">
            <span class="filter-title">Price Range (£)</span>
            <i class="bi bi-dash-lg"></i>
        </div>
        <div id="priceFilterCollapse" class="collapse show">
            <div class="px-1">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-6">
                        <label class="text-muted mb-1" style="font-size: 11px;">Min Price</label>
                        <input type="number" class="form-control price-input-field min-price-input" placeholder="Min">
                    </div>
                    <div class="col-6">
                        <label class="text-muted mb-1" style="font-size: 11px;">Max Price</label>
                        <input type="number" class="form-control price-input-field max-price-input" placeholder="Max">
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-apply-price w-100 apply-price-btn">Apply Price</button>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-3 text-muted">
    <span class="d-block mb-3 filter-title text-muted" style="font-size: 12px; transform: translateY(-5px);">Filter By Attributes</span>

    <div class="attribute-filters-container"></div>

    
</template>
<section class="heading-section" style="background-color: #f8f9fa; padding: 20px 0; display: flex; justify-content: center  ;">
    <div class="container">
        <h2 class="category-title" style="text-align: Left ; color: #13564f;">
            Category
        </h2>
        <p style="text-align: Left ;">lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, vitae aliquet nunc nisl eget nunc.lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, vitae aliquet nunc nisl eget nunc lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, vitae aliquet nunc nisl eget nunc
        </p>
        
    </div>
</section>
<script>
$(document).ready(function() {
    'use strict';

    const BASE_API_URL = `${baseAPI}wp-json/wp/v2`;
    
    const activeCategorySlug = '<?php echo $slug; ?>';
    const activeSubCategorySlug = '<?php echo $sub_slug; ?>';
          

    let allFetchedProducts = []; 
    let filteredProducts = [];   
    let categoriesGlobalTree = []; 
    let currentPage = 1;
    const itemsPerPage = 12; 

    let globalMinPrice = 0;
    let globalMaxPrice = 2000;
    let currentMinPriceFilter = null; 
    let currentMaxPriceFilter = null;

    const templateContent = $('#sidebar-template').html();
    $('.sidebar-content-wrapper, .mobile-sidebar-content-wrapper').html(templateContent);

    const $catDropdowns = $('.cat-select-dropdown');


    

    function loadCategoriesTree() {
        $.ajax({
            url: `${BASE_API_URL}/categories-tree`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                categoriesGlobalTree = response || [];
                
                let optionsHtml = '<option value="" disabled selected>Choose Category...</option>';
                optionsHtml += '';

                if (Array.isArray(categoriesGlobalTree)) {
                    categoriesGlobalTree.forEach(cat => {
                        if (cat.slug !== 'uncategorized') {
                            optionsHtml += `<option value="${cat.slug}">${cat.name} (${cat.count || 0})</option>`;
                        }
                    });
                }
                $catDropdowns.html(optionsHtml);
                
                if (activeSubCategorySlug) {
                    let parentFinder = findParentSlugByChild(activeSubCategorySlug);
                    if (parentFinder) {
                        $catDropdowns.val(parentFinder);
                        renderSubCategoryLinksHtml(parentFinder);
                    }
                    fetchCategoryProducts(activeSubCategorySlug);
              
                } else if (activeCategorySlug) {
                    $catDropdowns.val(activeCategorySlug);
                    renderSubCategoryLinksHtml(activeCategorySlug);
                    fetchCategoryProducts(activeCategorySlug);
                } else {
                    $catDropdowns.val('mobile-phones').trigger('change');
                }
            },
            error: function(err) {
                console.error("Tree error: ", err);
                $catDropdowns.html('<option value="">Failed to load categories</option>');
            }
        });
    }

    function findParentSlugByChild(childSlug) {
        let foundSlug = null;
        categoriesGlobalTree.forEach(parent => {
            if (parent.children && Array.isArray(parent.children)) {
                parent.children.forEach(child => {
                    if (child.slug === childSlug) {
                        foundSlug = parent.slug;
                    }
                });
            }
        });
        return foundSlug;
    }

    function renderSubCategoryLinksHtml(parentSlug) {
        const $subContainers = $('.subcategories-container');
        const $subWrappers = $('.subcategories-wrapper');

        $subWrappers.empty();

        if (parentSlug === 'all') {
            $subContainers.addClass('d-none');
            return;
        }

        const selectedCategoryData = categoriesGlobalTree.find(cat => cat.slug === parentSlug);

        if (selectedCategoryData && selectedCategoryData.children && selectedCategoryData.children.length > 0) {
            let linksHtml = '';
            selectedCategoryData.children.forEach(sub => {
                const isActive = (sub.slug === activeSubCategorySlug) ? 'fw-bold text-primary' : '';
                
                linksHtml += `
                    <a href="http://localhost:8080/shop/category/${parentSlug}/${sub.slug}" class="sub-cat-link ${isActive}">
                        <i class="bi bi-chevron-right me-1"></i> ${sub.name} (${sub.count || 0})
                    </a>
                `;
            });
            
            $subWrappers.html(linksHtml);
            $subContainers.removeClass('d-none'); 
        } else {
            $subContainers.addClass('d-none'); 
        }
    }

    function fetchCategoryProducts(categorySlug) {
        $('#products-container').html(`
            <div class="w-100 text-center py-5">
                <div class="spinner-border text-primary" role="status" style="color: var(--accent) !important;"></div>
                <p class="mt-2 text-muted" style="font-size:13px;">Fetching stock items...</p>
            </div>
        `);
        $('#pagination-container').remove();

        $.ajax({
            url: `${BASE_API_URL}/category/${categorySlug}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                allFetchedProducts = response.products || [];
                
                calculatePriceRangeLimits(allFetchedProducts);

                filteredProducts = [...allFetchedProducts]; 
                currentPage = 1;

                buildDynamicAttributeFilters(allFetchedProducts);
                renderProductsGrid();
            },
            error: function(err) {
                console.error("Products engine runtime failure: ", err);
                $('#products-container').html('<div class="alert alert-danger m-2">Error connecting to server.</div>');
            }
        });
    }

    function calculatePriceRangeLimits(products) {
        if(products.length === 0) {
            globalMinPrice = 0;
            globalMaxPrice = 2000;
        } else {
            let prices = products.map(p => p.price ? parseFloat(p.price) : 0).filter(p => p > 0);
            if(prices.length > 0) {
                globalMinPrice = Math.floor(Math.min(...prices));
                globalMaxPrice = Math.ceil(Math.max(...prices));
            } else {
                globalMinPrice = 0;
                globalMaxPrice = 2000;
            }
        }
        
        if (currentMinPriceFilter === null) currentMinPriceFilter = globalMinPrice;
        if (currentMaxPriceFilter === null) currentMaxPriceFilter = globalMaxPrice;

        $('.min-price-input').val(currentMinPriceFilter).attr('min', globalMinPrice);
        $('.max-price-input').val(currentMaxPriceFilter).attr('max', globalMaxPrice);
    }

    function renderProductsGrid() {
        const $container = $('#products-container');
        $container.empty();

        if (filteredProducts.length === 0) {
            $container.html('<div class="text-center w-100 py-5 text-muted"><h5>No products match your filters.</h5></div>');
            $('#pagination-container').remove();
            return;
        }

        const totalItems = filteredProducts.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentSliceGroup = filteredProducts.slice(startIndex, endIndex);

        currentSliceGroup.forEach(p => {
            const fallbackImg = p.image || 'https://via.placeholder.com/120x150?text=No+Image';
            const priceLabel = p.price ? `£${parseFloat(p.price).toFixed(2)}` : 'Contact Price';
            
            let dynamicGradeBadge = "Condition - Pristine";
            if (p.product_attributes && Array.isArray(p.product_attributes)) {
                const searchGradeKey = p.product_attributes.find(item => item.name.toLowerCase() === 'grade');
                if (searchGradeKey) {
                    let gradeVal = '';
                    if (searchGradeKey.values && searchGradeKey.values.length > 0) gradeVal = searchGradeKey.values[0];
                    else if (searchGradeKey.options && searchGradeKey.options.length > 0) gradeVal = searchGradeKey.options[0];
                    
                    if (gradeVal) dynamicGradeBadge = `Condition - ${gradeVal}`;
                }
            }

            const estimatedKlarna = p.price ? (parseFloat(p.price) / 24).toFixed(2) : '10.00';

            const itemNodeHtml = `
                <div class="col">
                    <div class="product-card d-flex flex-column h-100">
                        <div class="product-card-header"></div>
                        <div class="p-3 text-center flex-grow-1 d-flex flex-column justify-content-between">
                            <div class="my-3">
                                <img src="${fallbackImg}" alt="${p.name}" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                            </div>
                            <div>
                                <h3 class="product-title text-start mb-1">${p.name || 'Device Catalog Item'}</h3>
                                <div class="text-start product-meta mb-2">
                                    ${dynamicGradeBadge} <i class="bi bi-info-circle" style="font-size: 10px;"></i>
                                </div>
                                <div class="text-start product-price mb-2">${priceLabel}</div>
                             
                            </div>
                            <div>
                                <a href="${BASE_URL}/buy/${p.slug || '#'}" class="btn btn-view-product w-100 py-2 mb-2">View product</a>
                           
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $container.append(itemNodeHtml);
        });

        buildPaginationNodes(totalPages);
    }

    function buildPaginationNodes(totalPages) {
        $('#pagination-container').remove();
        if (totalPages <= 1) return;

        let pagNodeHtml = `
            <div id="pagination-container" class="col-12 d-flex justify-content-center mt-4">
                <nav aria-label="Catalog navigation">
                    <ul class="pagination pagination-sm gap-1">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link boundary-prev-btn" href="#" aria-label="Previous" style="color: var(--accent);">&laquo;</a>
                        </li>
        `;

        for (let idx = 1; idx <= totalPages; idx++) {
            let activeStyle = currentPage === idx ? 'background-color: var(--accent); border-color: var(--accent); color: #fff;' : 'color: var(--accent);';
            pagNodeHtml += `
                <li class="page-item ${currentPage === idx ? 'active' : ''}">
                    <a class="page-link numeric-index-btn" href="#" data-target-page="${idx}" style="${activeStyle}">${idx}</a>
                </li>
            `;
        }

        pagNodeHtml += `
                        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                            <a class="page-link boundary-next-btn" href="#" aria-label="Next" style="color: var(--accent);">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        `;

        $('#products-container').after(pagNodeHtml);
    }

    function buildDynamicAttributeFilters(productsList) {
        const $attributeContainers = $('.attribute-filters-container');
        $attributeContainers.empty();

        let attributesMatrix = {};

        productsList.forEach(item => {
            if (item.product_attributes && Array.isArray(item.product_attributes)) {
                item.product_attributes.forEach(attr => {
                    const groupKey = attr.name;
                    if (!attributesMatrix[groupKey]) {
                        attributesMatrix[groupKey] = new Set();
                    }
                    
                    if (attr.values && Array.isArray(attr.values)) {
                        attr.values.forEach(opt => attributesMatrix[groupKey].add(opt));
                    } else if (attr.options && Array.isArray(attr.options)) {
                        attr.options.forEach(opt => attributesMatrix[groupKey].add(opt));
                    }
                });
            }
        });

        let groupIndex = 0;
        Object.keys(attributesMatrix).forEach(key => {
            groupIndex++;
            const safeCollapseId = `collapse-attr-${groupIndex}`;

            let filterGroupBlock = `
                <div class="mb-3 border-bottom pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2 filter-header-toggle" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#${safeCollapseId}" 
                         aria-expanded="true">
                        <span class="filter-title">${key}</span>
                        <i class="bi bi-dash-lg"></i>
                    </div>
                    <div id="${safeCollapseId}" class="collapse show attribute-collapse-wrapper">
            `;

            let internalCounter = 0;
            attributesMatrix[key].forEach(valString => {
                internalCounter++;
                const compoundId = `chk-${key.replace(/\s+/g, '-').toLowerCase()}-${groupIndex}-${internalCounter}`;
                
                filterGroupBlock += `
                    <div class="form-check mb-1">
                        <input class="form-check-input unified-filter-chk" type="checkbox" value="${valString}" data-parent-attr="${key}" id="${compoundId}">
                        <label class="form-check-label" for="${compoundId}">${valString}</label>
                    </div>
                `;
            });

            filterGroupBlock += `
                    </div>
                </div>
            `;
            $attributeContainers.append(filterGroupBlock);
        });
    }

    function applySelectedFilters() {
        let activeFiltersMap = {};
        
        $('.unified-filter-chk:checked').each(function() {
            const attrName = $(this).data('parent-attr');
            const checkedVal = $(this).val();

            if (!activeFiltersMap[attrName]) {
                activeFiltersMap[attrName] = [];
            }
            activeFiltersMap[attrName].push(checkedVal);
        });

        filteredProducts = allFetchedProducts.filter(product => {
            const itemPrice = product.price ? parseFloat(product.price) : 0;
            
  
            if (currentMinPriceFilter !== null && itemPrice < currentMinPriceFilter) {
                return false;
            }
            if (currentMaxPriceFilter !== null && itemPrice > currentMaxPriceFilter) {
                return false;
            }


            for (let filterName in activeFiltersMap) {
                const allowedValues = activeFiltersMap[filterName];
                const productAttr = product.product_attributes ? product.product_attributes.find(a => a.name === filterName) : null;
                
                if (!productAttr) return false;

                const itemValuesArray = productAttr.values || productAttr.options || [];
                const hasMatchingValue = itemValuesArray.some(val => allowedValues.includes(val));
                
                if (!hasMatchingValue) return false; 
            }
            return true;
        });

        currentPage = 1; 
        renderProductsGrid();
    }



    $(document).on('click', '.apply-price-btn', function() {
        const parentSection = $(this).closest('.price-filter-section');
        let minVal = parseFloat(parentSection.find('.min-price-input').val());
        let maxVal = parseFloat(parentSection.find('.max-price-input').val());

        if (isNaN(minVal)) minVal = globalMinPrice;
        if (isNaN(maxVal)) maxVal = globalMaxPrice;

        currentMinPriceFilter = minVal;
        currentMaxPriceFilter = maxVal;

        $('.min-price-input').val(minVal);
        $('.max-price-input').val(maxVal);

        applySelectedFilters();
    });

    $(document).on('show.bs.collapse', '.collapse', function () {
        $(this).prev('.filter-header-toggle').find('i').removeClass('bi-plus-lg').addClass('bi-dash-lg');
    });

    $(document).on('hide.bs.collapse', '.collapse', function () {
        $(this).prev('.filter-header-toggle').find('i').removeClass('bi-dash-lg').addClass('bi-plus-lg');
    });

    $(document).on('change', '.unified-filter-chk', function() {
        const targetValue = $(this).val();
        const targetAttr = $(this).data('parent-attr');
        const isChecked = $(this).is(':checked');

        $('.unified-filter-chk').each(function() {
            if ($(this).data('parent-attr') === targetAttr && $(this).val() === targetValue) {
                $(this).prop('checked', isChecked);
            }
        });

        applySelectedFilters();
    });


    $(document).on('click', '.clear-all-filters-btn', function(e) {
        e.preventDefault();
        

        $('.unified-filter-chk').prop('checked', false);
        

        $('.min-price-input').val('');
        $('.max-price-input').val('');

        currentMinPriceFilter = null;
        currentMaxPriceFilter = null;
        
        applySelectedFilters();
    });

    $catDropdowns.on('change', function() {
        const parentSlugValue = $(this).val();
        if (!parentSlugValue) return;
      
        $catDropdowns.not(this).val(parentSlugValue);
        renderSubCategoryLinksHtml(parentSlugValue);

        if (parentSlugValue === 'all') {
            window.location.href = 'http://localhost:8080/shop';
        } else {
            fetchCategoryProducts(parentSlugValue);
              
        }
    });

    $(document).on('click', '.numeric-index-btn', function(event) {
        event.preventDefault();
        currentPage = parseInt($(this).data('target-page'));
        renderProductsGrid();
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    $(document).on('click', '.boundary-prev-btn', function(event) {
        event.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderProductsGrid();
        }
    });

    $(document).on('click', '.boundary-next-btn', function(event) {
        event.preventDefault();
        const absoluteMaxPages = Math.ceil(filteredProducts.length / itemsPerPage);
        if (currentPage < absoluteMaxPages) {
            currentPage++;
            renderProductsGrid();
        }
    });

    loadCategoriesTree();

function renderSimpleBreadcrumb(slug, subSlug = '') {
    const container = document.getElementById('breadcrumb-dynamic');
    const MainCategoryHeading = document.getElementById('category-heading');
    // console.log(mainCategoryHeading);
    if (!container) return;


    function formatSlugToName(str) {
        if (!str) return '';
        return str
            .split('-')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    let breadcrumbHtml = '';
    const mainCategoryName = formatSlugToName(slug);
    const subCategoryName = formatSlugToName(subSlug);

    if (mainCategoryName) {
        if (subCategoryName) {

            breadcrumbHtml += `
                <span class="breadcrumb-link">${mainCategoryName}</span>
                <span class="sep">/</span>
                <span class="breadcrumb-current">${subCategoryName}</span>
            `;
        } else {

            breadcrumbHtml += `
                <span class="breadcrumb-current">${mainCategoryName}</span>
            `;
        }
    }

    container.innerHTML = breadcrumbHtml;
    MainCategoryHeading.textContent = formatSlugToName(activeCategorySlug);
}


renderSimpleBreadcrumb(activeCategorySlug, activeSubCategorySlug);

});





</script>

<?php include 'includes/footer.php'; ?>
