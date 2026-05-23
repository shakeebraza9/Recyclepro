
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
    <?php include_once 'css/category_style.php'; ?>

 
    <section class="rp-breadcrumb">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <a href="/shop/"><i class="bi bi-house-fill" style="font-size:12px;"></i></a>
                <span class="sep">/</span>
                <span id="breadcrumb-dynamic">
                    <span class="skeleton" style="display:inline-block;width:110px;height:13px;vertical-align:middle;"></span>
                </span>
            </nav>
        </div>
    </section>

    <div class="subcategory-strip" id="subcategory-strip" style="display:none;">
        <div class="container" style="position:relative;">
            <div class="subcat-inner" id="subcat-tabs"></div>
            <button class="subcat-scroll-btn" id="subcat-right" style="position:absolute;right:0;top:50%;transform:translateY(-50%);">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="container">
        <div class="shop-layout">

 
            <aside class="shop-sidebar">

          
                <div class="sidebar-card">
                    <div class="cat-search-wrap" id="cat-search-wrap">
                        <span class="cat-search-label">Browse by Category</span>
                        <!-- <div class="cat-search-input-row" id="cat-search-row">
                            <i class="bi bi-search search-icon"></i>
                            <input
                                type="text"
                                class="cat-search-input"
                                id="cat-search-input"
                                placeholder="Search categories…"
                                autocomplete="off"
                            >
                            <button class="cat-search-clear" id="cat-search-clear" title="Clear">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div> -->

             
                        <div class="cat-dropdown" id="cat-dropdown"></div>

                        <!-- SELECTED CHIP -->
                        <div class="selected-cat-chip" id="selected-cat-chip">
                            <div class="chip-icon"><i class="bi bi-tag-fill"></i></div>
                            <div class="chip-info">
                                <div class="chip-name" id="chip-name">-</div>
                                <div class="chip-sub" id="chip-sub"></div>
                            </div>
                            <button class="chip-remove" id="chip-remove" title="Remove filter">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- <div class="select-cat-label">All Categories</div> -->
                   
                    <div id="sidebar-categories">
                        <?php for($i=0;$i<5;$i++): ?>
                        <div class="skeleton skel-line" style="margin:10px 14px;"></div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- FILTERS CARD -->
                <div class="sidebar-card" id="filters-card">
                    <div class="filter-group">
                        <div class="filter-group-header" data-target="">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 10px;">
                                <span class="filter-group-title" style="margin: 0; padding: 0; white-space: nowrap;">Price Range</span>
                                <button class="clear-all-link" id="clear-all-btn" style="margin: 0; padding: 0; white-space: nowrap; background: none; border: none; cursor: pointer;">Clear All Filters</button>
                            </div>
                        </div>
                        <div class="filter-group-body" id="fg-price">
                            <div class="price-row">
                                <input type="number" id="price-min" class="price-input" placeholder="From" min="0">
                                <span class="price-sep">&mdash;</span>
                                <input type="number" id="price-max" class="price-input" placeholder="To" min="0">
                            </div>
                            <button class="btn-apply" id="apply-price">Apply</button>
                        </div>
                    </div>
                    <div id="attribute-filters">
                        
                    </div>
                </div>

            </aside>

  
            <main>
        
                <div class="results-bar">
                    <div class="results-count" id="results-count">
                        <span class="skeleton" style="display:inline-block;width:150px;height:13px;"></span>
                    </div>
                    <div class="mobile_resposive_div">
                    <button class="mobile-filter-btn" id="mobile-filter-btn">
                        <i class="bi bi-funnel"></i>
                        Filters
                    </button>
                  
                    <select class="sort-sel" id="sort-main">
                        <option value="">Sort by</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="name-asc">Name: A&ndash;Z</option>
                        <option value="name-desc">Name: Z&ndash;A</option>
                    </select>
                      </div>
                    
                </div>

                <div class="products-grid" id="products-container">
                    
                    <?php for($i=0;$i<6;$i++): ?>
                    <div class="skel-card">
                        <div class="skeleton skel-img"></div>
                        <div class="skel-body">
                            <div class="skeleton skel-line w40"></div>
                            <div class="skeleton skel-line w80"></div>
                            <div class="skeleton skel-line w60"></div>
                            <div class="skeleton skel-line w40"></div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="pagination-wrap" id="pagination-wrap"></div>
            </main>

        </div>
    </div>

  <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <script>
    (() => {
        'use strict';

        const SLUG     = '<?php echo addslashes($slug); ?>';
        const SUB_SLUG = '<?php echo addslashes($sub_slug); ?>';
        const API_BASE = 'https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2';

        let allProducts   = [];
        let allCategories = [];
        let categoryInfo  = {};
        let currentSort   = '';
        let currentPage = 1;
        const perPage = 12
        let activeCatFilter = null; // { slug, name, count }
        let focusedDropdownIdx = -1;

        const $ = id => document.getElementById(id);

        // DOM refs
        const productsContainer = $('products-container');
        const categoriesMenu    = $('sidebar-categories');
        const attrFilters       = $('attribute-filters');
        const breadcrumbEl      = $('breadcrumb-dynamic');
        const priceMinEl        = $('price-min');
        const priceMaxEl        = $('price-max');
        const resultsCount      = $('results-count');
        const paginationWrap = $('pagination-wrap');
        const sortMain          = $('sort-main');
        const subcatStrip       = $('subcategory-strip');
        const subcatTabs        = $('subcat-tabs');

        // Category search DOM
        // const catSearchInput  = $('cat-search-input');
        // const catSearchClear  = $('cat-search-clear');
        const catDropdown     = $('cat-dropdown');
        const catSearchRow    = $('cat-search-row');
        const selectedChip    = $('selected-cat-chip');
        const chipName        = $('chip-name');
        const chipSub         = $('chip-sub');
        const chipRemove      = $('chip-remove');

    
   ;

        // ── Fetch ────────────────────────────────────────────────────
        async function fetchJSON(url) {
            const res = await fetch(url, { signal: AbortSignal.timeout(8000) });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        }

        // ── INIT ─────────────────────────────────────────────────────
        async function init() {
            try {
                const [catsData, mainData] = await Promise.all([
                    fetchJSON(API_BASE + '/categories-tree'),
                    fetchJSON(API_BASE + '/category/' + (SUB_SLUG || SLUG))
                ]);

                allCategories = (catsData || []).filter(c => c.count > 0);
                renderCategoriesSidebar();

                if (!mainData || !mainData.category) { showError('Category not found.'); return; }
                categoryInfo = mainData.category;
                allProducts  = mainData.products || [];

                document.title = categoryInfo.name + ' - RecyclePro';
                renderBreadcrumb();
                renderSubcatTabs();

                // Fetch subcategory products
                const subs = allCategories.filter(c => c.parent && c.parent == categoryInfo.id && c.count > 0);
                if (subs.length) {
                    const results = await Promise.all(
                        subs.map(s => fetchJSON(API_BASE + '/category/' + s.slug)
                            .then(d => d && d.products ? d.products : []).catch(() => []))
                    );
                    results.forEach(p => { allProducts = allProducts.concat(p); });
                }

                // Deduplicate
                const seen = new Set();
                allProducts = allProducts.filter(p => { if(seen.has(p.id)) return false; seen.add(p.id); return true; });

                buildAttrFilters();

                const prices = getMinMax();
                priceMinEl.placeholder = 'From: £' + prices.min.toFixed(2);
                priceMaxEl.placeholder = 'To: £'   + prices.max.toFixed(2);

                applyFilters();
            } catch(err) {
                console.error(err);
                showError('Could not load products. Please try again.');
            }
        }

        // ── CATEGORY SEARCH ──────────────────────────────────────────

        // Build a flat list of all searchable categories (parent + children)
        function buildSearchList() {
            const list = [];
            allCategories.forEach(cat => {
                list.push({ slug: cat.slug, name: cat.name, count: cat.count, parent: null, parentName: null });
                (cat.children || []).filter(c => c.count > 0).forEach(ch => {
                    list.push({ slug: ch.slug, name: ch.name, count: ch.count, parent: cat.slug, parentName: cat.name });
                });
            });
            return list;
        }

        // Icons map for common categories
        const catIcons = {
            'mobile-phones':'phone','tablets':'tablet','laptops':'laptop','earbuds':'headphones',
            'headphones':'headset','speakers':'speaker','smart-watches':'smartwatch',
            'game-console':'controller','default':'tag'
        };
        function getCatIcon(slug) {
            for (const key in catIcons) {
                if (slug && slug.includes(key)) return catIcons[key];
            }
            return catIcons.default;
        }

        function renderDropdown(query) {
            const q = (query || '').trim().toLowerCase();
            const list = buildSearchList();

            let matches;
            if (!q) {
                // Show all when empty and focused
                matches = list.slice(0, 20);
            } else {
                matches = list.filter(c =>
                    c.name.toLowerCase().includes(q) ||
                    (c.parentName && c.parentName.toLowerCase().includes(q))
                );
            }

            focusedDropdownIdx = -1;

            if (!matches.length) {
                catDropdown.innerHTML = '<div class="cat-dropdown-empty"><i class="bi bi-search" style="margin-right:6px;"></i>No categories found</div>';
                openDropdown();
                return;
            }

            catDropdown.innerHTML = matches.map((cat, idx) => {
                const icon = getCatIcon(cat.slug);
                const href = cat.parent
                    ? '/shop/category/' + esc(cat.parent) + '/' + esc(cat.slug)
                    : '/shop/category/' + esc(cat.slug);

                // Highlight match
                const displayName = q
                    ? cat.name.replace(new RegExp('(' + escRegex(q) + ')', 'gi'), '<mark style="background:#fef08a;border-radius:2px;padding:0 1px;">$1</mark>')
                    : esc(cat.name);

                return '<a class="cat-dropdown-item" href="' + href + '" data-idx="' + idx + '" data-slug="' + esc(cat.slug) + '" data-name="' + esc(cat.name) + '" data-parent="' + esc(cat.parent || '') + '" data-parentname="' + esc(cat.parentName || '') + '" data-count="' + cat.count + '">' +
                    '<div class="cat-dropdown-icon"><i class="bi bi-' + icon + '"></i></div>' +
                    '<div class="cat-dropdown-text">' +
                        '<div class="cat-dropdown-name">' + displayName + '</div>' +
                        (cat.parentName ? '<div class="cat-dropdown-meta">in ' + esc(cat.parentName) + '</div>' : '<div class="cat-dropdown-meta">Main Category</div>') +
                    '</div>' +
                    '<span class="cat-dropdown-count">' + cat.count + '</span>' +
                    '</a>';
            }).join('');

            openDropdown();

            // Click on dropdown item
            catDropdown.querySelectorAll('.cat-dropdown-item').forEach(item => {
                item.addEventListener('mousedown', e => {
                    e.preventDefault();
                    selectCategory({
                        slug:       item.dataset.slug,
                        name:       item.dataset.name,
                        parent:     item.dataset.parent || null,
                        parentName: item.dataset.parentname || null,
                        count:      parseInt(item.dataset.count) || 0
                    });
                });
            });
        }

        function openDropdown() {
            catDropdown.classList.add('open');
            catSearchRow.classList.add('active');
        }

        function closeDropdown() {
            catDropdown.classList.remove('open');
            catSearchRow.classList.remove('active');
            focusedDropdownIdx = -1;
        }

        function selectCategory(cat) {
            activeCatFilter = cat;
            // catSearchInput.value = cat.name;
            // catSearchClear.style.display = 'block';
            closeDropdown();

            // Show chip
            chipName.textContent = cat.name;
            chipSub.textContent  = cat.parentName ? 'in ' + cat.parentName : 'Main Category · ' + cat.count + ' products';
            selectedChip.classList.add('show');

            // Navigate to category page
            const href = cat.parent
                ? '/shop/category/' + encodeURIComponent(cat.parent) + '/' + encodeURIComponent(cat.slug)
                : '/shop/category/' + encodeURIComponent(cat.slug);
            window.location.href = href;
        }

        function clearCatSearch() {
            // catSearchInput.value = '';
            // catSearchClear.style.display = 'none';
            selectedChip.classList.remove('show');
            activeCatFilter = null;
            closeDropdown();
        }

        // Keyboard nav
        // catSearchInput.addEventListener('keydown', e => {
        //     const items = catDropdown.querySelectorAll('.cat-dropdown-item');
        //     if (!items.length) return;
        //     if (e.key === 'ArrowDown') {
        //         e.preventDefault();
        //         focusedDropdownIdx = Math.min(focusedDropdownIdx + 1, items.length - 1);
        //         updateDropdownFocus(items);
        //     } else if (e.key === 'ArrowUp') {
        //         e.preventDefault();
        //         focusedDropdownIdx = Math.max(focusedDropdownIdx - 1, 0);
        //         updateDropdownFocus(items);
        //     } else if (e.key === 'Enter') {
        //         e.preventDefault();
        //         if (focusedDropdownIdx >= 0 && items[focusedDropdownIdx]) {
        //             items[focusedDropdownIdx].dispatchEvent(new MouseEvent('mousedown'));
        //         }
        //     } else if (e.key === 'Escape') {
        //         closeDropdown();
        //         catSearchInput.blur();
        //     }
        // });

        function updateDropdownFocus(items) {
            items.forEach((el, i) => el.classList.toggle('focused', i === focusedDropdownIdx));
            if (items[focusedDropdownIdx]) {
                items[focusedDropdownIdx].scrollIntoView({ block: 'nearest' });
            }
        }

        // catSearchInput.addEventListener('focus', () => {
        //     if (allCategories.length) renderDropdown(catSearchInput.value);
        // });

        // catSearchInput.addEventListener('input', () => {
        //     const val = catSearchInput.value;
        //     catSearchClear.style.display = val ? 'block' : 'none';
        //     renderDropdown(val);
        // });

        // catSearchInput.addEventListener('blur', () => {
        //     setTimeout(closeDropdown, 150);
        // });

        // catSearchClear.addEventListener('click', clearCatSearch);
        chipRemove.addEventListener('click', clearCatSearch);

        // ── BREADCRUMB ───────────────────────────────────────────────
        function renderBreadcrumb() {
            if (SUB_SLUG) {
                const parent = allCategories.find(c => c.slug === SLUG);
                const pName  = parent ? parent.name : cap(SLUG);
                breadcrumbEl.innerHTML =
                    '<a href="/shop/category/' + esc(SLUG) + '" style="color:var(--text-secondary);text-decoration:none;">' + esc(pName) + '</a>' +
                    '<span class="sep"> / </span>' +
                    '<span class="current">' + esc(categoryInfo.name) + '</span>';
            } else {
                breadcrumbEl.innerHTML = '<span class="current">' + esc(categoryInfo.name) + '</span>';
            }
        }

        // ── SUB-CAT TABS ─────────────────────────────────────────────
        function renderSubcatTabs() {
            const subs = allCategories.filter(c => c.parent && c.parent == categoryInfo.id && c.count > 0);
            if (!subs.length) return;
            subcatStrip.style.display = 'block';
            let html = '<a href="/shop/category/' + esc(SLUG) + '" class="subcat-tab' + (!SUB_SLUG ? ' active' : '') + '">All ' + esc(categoryInfo.name) + '</a>';
            subs.forEach(s => {
                html += '<a href="/shop/category/' + esc(SLUG) + '/' + esc(s.slug) + '" class="subcat-tab' + (s.slug === SUB_SLUG ? ' active' : '') + '">' + esc(s.name) + '</a>';
            });
            subcatTabs.innerHTML = html;
            $('subcat-right').addEventListener('click', () => { subcatTabs.scrollBy({ left: 180, behavior: 'smooth' }); });
        }

        // ── CATEGORIES SIDEBAR ───────────────────────────────────────
        function renderCategoriesSidebar() {
            let html = '';
            allCategories.forEach(cat => {
                const children = (cat.children || []).filter(c => c.count > 0);
                const active   = cat.slug === SLUG;
                html += '<div>';
                html += '<a href="/shop/category/' + esc(cat.slug) + '" class="sidebar-cat-link' + (active ? ' active' : '') + '">';
                html += '<span>' + esc(cat.name) + '</span><span class="cat-count">' + cat.count + '</span></a>';
                if (active && children.length) {
                    children.forEach(ch => {
                        html += '<a href="/shop/category/' + esc(cat.slug) + '/' + esc(ch.slug) + '" class="sidebar-cat-child' + (ch.slug === SUB_SLUG ? ' active' : '') + '">';
                        html += '<span>' + esc(ch.name) + '</span><span class="cat-count">' + ch.count + '</span></a>';
                    });
                }
                html += '</div>';
            });
            categoriesMenu.innerHTML = html || '<p style="padding:14px;font-size:13px;color:var(--text-muted);">No categories.</p>';
        }


        function buildAttrFilters() {
            const attrs = {};
            allProducts.forEach(p => {
                (p.product_attributes || []).forEach(attr => {
                    if (!attrs[attr.name]) attrs[attr.name] = new Map();
                    attr.values.forEach(v => attrs[attr.name].set(v, (attrs[attr.name].get(v) || 0) + 1));
                });
            });

            let html = '';
            Object.entries(attrs).forEach(([name, valMap]) => {
                const sorted  = [...valMap.entries()].sort((a,b) => a[0].localeCompare(b[0]));
                const visible = sorted.slice(0, 5);
                const hidden  = sorted.slice(5);
                const fid     = 'fg-' + slugify(name);

                html += '<div class="filter-group">';
                html += '<div class="filter-group-header" data-target="' + fid + '">';
                html += '<span class="filter-group-title">' + esc(name) + '</span>';
                html += '<i class="bi bi-dash toggle-icon"></i></div>';
                html += '<div class="filter-group-body" id="' + fid + '">';
                visible.forEach(([val, cnt]) => { html += optionHTML(name, val, cnt); });
                if (hidden.length) {
                    html += '<div class="hidden-opts" style="display:none;">';
                    hidden.forEach(([val, cnt]) => { html += optionHTML(name, val, cnt); });
                    html += '</div>';
                    html += '<button class="show-more-btn" onclick="toggleMore(this)">Show more (' + hidden.length + ')</button>';
                }
                html += '</div></div>';
            });

            attrFilters.innerHTML = html;
            attrFilters.querySelectorAll('.filter-checkbox').forEach(cb => cb.addEventListener('change', applyFilters));

            document.querySelectorAll('.filter-group-header').forEach(h => {
                h.addEventListener('click', () => {
                    const body = document.getElementById(h.dataset.target);
                    const icon = h.querySelector('.toggle-icon');
                    if (!body) return;
                    body.classList.toggle('collapsed');
                    icon.className = body.classList.contains('collapsed')
                        ? icon.className.replace('bi-dash','bi-plus')
                        : icon.className.replace('bi-plus','bi-dash');
                });
            });
        }

        function optionHTML(name, val, cnt) {
            return '<div class="filter-option">' +
                '<input type="checkbox" class="filter-checkbox" data-attribute="' + esc(name) + '" value="' + esc(val) + '">' +
                '<label><span>' + esc(val) + '</span><span class="fcount">(' + cnt + ')</span></label>' +
                '</div>';
        }

        window.toggleMore = function(btn) {
            const wrap = btn.previousElementSibling;
            const open = wrap.style.display !== 'none';
            wrap.style.display = open ? 'none' : 'block';
            btn.textContent = open ? 'Show more (' + wrap.querySelectorAll('.filter-option').length + ')' : 'Show less';
        };

        function renderProducts(products) {

            resultsCount.innerHTML =
                'Showing <strong>' + products.length + '</strong> products';

            if (!products.length) {

                paginationWrap.innerHTML = '';

                productsContainer.innerHTML =
                    '<div class="no-products">' +
                    '<i class="bi bi-box-seam"></i>' +
                    '<p>No products match your filters.</p>' +
                    '</div>';

                return;
            }

            const totalPages = Math.ceil(products.length / perPage);

            if (currentPage > totalPages) {
                currentPage = 1;
            }

            const start = (currentPage - 1) * perPage;
            const end   = start + perPage;

            const paginatedProducts = products.slice(start, end);

            productsContainer.innerHTML =
                paginatedProducts.map(cardHTML).join('');

            renderPagination(totalPages);
        }
        function renderPagination(totalPages){

            if(totalPages <= 1){
                paginationWrap.innerHTML = '';
                return;
            }

            let html = '';

            html += `
                <button class="page-btn"
                    ${currentPage === 1 ? 'disabled' : ''}
                    onclick="changePage(${currentPage - 1})">
                    Prev
                </button>
            `;

            for(let i = 1; i <= totalPages; i++){

                if(
                    i === 1 ||
                    i === totalPages ||
                    (i >= currentPage - 1 && i <= currentPage + 1)
                ){

                    html += `
                        <button class="page-btn ${i === currentPage ? 'active' : ''}"
                            onclick="changePage(${i})">
                            ${i}
                        </button>
                    `;
                }
            }

            html += `
                <button class="page-btn"
                    ${currentPage === totalPages ? 'disabled' : ''}
                    onclick="changePage(${currentPage + 1})">
                    Next
                </button>
            `;

            paginationWrap.innerHTML = html;
        } 
        function cardHTML(p) {
                    const link = (p.permalink || '').replace('https://www.recyclepro.co.uk/rp-dashboard/', 'https://www.recyclepro.co.uk/shop/');
                    const price = parseFloat(p.price);
                    const priceStr = price ? '£' + price.toFixed(2) : 'POA';

                    const condAttr = (p.product_attributes || []).find(a => /condition|grade/i.test(a.name));
                    const condVal  = condAttr && condAttr.values && condAttr.values[0] ? condAttr.values[0] : '';


                    return `
                        <div class="product-card">
                            <div class="top-accent-bar"></div>
                            
                            <div class="product-img-wrap">
                                <a href="${esc(link)}">
                                    <img src="${esc(p.image || '')}" alt="${esc(p.name)}" loading="lazy" onerror="this.src='/images/placeholder.png'">
                                </a>
                            </div>
                            
                            <div class="product-body">
                                <h3 class="product-name">${esc(p.name)}</h3>
                                
                                ${condVal ? `
                                    <div class="product-condition-wrap">
                                        <span class="product-condition">${esc(condVal)}</span>
                                        <span class="info-icon">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                        </span>
                                    </div>
                                ` : ''}

                                <div class="product-condition-wrap" style="text-align:center;margin-bottom:10px; font-size:12px; color:var(--text-muted);   ">
                                    <span class="product-condition">Condition - Pristine </span>
                                    <span class="info-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                    </span>
                                </div>
                                
                                <div class="product-price">${priceStr}</div>
                                
                    
                                
                                <a href="${esc(link)}" class="btn-view" style="margin-top: 20px;">View product</a>
                                
                        
                            </div>
                        </div>
                    `;
                }
                    
        function applyFilters() {
            const selFilters = {};
            document.querySelectorAll('.filter-checkbox:checked').forEach(cb => {
                if (!selFilters[cb.dataset.attribute]) selFilters[cb.dataset.attribute] = [];
                selFilters[cb.dataset.attribute].push(cb.value);
            });
            const prMin    = parseFloat(priceMinEl.value) || 0;
            const prMax    = parseFloat(priceMaxEl.value) || Infinity;
            const hasAttr  = Object.keys(selFilters).length > 0;
            const hasPrice = priceMinEl.value !== '' || priceMaxEl.value !== '';

            let filtered = allProducts.filter(p => {
                if (hasPrice) {
                    const pr = parseFloat(p.price) || 0;
                    if (pr < prMin || pr > prMax) return false;
                }
                if (hasAttr) {
                    return Object.keys(selFilters).every(attr => {
                        const pa = (p.product_attributes || []).find(a => a.name === attr);
                        if (!pa) return false;
                        return selFilters[attr].some(v => pa.values.includes(v));
                    });
                }
                return true;
            });

            if (currentSort) {
                filtered = filtered.slice().sort((a,b) => {
                    if (currentSort === 'price-asc')  return (parseFloat(a.price)||0)-(parseFloat(b.price)||0);
                    if (currentSort === 'price-desc') return (parseFloat(b.price)||0)-(parseFloat(a.price)||0);
                    if (currentSort === 'name-asc')   return a.name.localeCompare(b.name);
                    if (currentSort === 'name-desc')  return b.name.localeCompare(a.name);
                    return 0;
                });
            }
            renderProducts(filtered);
        }

        function getMinMax() {
            let min=Infinity, max=-Infinity;
            allProducts.forEach(p => { const pr=parseFloat(p.price)||0; if(pr<min)min=pr; if(pr>max)max=pr; });
            return { min:min===Infinity?0:min, max:max===-Infinity?0:max };
        }


        function showError(msg) {
            productsContainer.innerHTML = '<div class="no-products"><i class="bi bi-exclamation-triangle" style="color:var(--red);"></i><p style="color:var(--red);">' + esc(msg) + '</p></div>';
            resultsCount.textContent = '';
        }


        function esc(s) { return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }
        function cap(s) { return s.charAt(0).toUpperCase()+s.slice(1); }
        function slugify(s) { return s.toLowerCase().replace(/[^a-z0-9]/g,'-'); }
        function escRegex(s) { return s.replace(/[.*+?^${}()|[\]\\]/g,'\\$&'); }

        $('apply-price').addEventListener('click', applyFilters);
        priceMinEl.addEventListener('keypress', e => { if(e.key==='Enter') applyFilters(); });
        priceMaxEl.addEventListener('keypress', e => { if(e.key==='Enter') applyFilters(); });

        sortMain.addEventListener('change', () => { currentSort = sortMain.value; applyFilters(); });

        $('clear-all-btn').addEventListener('click', () => {
            document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
            priceMinEl.value = '';
            priceMaxEl.value = '';
            currentSort = '';
            sortMain.value = '';
            clearCatSearch();
            applyFilters();
        });

        window.showToast = function(msg, type) {
            const t = document.createElement('div');
            t.style.cssText = 'position:fixed;top:20px;right:20px;background:' + (type==='success'?'#16a34a':'#2563eb') + ';color:#fff;padding:12px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.2);z-index:9999;font-family:DM Sans,sans-serif;font-weight:500;font-size:14px;';
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3000);
        };
            const mobileFilterBtn = $('mobile-filter-btn');
        const sidebarOverlay = $('sidebar-overlay');
        const sidebar = document.querySelector('.shop-sidebar');

        mobileFilterBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.classList.add('sidebar-open');
        });

        sidebarOverlay.addEventListener('click', closeSidebar);

        function closeSidebar(){
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
window.changePage = function(page){

    currentPage = page;

    applyFilters();

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
        init();
    })();


    </script>

    <?php include 'includes/footer.php'; ?>
  
</body>
</html>
