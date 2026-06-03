<?php

$config = require_once dirname(__DIR__) . '/config.php';
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

    const mobileGuestActions = document.getElementById('mobileGuestActions');
    const mobileUserActions = document.getElementById('mobileUserActions');
    const mobileWelcomeName = document.getElementById('mobileWelcomeName');

    if (account) {
        if (guestActions) guestActions.classList.add('d-none');
        if (userActions) userActions.classList.remove('d-none');
        if (welcomeName) welcomeName.textContent = `Welcome ${account.name}`;

        if (mobileGuestActions) mobileGuestActions.classList.add('d-none');
        if (mobileUserActions) {
            mobileUserActions.classList.remove('d-none');
            mobileUserActions.classList.add('d-flex');
        }
        if (mobileWelcomeName) mobileWelcomeName.textContent = `Hi, ${account.name}`;
    } else {
        if (guestActions) guestActions.classList.remove('d-none');
        if (userActions) userActions.classList.add('d-none');
        if (welcomeName) welcomeName.textContent = '';

        if (mobileGuestActions) mobileGuestActions.classList.remove('d-none');
        if (mobileUserActions) {
            mobileUserActions.classList.add('d-none');
            mobileUserActions.classList.remove('d-flex');
        }
        if (mobileWelcomeName) mobileWelcomeName.textContent = 'Account';
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
    const bar = document.querySelector('.info-bar');
    if (bar) {
        bar.innerHTML = '';
        Object.values(data.header_top_bar || {}).forEach(v => {
            const span = document.createElement('span');
            span.innerHTML = v || '';
            bar.appendChild(span);
        });
    }

    const menu = document.getElementById('menu');
    if (menu) {
        menu.innerHTML = '';
        let menuItemCounter = 0;

        const createMenuItem = (item, depth = 0) => {
            const li = document.createElement('li');
            const hasSub = Array.isArray(item.sub) && item.sub.length > 0;
            const itemId = menuItemCounter++;
            const submenuId = `submenu-${itemId}`;
            const cleanClassName = item.name ? item.name.toLowerCase().replace(/\s+/g, '-') : 'item';

            li.className = depth === 0 ? 'nav-item' : '';
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
                    <ul class="dropdown-menu ${cleanClassName}" id="${submenuId}">
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

        (data.menu || []).forEach((m) => {
            menu.appendChild(createMenuItem(m, 0));
        });

        const mobileSwitchLi = document.createElement('li');
        mobileSwitchLi.className = 'nav-item d-md-none mt-3 pt-3 border-top-mobile'; 
        mobileSwitchLi.innerHTML = `
            <div id="mobileGuestActions" class="px-3 d-flex flex-column gap-2">
                <button type="button" class="btn btn-login-mobile w-100 py-2" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="login">
                    Login
                </button>
                <button type="button" class="btn btn-account-mobile w-100 py-2" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="open-account">
                    <i class="bi bi-person"></i> Create Account
                </button>
            </div>

            <div id="mobileUserActions" class="px-3 d-none flex-column gap-2">
                <div class="text-center mb-2">
                    <i class="bi bi-person-circle fs-3 text-secondary mb-1 d-block"></i>
                    <span id="mobileWelcomeName" class="fw-bold text-dark fs-6">Account</span>
                </div>
                
                <a class="btn btn-light w-100 py-2 text-start d-flex align-items-center gap-2" href="${BASE_URL}user/settings/" style="border: 1px solid #e2e8f0; font-weight: 500;">
                    <i class="bi bi-gear-fill text-secondary"></i>
                    <span>Your Account</span>
                </a>
                

                
                <button type="button" class="btn btn-danger w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" id="mobileLogoutButton">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        `;
        menu.appendChild(mobileSwitchLi);

        setTimeout(() => {
            document.getElementById('mobileLogoutButton')?.addEventListener('click', clearAccount);
            updateAccountHeader();
        }, 150);
    }
}

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
            body: new URLSearchParams({ action, ...payload })
        })
        .then(response => response.json())
        .then(data => {
            this.items = data.items || [];
            this.count = Number(data.count || 0);
            this.total = Number(data.total || 0);

            this.updateCartDisplay();
            document.dispatchEvent(new CustomEvent('cart:updated', { detail: data }));
            return data;
        });
    }

    load() { return this.request('get'); }

    addItem(product) {
        return this.request('add', {
            product_id: product.product_id || product.id || product.ID || '',
            name: product.name || product.title || '',
            price: product.price || 0,
            image: product.image || '',
            permalink: product.permalink || product.url || ''
        });
    }

    updateQuantity(index, quantity) { return this.request('update', { index, qty: quantity }); }
    removeItem(index) { return this.request('remove', { index }); }
    clearCart() { return this.request('clear'); }
    getItems() { return this.items; }
    getTotalItems() { return this.count; }
    getTotalPrice() { return this.total; }

    updateCartDisplay() {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach((element) => {
            element.textContent = this.count;
            element.style.display = this.count > 0 ? 'inline-block' : 'none';
        });
    }
}

const cartManager = new CartManager();
window.cartManager = cartManager;

function setupMobileMenu() {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const menuOverlay = document.getElementById('mobileMenuOverlay');
    const menuNav = document.getElementById('mainMenuNav');
    const menu = document.getElementById('menu');

    if (!menuToggle || !menuOverlay || !menuNav || !menu) return;

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
            if (!willOpen) closeSubmenus();
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

document.addEventListener('DOMContentLoaded', () => {
    loadHeaderData();
    setupMobileMenu();
    updateAccountHeader();
    
    cartManager.load().catch(err => {
        console.error('Cart API Error:', err);
    });

    const loginForm = document.getElementById('loginForm');
    const openAccountForm = document.getElementById('openAccountForm');
    const logoutButton = document.getElementById('logoutButton');

    // --- REMEMBER ME: PAGE LOAD DETECT ---
    const emailInput = document.getElementById('loginEmail');
    const rememberCheckbox = document.getElementById('loginRemember');

    if (emailInput && rememberCheckbox) {
        const savedEmail = localStorage.getItem('remembered_email');
        if (savedEmail) {
            emailInput.value = savedEmail;
            rememberCheckbox.checked = true;
        }
    }

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

    // --- LOGIN FORM SUBMIT LOGIC ---
    loginForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const status = document.getElementById('loginStatus');
        const submitButton = loginForm.querySelector('button[type="submit"]');
        const email = loginForm.email.value.trim();
        const password = loginForm.password.value;
        const isRememberMeChecked = rememberCheckbox ? rememberCheckbox.checked : false;

        if (status) status.textContent = '';
        if (submitButton) submitButton.disabled = true;

        try {
            const data = await submitAccountForm('login', { email, password });
            console.log(data.token);
            console.log(data.jwt);
            
            storeAccount({
                id: data.user.id,
                name: getAccountDisplayName(data, email),
                email,
                token: data.token || data.jwt || ''
            });

            // --- REMEMBER ME STORAGE HANDLING ---
            if (isRememberMeChecked) {
                localStorage.setItem('remembered_email', email);
            } else {
                localStorage.removeItem('remembered_email');
            }

            if (status) {
                status.textContent = 'Login successful.';
                status.className = 'small text-success';
            }
            
            const modalEl = document.getElementById('accountModal');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();

            // Password Manager integration fix & Reset handling
            setTimeout(() => {
                loginForm.reset();
                if (isRememberMeChecked && emailInput && rememberCheckbox) {
                    emailInput.value = email;
                    rememberCheckbox.checked = true;
                }
            }, 500);

        } catch (error) {
            if (status) {
                status.textContent = error.message;
                status.className = 'small text-danger';
            }
        } finally {
            if (submitButton) submitButton.disabled = false;
        }
    });

   
    openAccountForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const status = document.getElementById('openAccountStatus');
        const submitButton = openAccountForm.querySelector('button[type="submit"]');
        
        const firstName = document.getElementById('accountFirstName').value.trim();
        const lastName = document.getElementById('accountLastName').value.trim();
        const email = document.getElementById('accountEmail').value.trim();
        const phone = document.getElementById('accountPhone').value.trim();
        const address = document.getElementById('accountAddress').value.trim();
        const postalCode = document.getElementById('accountPostalCode').value.trim();
        const password = document.getElementById('accountPassword').value;
        const confirmPassword = document.getElementById('accountConfirmPassword').value;

        if (status) status.textContent = '';

        if (password !== confirmPassword) {
            if (status) {
                status.textContent = 'Passwords do not match!';
                status.className = 'small text-danger';
                showToast('Passwords do not match!', 'error');
            }
            return; 
        }

    const cleanPostal = postalCode.replace(/\s+/g, '');
        
        const strictAlphanumericRegex = /^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]+$/;

        if (!cleanPostal || !strictAlphanumericRegex.test(cleanPostal)) {
            if (status) {
                status.textContent = 'Postal code must contain Alphanumeric characters !';
                status.className = 'small text-danger';
                showToast('Postal code must contain Alphanumeric characters !', 'error');
            }
            return; 
        }

        const phoneRegex = /^[0-9]+$/;
        if (!phone || !phoneRegex.test(phone)) {
            if (status) {
                status.textContent = 'Phone number must contain numbers only!';
                status.className = 'small text-danger';
                showToast('Phone number must contain numbers only!', 'error');
            }
            return; 
        }

        if (submitButton) submitButton.disabled = true;

        try {
            const fullName = `${firstName} ${lastName}`.trim();
            const data = await submitAccountForm('open-account', { 
                name: fullName, firstName, lastName, email, phone, address, postalCode, password 
            });

            storeAccount({
                id: data?.user?.id,
                name: data?.name || data?.user?.name || data?.display_name || fullName,
                email,
                token: data.token || data.jwt || ''
            });

            if (status) {
                status.textContent = 'Account created successfully.';
                status.className = 'small text-success';
            }
            
            const modalEl = document.getElementById('accountModal');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            
            setTimeout(() => {
                openAccountForm.reset();
            }, 500);
            
        } catch (error) {
            if (status) {
                status.textContent = error.message;
                status.className = 'small text-danger';
            }
        } finally {
            if (submitButton) submitButton.disabled = false;
        }
    });


});
</script>
</head>

<body>

<header class="site-header bg-dark text-white">
    <div class="container">

        <div class="header-layout">

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


            <div class="header-switch-links">

                <a class="v-nav-link" href="/shop/">
                    <span>Shop</span>
                </a>

                <a class="v-nav-link" href="/">
                    <span>Sell</span>
                </a>

            </div>


            <div class="header-search position-relative my-2  d-md-block" id="mobileSearchWrapper">
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

                <div class="mobile-header-switch-links d-flex gap-2 px-3 mb-3">
  
                        <a href="/shop/" 
                            class="btn text-white fw-semibold py-2 px-3 flex-fill text-center shadow-sm text-decoration-none d-inline-flex align-items-center justify-content-center"
                            title="Shop"
                            style="font-size: 0.95rem; background-color: #13564f; border-radius: 5px; border: 1px solid #2c7c74; letter-spacing: 0.5px;">
                            Shop
                        </a>

                
                        <a href="/" 
                            class="btn text-white fw-semibold py-2 px-3 flex-fill text-center shadow-sm text-decoration-none d-inline-flex align-items-center justify-content-center"
                            title="Sell"
                            style="font-size: 0.95rem;color:white; background-color: #13564f; border-radius: 5px; border: 1px solid #2c7c74; letter-spacing: 0.5px;">
                            Sell
                        </a>
                    </div>
                <div class="">
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
                </div>
                <span id="guestAccountActions" class="account-actions">
                    <button type="button" class="btn btn-login text-white" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="login">
                        Login
                    </button>

                    <button type="button" class="btn btn-account text-white" data-bs-toggle="modal" data-bs-target="#accountModal" data-account-tab="open-account">
                        <i class="bi bi-person"></i>
                        Create Account
                    </button>
                </span>

            <div id="userAccountActions" class="d-none dropdown account-welcome-dropdown">
                    <button class="btn dropdown-toggle d-inline-flex align-items-center gap-2 text-white border-0 px-3 py-1.5" 
                            type="button" 
                            id="desktopUserDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                            style="background: transparent; font-weight: 500; font-size: 0.95rem;">
                        <i class="bi bi-person-circle fs-5 text-white-50"></i>
                        <span id="welcomeName" class="fw-semibold text-white">Account</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 desktop-premium-dropdown-menu" 
                        aria-labelledby="desktopUserDropdown">
                        
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2.5" href="<?php echo $config['BASE_URL']; ?>user/settings/">
                                <i class="bi bi-gear-fill text-secondary"></i>
                                <span>Your Account</span>
                            </a>
                        </li>
                        
                        <!-- <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2.5" href="<?php echo $config['BASE_URL']; ?>user/orders/">
                                <i class="bi bi-bag-check-fill text-secondary"></i>
                                <span>Your Orders</span>
                            </a>
                        </li> -->
                        
                        <li><hr class="dropdown-divider my-2" style="border-color: #f1f3f5;"></li>
                        
                        <li>
                            <button type="button" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2.5 fw-semibold" id="logoutButton">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </li>
                    </ul>
            </div>

            </div>

        </div>
    </div>
</header>


<nav class="navbar navbar-expand-lg bg-light main-menu-navbar" id="mainMenuNav">

    <div class="container">

        

        <ul class="navbar-nav mx-auto" id="menu">
       
        </ul>

    </div>

</nav>

<div class="mobile-menu-overlay" id="mobileMenuOverlay">
    
</div>



<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content account-modal" style="border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden;">
            
            <div class="modal-header" style="background-color: #ffffff; border-bottom: 1px solid #f1f1f1; padding: 20px 24px;">
                <h5 class="modal-title" id="accountModalLabel" style="font-weight: 700; color: #212529; letter-spacing: -0.5px;">Your Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: none;"></button>
            </div>

            <div class="modal-body" style="padding: 24px;">
                <ul class="nav nav-pills account-tabs mb-4" id="accountTabs" role="tablist" style="background: #f8f9fa; padding: 6px; border-radius: 30px; display: inline-flex;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#loginPane" type="button" role="tab" aria-controls="loginPane" aria-selected="true" 
                                style="border-radius: 25px; font-weight: 600; padding: 8px 24px; transition: all 0.3s ease;">
                            Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="open-account-tab" data-bs-toggle="pill" data-bs-target="#openAccountPane" type="button" role="tab" aria-controls="openAccountPane" aria-selected="false"
                                style="border-radius: 25px; font-weight: 600; padding: 8px 24px; transition: all 0.3s ease;">
                            Create Account
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="loginPane" role="tabpanel" aria-labelledby="login-tab">
                        <form id="loginForm" style="max-width: 450px; margin: 0 auto; padding: 10px 0;" autocomplete="on">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057;">
                                    Email <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@email.com" required 
                                    autocomplete="username"
                                    style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057;">
                                    Password <span style="color: #dc3545;">*</span>
                                </label>
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="••••••••" required 
                                    autocomplete="current-password"
                                    style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input" id="loginRemember" name="remember" style="cursor: pointer; box-shadow: none;">
                                <label class="form-check-label ms-2 small text-muted" for="loginRemember" style="cursor: pointer; font-weight: 500; user-select: none;">
                                    Remember Me
                                </label>
                            </div>

                            <p id="loginStatus" class="small mb-3" style="font-weight: 500;"></p>
                            <button type="submit" class="btn btn-dark w-100" style="padding: 12px; border-radius: 8px; font-weight: 600; background-color: #212529; border: none; transition: background 0.2s;">
                                Login
                            </button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="openAccountPane" role="tabpanel" aria-labelledby="open-account-tab">
                        <form id="openAccountForm">
                            <div class="row g-3">
                                
                                <div class="col-md-6">
                                    <label for="accountFirstName" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">First Name <span style="color: #dc3545;">*</span></label>
                                    <input type="text" class="form-control" id="accountFirstName" name="firstName" placeholder="Enter First Name" required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                    <label for="accountLastName" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Last Name <span style="color: #dc3545;">*</span></label>
                                    <input type="text" class="form-control" id="accountLastName" name="lastName" placeholder="Enter Last Name" required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                    <label for="accountEmail" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Email <span style="color: #dc3545;">*</span></label>
                                    <input type="email" class="form-control" id="accountEmail" name="email" placeholder="Enter Email" required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                   <label for="accountPhone" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Phone Number <span style="color: #dc3545;">*</span></label>
                                        
                                    <input type="tel" class="form-control" id="accountPhone" name="phone" placeholder="Enter Phone Number" required 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"       
                                    style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                    <label for="accountAddress" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Address Line <span style="color: #dc3545;">*</span></label>
                                    <input type="text" class="form-control" id="accountAddress" name="address" placeholder="Enter Address Line" required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                    <label for="accountPostalCode" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Postal Code <span style="color: #dc3545;">*</span></label>
                                    <input type="text" class="form-control" id="accountPostalCode" name="postalCode" placeholder="postcode" required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                    <label for="accountPassword" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Password <span style="color: #dc3545;">*</span></label>
                                    <input type="password" class="form-control" id="accountPassword" name="password" placeholder="Enter Password..." required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                                <div class="col-md-6">
                                    <label for="accountConfirmPassword" class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #495057; margin-bottom: 6px;">Confirm Password <span style="color: #dc3545;">*</span></label>
                                    <input type="password" class="form-control" id="accountConfirmPassword" name="confirmPassword" placeholder="Enter Confirm Password" required 
                                           style="padding: 11px 16px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 0.95rem; box-shadow: none;">
                                </div>

                            </div>

                            <p id="openAccountStatus" class="small mt-3 mb-2" style="font-weight: 500;"></p>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-dark w-100" style="padding: 12px; border-radius: 8px; font-weight: 600; background-color: #212529; border: none; transition: background 0.2s;">Create Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 10500;">
    <div id="liveToast" class="toast align-items-center text-white border-0 shadow rounded-3" role="alert" aria-live="assertive" aria-atomic="true" style="transition: all 0.3s ease;">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2" style="font-weight: 500; font-size: 0.95rem;">
                <span id="toast-icon"></span>
                <span id="toast-message"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
