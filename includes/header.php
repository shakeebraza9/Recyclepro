<?php
/**
 * IMPORTANT:
 * - Save this file as UTF-8 WITHOUT BOM
 * - Do not add spaces/new lines before <?php
 * - Do not close PHP tag at end of pure PHP files
 */

//ob_start();

//if (session_status() === PHP_SESSION_NONE) {
  //  session_start();
//}
$config = require_once __DIR__ . '/config.php';
$pageTitle = $pageTitle ?? 'Recycle Pro';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
<link rel="stylesheet" href="/shop/css/main.css?v1">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script>
const baseAPI = "<?= $config['API_URL'] ?>";
const BASE_URL = "<?= $config['BASE_URL'] ?>";
const headerAPI = `${baseAPI}wp-json/wp/v2/header`;

async function loadHeaderData() {
    try {
        const response = await fetch(headerAPI);

        if (!response.ok) {
            throw new Error('Failed to load header data');
        }

        const header = await response.json();

        renderHeader(header.header || {});
    } catch (err) {
        console.error('Header API Error:', err);
    }
}

function renderHeader(data) {

    // Top Bar
    const bar = document.querySelector('.info-bar');

    if (bar) {
        bar.innerHTML = '';

        Object.values(data.header_top_bar || {}).forEach(v => {
            const span = document.createElement('span');
            span.innerHTML = v || '';
            bar.appendChild(span);
        });
    }

    // Menu
    const menu = document.getElementById('menu');

    if (menu) {
        menu.innerHTML = '';

        let menuItemCounter = 0;

        const createMenuItem = (item, depth = 0) => {
            const li = document.createElement('li');
            const hasSub = Array.isArray(item.sub) && item.sub.length > 0;
            const itemId = menuItemCounter++;
            const submenuId = `submenu-${itemId}`;

            if (depth === 0) {
                li.className = 'nav-item';
            } else {
                li.className = '';
            }

            if (hasSub) {
                li.classList.add('dropdown');
            }

            const linkClass = depth === 0 
                ? `nav-link${hasSub ? ' dropdown-toggle' : ''}`
                : hasSub 
                    ? 'dropdown-item dropdown-toggle'
                    : 'dropdown-item';

            let cleanUrl = (item.url || '#').replace(/^https?:\/\/[^\/]+\/shop\/?/i, '');

            if (cleanUrl.startsWith('/shop/')) {
                cleanUrl = cleanUrl.replace(/^\/shop\//i, '');
            } else if (cleanUrl.startsWith('shop/')) {
                cleanUrl = cleanUrl.replace(/^shop\//i, '');
            }

            if (cleanUrl.startsWith('/')) {
                cleanUrl = cleanUrl.substring(1);
            }

            const finalUrl = `${BASE_URL}${cleanUrl}`;

            let itemHtml = `
                <a class="${linkClass}" href="${finalUrl}">
                    ${item.name}
                </a>
            `;

            if (hasSub) {
                itemHtml += `
                    <button
                        class="submenu-toggle"
                        type="button"
                        aria-expanded="false"
                        aria-controls="${submenuId}"
                        aria-label="Expand ${item.name}"
                    >
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <ul class="dropdown-menu" id="${submenuId}">
                `;

                item.sub.forEach(subItem => {
                    const subLi = createMenuItem(subItem, depth + 1);
                    itemHtml += subLi.outerHTML;
                });

                itemHtml += '</ul>';
            }

            li.innerHTML = itemHtml;
            return li;
        };

        // 1. Pehle API wale saare menu items add karo
        (data.menu || []).forEach((m) => {
            menu.appendChild(createMenuItem(m, 0));
        });

        // 2. MOBILE ENHANCEMENT: Shop/Sell aur Account Buttons dono ko aakhir me add karo
        const mobileSwitchLi = document.createElement('li');
        mobileSwitchLi.className = 'nav-item d-md-none mt-3 pt-3 border-top-mobile'; 
        mobileSwitchLi.innerHTML = `
            <div class="mobile-header-switch-links d-flex gap-2 px-3 mb-3">
                <a class="v-nav-link btn-shop-mobile w-50 text-center py-2" href="/shop/">
                    <span>Shop</span>
                </a>
                <a class="v-nav-link btn-sell-mobile w-50 text-center py-2" href="/">
                    <span>Sell</span>
                </a>
            </div>

            <div id="mobileGuestActions" class="px-3 d-flex flex-column gap-2">
                <button type="button" class="btn btn-login-mobile w-100 py-2" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="login">
                    Login
                </button>
                <button type="button" class="btn btn-account-mobile w-100 py-2" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="open-account">
                    <i class="bi bi-person"></i> Open Account
                </button>
            </div>

            <div id="mobileUserActions" class="px-3 d-none flex-column gap-2 text-center">
                <span id="mobileWelcomeName" class="fw-semibold text-dark mb-1 d-block"></span>
                <button type="button" class="btn btn-sm btn-danger w-100 py-2" id="mobileLogoutButton">
                    Logout
                </button>
            </div>
        `;
        menu.appendChild(mobileSwitchLi);

        // Mobile logout trigger attach karein
        setTimeout(() => {
            document.getElementById('mobileLogoutButton')?.addEventListener('click', clearAccount);
        }, 150);
    }
}
document.addEventListener('DOMContentLoaded', loadHeaderData);

function setupMobileMenu() {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const menuOverlay = document.getElementById('mobileMenuOverlay');
    const menuNav = document.getElementById('mainMenuNav');
    const menu = document.getElementById('menu');

    if (!menuToggle || !menuOverlay || !menuNav || !menu) {
        return;
    }

    const icon = menuToggle.querySelector('i');
    const setMenuState = (isOpen) => {
        menuNav.classList.toggle('menu-open', isOpen);
        document.body.classList.toggle('mobile-menu-open', isOpen);
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        menuToggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');

        if (icon) {
            icon.className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
        }
    };

    const isDrawerLayout = () => window.matchMedia('(max-width: 991.98px)').matches;
    const setSubmenuState = (item, isOpen) => {
        const toggle = item.querySelector(':scope > .submenu-toggle');
        const toggleIcon = toggle?.querySelector('i');

        item.classList.toggle('submenu-open', isOpen);

        if (toggle) {
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggle.setAttribute('aria-label', `${isOpen ? 'Collapse' : 'Expand'} ${item.querySelector(':scope > .nav-link')?.textContent.trim() || 'submenu'}`);
        }

        if (toggleIcon) {
            toggleIcon.className = isOpen ? 'bi bi-dash-lg' : 'bi bi-plus-lg';
        }
    };

    const closeSubmenus = () => {
        menu.querySelectorAll('.dropdown.submenu-open').forEach((item) => {
            setSubmenuState(item, false);
        });
    };

    const setDesktopToggleState = () => {
        const isExpanded = !menuNav.classList.contains('desktop-menu-compact');

        menuToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
        menuToggle.setAttribute('aria-label', isExpanded ? 'Collapse menu' : 'Open menu');

        if (icon) {
            icon.className = isExpanded ? 'bi bi-x-lg' : 'bi bi-list';
        }
    };

    const syncToggleState = () => {
        if (isDrawerLayout()) {
            setMenuState(menuNav.classList.contains('menu-open'));
        } else {
            setDesktopToggleState();
        }
    };

    menuToggle.addEventListener('click', () => {
        if (isDrawerLayout()) {
            const willOpen = !menuNav.classList.contains('menu-open');
            setMenuState(willOpen);

            if (!willOpen) {
                closeSubmenus();
            }
        } else {
            menuNav.classList.toggle('desktop-menu-compact');
            menuToggle.classList.toggle('is-compact');
            setDesktopToggleState();
        }
    });

    menuOverlay.addEventListener('click', () => {
        setMenuState(false);
        closeSubmenus();
    });

    menu.addEventListener('click', (event) => {
        const submenuToggle = event.target.closest('.submenu-toggle');
        const parentItem = event.target.closest('.dropdown');

        if (isDrawerLayout() && submenuToggle && parentItem) {
            event.preventDefault();
            setSubmenuState(parentItem, !parentItem.classList.contains('submenu-open'));
            return;
        }

        const link = event.target.closest('a');

        if (isDrawerLayout() && link?.classList.contains('dropdown-toggle') && parentItem) {
            event.preventDefault();
            setSubmenuState(parentItem, !parentItem.classList.contains('submenu-open'));
            return;
        }

        if (link) {
            setMenuState(false);
            closeSubmenus();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMenuState(false);
            closeSubmenus();
        }
    });

    window.addEventListener('resize', () => {
        if (!isDrawerLayout()) {
            setMenuState(false);
            closeSubmenus();
        }

        syncToggleState();
    });

    syncToggleState();
}

document.addEventListener('DOMContentLoaded', setupMobileMenu);

class CartManager {

    constructor() {
        this.items = [];
        this.count = 0;
        this.total = 0;
    }

    request(action, payload = {}) {

        return fetch('/shop/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                action,
                ...payload
            })
        })
        .then(response => response.json())
        .then(data => {

            this.items = data.items || [];
            this.count = Number(data.count || 0);
            this.total = Number(data.total || 0);

            this.updateCartDisplay();

            document.dispatchEvent(
                new CustomEvent('cart:updated', {
                    detail: data
                })
            );

            return data;
        });
    }

    load() {
        return this.request('get');
    }

    addItem(product) {

        return this.request('add', {

            product_id:
                product.product_id ||
                product.id ||
                product.ID ||
                '',

            name:
                product.name ||
                product.title ||
                '',

            price:
                product.price ||
                0,

            image:
                product.image ||
                '',

            permalink:
                product.permalink ||
                product.url ||
                ''
        });
    }

    updateQuantity(index, quantity) {

        return this.request('update', {
            index,
            qty: quantity
        });
    }

    removeItem(index) {

        return this.request('remove', {
            index
        });
    }

    clearCart() {
        return this.request('clear');
    }

    getItems() {
        return this.items;
    }

    getTotalItems() {
        return this.count;
    }

    getTotalPrice() {
        return this.total;
    }

    updateCartDisplay() {

        const cartCountElements =
            document.querySelectorAll('.cart-count');

        cartCountElements.forEach((element) => {

            element.textContent = this.count;

            element.style.display =
                this.count > 0
                    ? 'inline-block'
                    : 'none';
        });
    }
}

const cartManager = new CartManager();

window.cartManager = cartManager;

const accountAPIBase = 'https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2';

function getStoredAccount() {
    try {
        return JSON.parse(localStorage.getItem('recycleproAccount') || 'null');
    } catch (error) {
        return null;
    }
}

function storeAccount(account) {
    localStorage.setItem('recycleproAccount', JSON.stringify(account));
    updateAccountHeader();
}

function clearAccount() {
    localStorage.removeItem('recycleproAccount');
    updateAccountHeader();
}

function getAccountDisplayName(data, fallbackEmail = '') {
    return data?.name || data?.user?.name || data?.display_name || fallbackEmail.split('@')[0] || 'Customer';
}

function updateAccountHeader() {
    const account = getStoredAccount();
    const guestActions = document.getElementById('guestAccountActions');
    const userActions = document.getElementById('userAccountActions');
    const welcomeName = document.getElementById('welcomeName');

    if (!guestActions || !userActions || !welcomeName) {
        return;
    }

    if (account) {
        guestActions.classList.add('d-none');
        userActions.classList.remove('d-none');
        welcomeName.textContent = `Welcome ${account.name}`;
    } else {
        guestActions.classList.remove('d-none');
        userActions.classList.add('d-none');
        welcomeName.textContent = '';
    }
}

async function submitAccountForm(endpoint, payload) {
    const response = await fetch(`${accountAPIBase}/${endpoint}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok || data.success === false) {
        throw new Error(data.message || 'Account request failed. Please try again.');
    }

    return data;
}

document.addEventListener('DOMContentLoaded', () => {

    cartManager.load()
        .catch(err => {
            console.error('Cart API Error:', err);
        });

    updateAccountHeader();

    const loginForm = document.getElementById('loginForm');
    const openAccountForm = document.getElementById('openAccountForm');
    const logoutButton = document.getElementById('logoutButton');

    logoutButton?.addEventListener('click', clearAccount);

    document.querySelectorAll('[data-account-tab]').forEach((button) => {
        button.addEventListener('click', () => {
            const tabId = button.dataset.accountTab === 'open-account' ? 'open-account-tab' : 'login-tab';
            const tabButton = document.getElementById(tabId);

            if (tabButton && window.bootstrap) {
                bootstrap.Tab.getOrCreateInstance(tabButton).show();
            }
        });
    });

    loginForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const status = document.getElementById('loginStatus');
        const submitButton = loginForm.querySelector('button[type="submit"]');
        const email = loginForm.email.value.trim();
        const password = loginForm.password.value;

        status.textContent = '';
        submitButton.disabled = true;

        try {
            const data = await submitAccountForm('login', { email, password });
            storeAccount({
                name: getAccountDisplayName(data, email),
                email,
                token: data.token || data.jwt || ''
            });
            status.textContent = 'Login successful.';
            status.className = 'small text-success';
            bootstrap.Modal.getOrCreateInstance(document.getElementById('accountModal')).hide();
            loginForm.reset();
        } catch (error) {
            status.textContent = error.message;
            status.className = 'small text-danger';
        } finally {
            submitButton.disabled = false;
        }
    });

    openAccountForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const status = document.getElementById('openAccountStatus');
        const submitButton = openAccountForm.querySelector('button[type="submit"]');
        const name = openAccountForm.name.value.trim();
        const email = openAccountForm.email.value.trim();
        const password = openAccountForm.password.value;

        status.textContent = '';
        submitButton.disabled = true;

        try {
            const data = await submitAccountForm('open-account', { name, email, password });
            storeAccount({
                name: data?.name || data?.user?.name || data?.display_name || name,
                email,
                token: data.token || data.jwt || ''
            });
            status.textContent = 'Account created successfully.';
            status.className = 'small text-success';
            bootstrap.Modal.getOrCreateInstance(document.getElementById('accountModal')).hide();
            openAccountForm.reset();
        } catch (error) {
            status.textContent = error.message;
            status.className = 'small text-danger';
        } finally {
            submitButton.disabled = false;
        }
    });
});




document.addEventListener("DOMContentLoaded", function () {
    updateHeaderWishlistCount();
});


document.addEventListener("DOMContentLoaded", function () {
    const searchToggleBtn = document.getElementById('mobileSearchToggleBtn');
    const searchWrapper = document.getElementById('mobileSearchWrapper');

    if (searchToggleBtn && searchWrapper) {
        searchToggleBtn.addEventListener('click', function (e) {
            e.stopPropagation(); 
            
  
            searchWrapper.classList.toggle('d-none');
            searchWrapper.classList.toggle('search-animate-open');

            
            const icon = searchToggleBtn.querySelector('i');
            if (searchWrapper.classList.contains('d-none')) {
                icon.className = 'bi bi-search';
            } else {
                icon.className = 'bi bi-x-lg';
        
                document.getElementById('header-search-input')?.focus();
            }
        });

  
        document.addEventListener('click', function (e) {
            if (window.innerWidth < 768 && !searchWrapper.classList.contains('d-none')) {
                if (!searchWrapper.contains(e.target) && !searchToggleBtn.contains(e.target)) {
                    searchWrapper.classList.add('d-none');
                    searchWrapper.classList.remove('search-animate-open');
                    searchToggleBtn.querySelector('i').className = 'bi bi-search';
                }
            }
        });
    }
});
</script>
</head>

<body>

<header class="site-header bg-dark text-white">
    <div class="container">

        <div class="header-layout">

            <!-- Logo -->
            <div class="header-brand">
                <a href="/shop/" class="header-logo-link">
                    <img
                        class="logo img-fluid"
                        src="/shop/img/rplogo.png"
                        alt="Recycle Pro Logo"
                    >
                </a>
                <button
                    class="mobile-menu-toggle"
                    type="button"
                    id="mobileMenuToggle"
                    aria-controls="menu"
                    aria-expanded="false"
                    aria-label="Open menu"
                >
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <!-- Navigation -->
            <div class="header-switch-links">

                <a class="v-nav-link" href="/shop/">
                    <span>Shop</span>
                </a>

                <a class="v-nav-link" href="/">
                    <span>Sell</span>
                </a>

            </div>


    <div class="header-search position-relative my-2 d-none d-md-block" id="mobileSearchWrapper">
    <div class="input-group header-search-group">
        <input
            type="text"
            id="header-search-input"
            class="form-control"
            placeholder="Search products..."
            autocomplete="off"
        >
        <button class="btn bg-light text-muted" id="header-search-btn" type="button">
            <i class="bi bi-search"></i>
        </button>
    </div>
    <div id="search-results-dropdown" class="search-dropdown-menu d-none"></div>
</div>

<div class="header-actions d-flex align-items-center gap-1">

    <button type="button" class="btn text-white p-2 d-inline-flex d-md-none align-items-center justify-content-center" 
            id="mobileSearchToggleBtn" title="Toggle Search" style="width: 40px; height: 40px; font-size: 1.2rem;">
        <i class="bi bi-search"></i>
    </button>

    <a href="/shop/wishlist" 
        class="btn text-white position-relative p-2 d-inline-flex align-items-center justify-content-center" 
        title="View Wishlist"
        style="width: 40px; height: 40px; font-size: 1.2rem;">
        <i class="bi bi-heart"></i>
        <span id="globalWishlistCount" 
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill" 
            style="background-color: #13564f; color: white; font-size: 0.65rem; padding: 0.25em 0.45em; min-width: 18px;">
            0
        </span>
    </a>

    <a href="/shop/cart-view" 
        class="btn text-white position-relative p-2 d-inline-flex align-items-center justify-content-center" 
        title="View Cart"
        style="width: 40px; height: 40px; font-size: 1.2rem;">
        <i class="bi bi-cart3"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-count" 
            style="background-color: #13564f; color: white; font-size: 0.65rem; padding: 0.25em 0.45em; min-width: 18px;">
            0
        </span>
    </a>

                <span id="guestAccountActions" class="account-actions">
                    <button type="button" class="btn btn-login text-white" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="login">
                        Login
                    </button>

                    <button type="button" class="btn btn-account text-white" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="open-account">
                        <i class="bi bi-person"></i>
                        Open Account
                    </button>
                </span>

                <span id="userAccountActions" class="d-none account-welcome">
                    <span id="welcomeName" class="me-2 fw-semibold"></span>
                    <button type="button" class="btn btn-sm btn-outline-light" id="logoutButton">Logout</button>
                </span>

            </div>

        </div>
    </div>
</header>

<!-- Main Menu -->
<nav class="navbar navbar-expand-lg bg-light main-menu-navbar" id="mainMenuNav">

    <div class="container">

        

        <ul class="navbar-nav mx-auto" id="menu">
       
        </ul>

    </div>

</nav>

<div class="mobile-menu-overlay" id="mobileMenuOverlay">
    
</div>

<!-- Top Info Bar -->
<!--<div class="bg-light border-bottom py-2 small text-secondary">

    <div class="container">

        <div class="info-bar d-flex gap-3 flex-wrap"></div>

    </div>

</div> -->

<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content account-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="accountModalLabel">Your Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills account-tabs mb-4" id="accountTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#loginPane" type="button" role="tab" aria-controls="loginPane" aria-selected="true">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="open-account-tab" data-bs-toggle="pill" data-bs-target="#openAccountPane" type="button" role="tab" aria-controls="openAccountPane" aria-selected="false">Open Account</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="loginPane" role="tabpanel" aria-labelledby="login-tab">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@emial.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="*****" required>
                            </div>
                            <p id="loginStatus" class="small mb-3"></p>
                            <button type="submit" class="btn btn-dark w-100">Login</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="openAccountPane" role="tabpanel" aria-labelledby="open-account-tab">
                        <form id="openAccountForm">
                            <div class="mb-3">
                                <label for="accountName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="accountName" name="name" placeholder="Kamran" required>
                            </div>
                            <div class="mb-3">
                                <label for="accountEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="accountEmail" name="email" placeholder="kamran@test.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="accountPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="accountPassword" name="password" placeholder="123456" required>
                            </div>
                            <p id="openAccountStatus" class="small mb-3"></p>
                            <button type="submit" class="btn btn-dark w-100">Open Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
