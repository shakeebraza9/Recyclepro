<?php
session_start();

// Get category slug from URL - handle both rewritten and direct query parameters
$slug = '';

// First check $_GET['slug'] (from .htaccess rewrite)
if (isset($_GET['slug'])) {
    $slug = sanitize_slug($_GET['slug']);
}
// If not found, check URI path
elseif (!empty($_SERVER['REQUEST_URI'])) {
    // Extract slug from /category/{slug}
    if (preg_match('/\/category\/([a-z0-9-]+)\/?(\?.*)?$/i', $_SERVER['REQUEST_URI'], $matches)) {
        $slug = sanitize_slug($matches[1]);
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
$categories_api_url = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/categories-tree";
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

// Fetch category and products from API
$api_url = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/category/" . $slug;

// Try to fetch API data
$context = stream_context_create(['http' => ['timeout' => 5]]);
$response = @file_get_contents($api_url, false, $context);

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
    $sub_api_url = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/category/" . $subcat['slug'];
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

-
        <div class="container">
                               <nav class="content-breadcrumb" aria-label="Breadcrumb">
                                   <a href="/shop/"><i class="bi bi-house"></i></a>

                        <span>/</span>
                        
                    <?php 

                    $parent_id = isset($category['parent']) ? (int)$category['parent'] : 0;
                    $is_subcategory = ($parent_id > 0);

                    if ($is_subcategory): 

                        $parent_cat = array_values(array_filter($all_categories, function($cat) use ($parent_id) {
                            return (int)$cat['id'] === $parent_id;
                        }));
                        
          
                        $parent_slug = !empty($parent_cat[0]['slug']) ? $parent_cat[0]['slug'] : '';
                        $parent_name = !empty($parent_cat[0]['name']) ? $parent_cat[0]['name'] : 'Parent';
                    ?>
                        <a href="/shop/category/<?php echo htmlspecialchars($parent_slug, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($parent_name, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                        <span class="mx-2">/</span>
                        <span class="text-muted"><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></span>

                    <?php else: ?>
                        <span class="text-muted"><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                    </nav>
            
        </div>
-

    <div class="container" style="margin-bottom: 50px;">
        <div class="row">
            <!-- Categories Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="categories-sidebar">
                    <h5 style="margin-bottom: 20px; font-weight: 700;">Categories</h5>
                    <p id="product-count"><?php echo $category['count']; ?> products</p>
                    <div class="categories-list">
                       <div class="category-tree">

   <div class="category-menu">

    <?php foreach ($all_categories as $cat): ?>

        <div class="category-block">

            <!-- Parent -->
            <a href="/shop/category/<?php echo htmlspecialchars($cat['slug']); ?>"
               class="parent-category">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>

            <!-- Children -->
            <?php if (!empty($cat['children'])): ?>
                <div class="children">

                    <?php foreach ($cat['children'] as $child): ?>
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
                            <div class="product-price">$${product.price}</div>
                            <div class="product-actions">
                                <a href="${product.permalink.replace(
    'https://www.recyclepro.co.uk/rp-dashboard/',
    'https://www.recyclepro.co.uk/shop/'
)}" class="product-link">
    View product
</a>

                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            document.getElementById('product-count').textContent = productsToDisplay.length + ' products';
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

        function filterProducts() {
            const selectedFilters = getSelectedFilters();
            const hasFilters = Object.keys(selectedFilters).length > 0;

            if (!hasFilters) {
                renderProducts(allProducts);
                return;
            }

            // Filter products based on selected attributes
            const filtered = allProducts.filter(product => {
                return Object.keys(selectedFilters).every(attrName => {
                    const selectedValues = selectedFilters[attrName];
                    const productAttr = product.product_attributes.find(a => a.name === attrName);
                    
                    if (!productAttr) return false;
                    
                    return selectedValues.some(value => productAttr.values.includes(value));
                });
            });

            renderProducts(filtered);
        }

        // Event listeners
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', filterProducts);
        });

        document.getElementById('clear-filters').addEventListener('click', () => {
            document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
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
