<?php
$pageTitle = 'Categories';
include __DIR__ . '/includes/header.php';
?>

<style>
    :root {
        --theme-green: #044339; /* Recycle Pro Header Green Color */
        --theme-light-green: #e6f0ee;
        --card-bg: #ffffff;
        --text-dark: #1a1d20;
    }

    .shop-categories-section {
        padding: 80px 0;
        background-color: #f9fbfb; /* Ultra-clean subtle background */
    }

    .shop-section-heading h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.8px;
        margin-bottom: 8px;
    }

    .shop-section-heading p {
        font-size: 1rem;
        color: #667085;
        font-weight: 400;
    }

    .shop-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 24px;
        margin-top: 50px;
    }

    /* Modern Minimalist Premium Card Design */
    .shop-category-card {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 32px 24px;
        text-decoration: none !important;
        color: unset !important;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-between;
        border: 1px solid #eaecf0;
        box-shadow: 0 2px 8px rgba(4, 67, 57, 0.02);
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        height: 100%;
        min-height: 180px;
    }

    /* Elegant Hover Interactions */
    .shop-category-card:hover {
        transform: translateY(-6px);
        border-color: var(--theme-green);
        box-shadow: 0 20px 32px rgba(4, 67, 57, 0.06);
    }

    /* Icons Styled Perfectly with Theme */
    .category-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background-color: var(--theme-light-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--theme-green);
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .shop-category-card:hover .category-icon-wrapper {
        background-color: var(--theme-green);
        color: #ffffff;
    }

    .shop-category-info {
        width: 100%;
    }

    .shop-category-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--text-dark);
        letter-spacing: -0.3px;
    }

    /* View Items Badge Layout */
    .card-footer-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #f2f4f7;
    }

    .product-count-badge {
        font-size: 0.85rem;
        color: #667085;
        font-weight: 500;
    }

    .view-arrow-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #f2f4f7;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .shop-category-card:hover .view-arrow-btn {
        background-color: var(--theme-green);
        color: #ffffff;
        transform: rotate(-45deg); /* Arrow moves diagonally on hover for modern look */
    }

    /* Elegant Skeleton Wave Loader */
    .skeleton-card {
        height: 190px;
        background: #ffffff;
        border: 1px solid #eaecf0;
        border-radius: 20px;
        padding: 32px 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .skeleton-element {
        background: linear-gradient(90deg, #f2f4f7 25%, #eaecf0 50%, #f2f4f7 75%);
        background-size: 200% 100%;
        animation: shimmerEffect 1.5s infinite linear;
        border-radius: 8px;
    }
    @keyframes shimmerEffect {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>

<main class="shop-page">
    <section class="shop-categories-section">
        <div class="container">
            <div class="shop-section-heading text-center text-md-start">
                <h2>Product Categories</h2>
                <p>Explore our collection of premium pristine devices.</p>
            </div>

            <div class="shop-categories-grid" id="categoriesContainer">
                <div class="skeleton-card">
                    <div class="skeleton-element" style="width: 48px; height: 48px; border-radius: 14px;"></div>
                    <div>
                        <div class="skeleton-element" style="width: 70%; height: 20px; mb-2: 8px;"></div>
                        <div class="skeleton-element" style="width: 40%; height: 14px;"></div>
                    </div>
                </div>
                <div class="skeleton-card">
                    <div class="skeleton-element" style="width: 48px; height: 48px; border-radius: 14px;"></div>
                    <div>
                        <div class="skeleton-element" style="width: 60%; height: 20px; mb-2: 8px;"></div>
                        <div class="skeleton-element" style="width: 35%; height: 14px;"></div>
                    </div>
                </div>
                <div class="skeleton-card">
                    <div class="skeleton-element" style="width: 48px; height: 48px; border-radius: 14px;"></div>
                    <div>
                        <div class="skeleton-element" style="width: 80%; height: 20px; mb-2: 8px;"></div>
                        <div class="skeleton-element" style="width: 50%; height: 14px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Base API Definition (Aapki existing setting ke mutabiq)
const BASE_API_URL = typeof baseAPI !== 'undefined' ? `${baseAPI}wp-json/wp/v2` : '/wp-json/wp/v2';

document.addEventListener("DOMContentLoaded", function () {
    // Icons matching mapping based on category slugs
    const iconMapping = {
        'earbuds': 'bi-earbuds',
        'headphones': 'bi-headphones',
        'smart-watches': 'bi-watch',
        'speakers': 'bi-speaker',
        'tablets': 'bi-tablet',
        'laptops': 'bi-laptop',
        'game-console': 'bi-controller',
        'mobile-phones': 'bi-phone',
        'default': 'bi-grid'
    };

    const container = document.getElementById('categoriesContainer');

    async function loadCategories() {
        try {
            const response = await fetch(`${BASE_API_URL}/categories-tree`);
            if (!response.ok) throw new Error('API request failed');
            
            const categories = await response.json();
            container.innerHTML = ''; // Clear loaders

            let activeCount = 0;

            categories.forEach(cat => {
                // Safely filter out the uncategorized array item
                if (cat.slug === 'uncategorized' || cat.name.toLowerCase() === 'uncategorized') {
                    return;
                }

                activeCount++;
                const iconClass = iconMapping[cat.slug] || iconMapping['default'];
                
                // Set neat counter string
                const countText = cat.products_count !== undefined 
                    ? `${cat.products_count} ${cat.products_count === 1 ? 'Device' : 'Devices'}`
                    : 'Explore';

                const cardHtml = `
                    <a href="/shop/category/${cat.slug}" class="shop-category-card">
                        <div>
                            <div class="category-icon-wrapper">
                                <i class="bi ${iconClass}"></i>
                            </div>
                            <div class="shop-category-info">
                                <h3>${cat.name}</h3>
                            </div>
                        </div>
                        <div class="card-footer-action">
                            <span class="product-count-badge">${countText}</span>
                            <div class="view-arrow-btn">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                `;
                container.insertAdjacentHTML('beforeend', cardHtml);
            });

            if (activeCount === 0) {
                container.innerHTML = `<div class="text-center w-100 text-muted py-5">No categories found.</div>`;
            }

        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="text-center w-100 py-5">
                    <p class="text-danger mb-3">Unable to synchronize categories grid.</p>
                    <button class="btn btn-sm btn-dark" style="background-color: var(--theme-green); border:none; padding: 8px 20px; border-radius: 8px;" onclick="location.reload()">Reload Page</button>
                </div>
            `;
        }
    }

    loadCategories();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>