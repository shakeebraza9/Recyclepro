<?php
session_start();

// Get category slug from URL - handle both rewritten and direct query parameters
$slug = '';
$sub_slug = '';

// First check $_GET['slug'] (from .htaccess rewrite)
if (isset($_GET['slug'])) {
    $slug = sanitize_slug($_GET['slug']);
}
// Check for sub_slug parameter
if (isset($_GET['sub_slug'])) {
    $sub_slug = sanitize_slug($_GET['sub_slug']);
}
// If not found, check URI path
elseif (!empty($_SERVER['REQUEST_URI'])) {
    // Extract slug from /category/{slug} or /category/{slug}/{sub_slug}
    if (preg_match('/\/category\/([a-z0-9-]+)(?:\/([a-z0-9-]+))?\/?(\?.*)?$/i', $_SERVER['REQUEST_URI'], $matches)) {
        $slug = sanitize_slug($matches[1]);
        $sub_slug = isset($matches[2]) ? sanitize_slug($matches[2]) : '';
    }
}

if (!$slug) {
    echo "Error: No category slug provided";
    exit;
}

function sanitize_slug($slug) {
    return preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
}

// Fetch all categories for sidebar navigation
$categories_api_url = "http://localhost:8080/bkrecyclepro/wp-json/wp/v2/categories-tree";
$categories_context = stream_context_create(['http' => ['timeout' => 5]]);
$categories_response = @file_get_contents($categories_api_url, false, $categories_context);

$all_categories = [];
if ($categories_response !== false) {
    $all_categories = json_decode($categories_response, true) ?: [];
    // Filter out empty categories
    $all_categories = array_filter($all_categories, function($cat) {
        return $cat['count'] > 0;
    });
}

// Use sub_slug if provided, otherwise use main slug
$api_slug = $sub_slug ?: $slug;

// Fetch category and products from API
$api_url = "http://localhost:8080/bkrecyclepro/wp-json/wp/v2/category/" . $api_slug;

// Try to fetch API data
$context = stream_context_create(['http' => ['timeout' => 5]]);
$response = @file_get_contents($api_url, false, $context);
// var_dump($response);
if ($response === false) {
    http_response_code(500);
    die("Error: Could not fetch category data from API. URL: " . htmlspecialchars($api_url));
}

$data = json_decode($response, true);

if (!$data || !isset($data['category'])) {
    http_response_code(404);
    die("Error: Category '" . htmlspecialchars($slug) . "' not found");
}

$category = $data['category'];
$products = $data['products'] ?? [];

// Get subcategories for this category
$subcategories = array_filter($all_categories, function($cat) use ($category) {
    return isset($cat['parent']) && $cat['parent'] == $category['id'];
});

// If there are subcategories, fetch their products too
$all_products = $products;
foreach ($subcategories as $subcat) {
    $sub_api_url = "http://localhost:8080/bkrecyclepro/wp-json/wp/v2/category/" . $subcat['slug'];
    $sub_response = @file_get_contents($sub_api_url, false, $context);

    if ($sub_response !== false) {
        $sub_data = json_decode($sub_response, true);
        if ($sub_data && isset($sub_data['products'])) {
            $all_products = array_merge($all_products, $sub_data['products']);
        }
    }
}

// Remove duplicates based on product ID
$all_products = array_unique($all_products, SORT_REGULAR);

// Extract all unique attributes from products
$all_attributes = [];
foreach ($all_products as $product) {
    if (isset($product['product_attributes'])) {
        foreach ($product['product_attributes'] as $attr) {
            $attr_name = $attr['name'];
            if (!isset($all_attributes[$attr_name])) {
                $all_attributes[$attr_name] = [
                    'slug' => $attr['slug'],
                    'values' => []
                ];
            }
            // Merge values and keep unique
            $all_attributes[$attr_name]['values'] = array_unique(
                array_merge($all_attributes[$attr_name]['values'], $attr['values'])
            );
        }
    }
}

// Sort attribute values
foreach ($all_attributes as &$attr) {
    sort($attr['values']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Category</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    
</head>
<body>
    <?php include 'includes/header.php'; ?>

     <section class="breadcrumb">
        <div class="container">
                    <nav class="content-breadcrumb" aria-label="Breadcrumb">
                                   <a href="/shop/"><i class="bi bi-house"></i></a>

                        <span>/</span>
                        
                        <?php if ($sub_slug): ?>
                            <!-- Sub-category breadcrumb -->
                            <a href="/shop/category/<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php 
                                // Find parent category name
                                $parent_cat = array_values(array_filter($all_categories, function($cat) use ($slug) {
                                    return $cat['slug'] === $slug;
                                }));
                                echo htmlspecialchars($parent_cat[0]['name'] ?? ucfirst($slug), ENT_QUOTES, 'UTF-8');
                                ?>
                            </a>
                            <span>/</span>
                            <span><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php else: ?>
                            <!-- Main category breadcrumb -->
                            <span><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </nav>

        </div>
    </section>


    <div class="container" style="margin-bottom: 50px;">
        <div class="row">
            <!-- Categories Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="categories-sidebar">
                    <h5 style="margin-bottom: 20px; font-weight: 700;">Categories</h5>
                    <div class="categories-list">
                       <div class="category-tree">

   <div class="category-menu">

    <?php foreach ($all_categories as $cat): ?>
        
        <?php 
        // Filter children to only show those with products
        $children_with_products = [];
        if (!empty($cat['children'])) {
            $children_with_products = array_filter($cat['children'], function($child) {
                return $child['count'] > 0;
            });
        }
        ?>

        <div class="category-block">

            <!-- Parent -->
            <a href="/shop/category/<?php echo htmlspecialchars($cat['slug']); ?>"
               class="parent-category">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>

            <!-- Children (only show if they have products) -->
            <?php if (!empty($children_with_products)): ?>
                <div class="children">

                    <?php foreach ($children_with_products as $child): ?>
                        <a href="/shop/category/<?php echo htmlspecialchars($cat['slug']); ?>/<?php echo htmlspecialchars($child['slug']); ?>"
                           class="child-category">
                             <?php echo htmlspecialchars($child['name']); ?>
                        </a>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

</div>

</div>
                    </div>
                </div>

                <!-- Filters Sidebar -->
                <div class="filters-sidebar" style="margin-top: 30px;">
                    <h5 style="margin-bottom: 20px; font-weight: 700;">Filters</h5>

                    <!-- Price Filter -->
                    <div class="filter-group" style="margin-bottom: 25px;">
                        <div class="filter-title" style="margin-bottom: 15px;">Price</div>
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <input
                                type="number"
                                id="price-min"
                                class="filter-price-input"
                                placeholder="Min"
                                min="0"
                                style="    width: 50%; flex: 1; padding: 6px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;"
                            >
                            <input
                                type="number"
                                id="price-max"
                                class="filter-price-input"
                                placeholder="Max"
                                min="0"
                                style= "  width: 50%;  flex: 1; padding: 6px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;"
                            >
                        </div>
                        <button id="apply-price-filter" style="width: 100%; padding: 8px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                            Apply Price
                        </button>
                    </div>

                    <?php foreach ($all_attributes as $attr_name => $attr_data): ?>
                        <div class="filter-group">
                            <div class="filter-title"><?php echo htmlspecialchars($attr_name); ?></div>
                            <?php foreach ($attr_data['values'] as $value): ?>
                                <div class="filter-option">
                                    <input
                                        type="checkbox"
                                        class="filter-checkbox"
                                        data-attribute="<?php echo htmlspecialchars($attr_name); ?>"
                                        value="<?php echo htmlspecialchars($value); ?>"
                                    >
                                    <label><?php echo htmlspecialchars($value); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <button class="clear-filters-btn" id="clear-filters">Clear All Filters</button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="products-grid" id="products-container">
                    <!-- Products will be loaded here via AJAX -->
                    <div class="loading">
                        <div class="loading-spinner"></div>
                        <p style="margin-top: 15px;">Loading products...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const categorySlug = '<?php echo htmlspecialchars($slug); ?>';
        const allProducts = <?php echo json_encode($all_products); ?>;

        // Calculate min/max prices for display
        function getMinMaxPrices() {
            let minPrice = Infinity;
            let maxPrice = -Infinity;
            
            allProducts.forEach(product => {
                const price = parseFloat(product.price) || 0;
                minPrice = Math.min(minPrice, price);
                maxPrice = Math.max(maxPrice, price);
            });
            
            return { minPrice: minPrice === Infinity ? 0 : minPrice, maxPrice: maxPrice === -Infinity ? 0 : maxPrice };
        }

        const prices = getMinMaxPrices();

        // Set placeholder values
        document.getElementById('price-min').placeholder = `Min: £${prices.minPrice.toFixed(2)}`;
        document.getElementById('price-max').placeholder = `Max: £${prices.maxPrice.toFixed(2)}`;

        function renderProducts(productsToDisplay) {
            const container = document.getElementById('products-container');
            
            if (productsToDisplay.length === 0) {
                container.innerHTML = '<div class="no-products" style="grid-column: 1/-1;">No products found matching your filters.</div>';
                return;
            }

            let html = '';
          
            productsToDisplay.forEach(product => {
                html += `
                    <div class="product-card">
                    <a href="${product.permalink.replace(
    'https://www.recyclepro.co.uk/rp-dashboard/',
    'https://www.recyclepro.co.uk/shop/'
)}" class="product-link-img">
   

                        <img src="${product.image}" alt="${product.name}" class="product-image">
                        </a>
                        <div class="product-info">
                            <h3 class="product-name">${product.name}</h3>
                            <div class="product-price">£${product.price}</div>
                            <div class="product-actions">
                                <a href="http://localhost:8080/shop/buy/motorola-edge-60-fusion/" class="product-link">
    View product
</a>

                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            // document.getElementById('product-count').textContent = productsToDisplay.length + ' products';
        }

        function getSelectedFilters() {
            const filters = {};
            document.querySelectorAll('.filter-checkbox:checked').forEach(checkbox => {
                const attr = checkbox.dataset.attribute;
                if (!filters[attr]) {
                    filters[attr] = [];
                }
                filters[attr].push(checkbox.value);
            });
            return filters;
        }

        function getPriceRange() {
            const minPrice = parseFloat(document.getElementById('price-min').value) || 0;
            const maxPrice = parseFloat(document.getElementById('price-max').value) || Infinity;
            return { minPrice, maxPrice };
        }

        function filterProducts() {
            const selectedFilters = getSelectedFilters();
            const priceRange = getPriceRange();
            const hasFilters = Object.keys(selectedFilters).length > 0;
            const hasPriceFilter = document.getElementById('price-min').value !== '' || document.getElementById('price-max').value !== '';

            if (!hasFilters && !hasPriceFilter) {
                renderProducts(allProducts);
                return;
            }

            // Filter products based on selected attributes and price
            const filtered = allProducts.filter(product => {
                // Check price filter
                const productPrice = parseFloat(product.price) || 0;
                if (hasPriceFilter) {
                    if (productPrice < priceRange.minPrice || productPrice > priceRange.maxPrice) {
                        return false;
                    }
                }

                // Check attribute filters
                if (hasFilters) {
                    return Object.keys(selectedFilters).every(attrName => {
                        const selectedValues = selectedFilters[attrName];
                        const productAttr = product.product_attributes.find(a => a.name === attrName);
                        
                        if (!productAttr) return false;
                        
                        return selectedValues.some(value => productAttr.values.includes(value));
                    });
                }

                return true;
            });

            renderProducts(filtered);
        }

        // Event listeners
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', filterProducts);
        });

        document.getElementById('apply-price-filter').addEventListener('click', filterProducts);

        document.getElementById('price-min').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') filterProducts();
        });

        document.getElementById('price-max').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') filterProducts();
        });

        document.getElementById('clear-filters').addEventListener('click', () => {
            document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('price-min').value = '';
            document.getElementById('price-max').value = '';
            renderProducts(allProducts);
        });

        // Initial render
        renderProducts(allProducts);

        function addToCart(product) {
            cartManager.addItem(product).then(() => {
                showToast('Product added to cart!', 'success');
            });
        }

        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : '#007bff'};
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                z-index: 9999;
                font-weight: 500;
            `;
            toast.textContent = message;

            document.body.appendChild(toast);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>

    <?php include 'includes/footer.php'; ?>
