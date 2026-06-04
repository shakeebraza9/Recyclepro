<?php
$pageTitle = 'Account Settings';
include __DIR__ . '/../../includes/header.php';
?>
<style>

:root {
    --g0: #044339;
    --g1: #0b6e5f;
    --g-light: rgba(4,67,57,.06);
    --bg-page: #f4f6f6;
    --bg-card: #ffffff;
    --bg-sidebar: #fafbfb;
    --border: #e2e8f0;
    --border-soft: rgba(4,67,57,.07);
    --text-dark: #0f172a;
    --text-muted: #64748b;
    --radius-md: 14px;
    --radius-lg: 20px;
    --radius-xl: 28px;
    --shadow-card: 0 20px 50px rgba(4,67,57,.03);
    --shadow-btn: 0 10px 25px rgba(4,67,57,.18);
    --shadow-btn-hover: 0 14px 30px rgba(4,67,57,.28);
    --font: 'Inter', system-ui, -apple-system, sans-serif;
}

.rp-settings { padding: 72px 0 120px; background: var(--bg-page); font-family: var(--font); }
.rp-shell {
    display: flex;
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-soft);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}


.rp-sidebar {
    width: 260px; flex-shrink: 0;
    background: var(--bg-sidebar);
    border-right: 1px solid var(--border);
    padding: 36px 20px;
}
.rp-nav-item {
    display: flex; align-items: center; gap: 13px;
    padding: 13px 16px;
    color: var(--text-muted);
    font-weight: 600; font-size: .93rem;
    border-radius: var(--radius-md);
    cursor: pointer;
    border: 1px solid transparent;
    transition: all .25s ease;
    margin-bottom: 6px;
    user-select: none;
}
.rp-nav-item i { font-size: 1.15rem; transition: transform .25s ease; }
.rp-nav-item:hover { color: var(--g0); background: var(--g-light); }
.rp-nav-item.active {
    color: var(--g0); background: var(--bg-card);
    border-color: var(--border-soft);
    box-shadow: 0 4px 12px rgba(4,67,57,.05);
}
.rp-nav-item.active i { transform: scale(1.12); color: var(--g1); }


.rp-content { flex: 1; min-width: 0; padding: 44px 48px; }
.pane-title { font-size: 1.55rem; font-weight: 800; color: var(--text-dark); letter-spacing: -.4px; margin: 0 0 4px; }
.pane-sub   { font-size: .88rem; color: var(--text-muted); margin: 0 0 30px; }


.rp-panel { display: none; }
.rp-panel.active { display: block; animation: fadeUp .4s cubic-bezier(.16,1,.3,1) forwards; }
@keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }


.field-label {
    display: block; font-size: .8rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: #475569; margin-bottom: 7px;
}
.field-label span { color: #f43f5e; }
.rp-input {
    width: 100%; height: 50px;
    border-radius: var(--radius-md) !important;
    border: 1px solid #cbd5e1;
    background: var(--bg-card);
    padding: 0 16px;
    font-size: .93rem; color: var(--text-dark); font-weight: 500;
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box;
}
.rp-input:focus { outline: none; border-color: var(--g0) !important; box-shadow: 0 0 0 3px rgba(4,67,57,.07) !important; }


.rp-btn-save {
    background: linear-gradient(135deg, var(--g0) 0%, #033029 100%);
    color: #fff; font-weight: 700; font-size: .93rem;
    padding: 13px 34px; border-radius: 16px; border: none;
    box-shadow: var(--shadow-btn);
    transition: all .25s ease; cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
}
.rp-btn-save:hover:not(:disabled) { transform: translateY(-2px); box-shadow: var(--shadow-btn-hover); color: #fff; }
.rp-btn-save:active:not(:disabled) { transform: translateY(0); }
.rp-btn-save:disabled { opacity: .7; cursor: not-allowed; }
.btn-outline-rp {
    border: 1px solid var(--border); background: var(--bg-card);
    color: #475569; font-size: .83rem; font-weight: 600;
    padding: 8px 15px; border-radius: 10px;
    transition: all .2s ease; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
}
.btn-outline-rp:hover { background: #f8fafc; border-color: #cbd5e1; color: var(--text-dark); }


#paneLoader {
    display: none; text-align: center;
    padding: 80px 0; color: var(--text-muted); font-size: .9rem;
}
#paneLoader .spinner-border { color: var(--g0); width: 2.2rem; height: 2.2rem; }


.rp-form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 28px; margin-top: 28px;
    border-top: 1px solid #f1f5f9;
}
.enc-note { font-size: .83rem; color: var(--text-muted); font-weight: 500; }


.stat-card {
    background: var(--bg-card); border: 1px solid var(--border-soft);
    border-radius: 18px; padding: 18px 22px;
    display: flex; align-items: center; gap: 14px;
}
.stat-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; flex-shrink: 0;
}


.orders-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 36px; margin-top: 4px;
}
.orders-header {
    display: flex; justify-content: space-between;
    align-items: flex-start; flex-wrap: wrap; gap: 14px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 20px; margin-bottom: 30px;
}
.filter-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.filter-pill {
    background: none; border: none; padding: 7px 18px;
    font-size: .87rem; font-weight: 600; color: var(--text-muted);
    border-radius: 12px; cursor: pointer; transition: all .2s ease;
}
.filter-pill.active { background: var(--g0); color: #fff; box-shadow: 0 4px 12px rgba(4,67,57,.15); }


.order-row {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 22px; margin-bottom: 16px;
    transition: box-shadow .25s ease, border-color .25s ease;
}
.order-row:last-child { margin-bottom: 0; }
.order-row:hover { border-color: #cbd5e1; box-shadow: 0 6px 20px rgba(0,0,0,.03); }
.order-meta-row {
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;
}
.order-ref { font-size: 1rem; font-weight: 800; color: var(--text-dark); }
.order-ref small { font-weight: 400; color: var(--text-muted); font-size: .86rem; margin-left: 8px; }
.badge--base {
    font-size: .74rem; font-weight: 700; text-transform: uppercase;
    padding: 5px 13px; border-radius: 100px; letter-spacing: .4px;
    display: inline-flex; align-items: center; gap: 5px;
}
.badge--processing { background:#fffbeb; color:#b45309; }
.badge--delivered  { background:#f0fdf4; color:#166534; }
.order-product { display: flex; align-items: center; gap: 14px; }
.product-icon-box {
    width: 60px; height: 60px; background: #f8fafc;
    border: 1px solid #f1f5f9; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); font-size: 1.35rem; flex-shrink: 0;
}
.product-name { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin: 0 0 6px; }
.spec-chip {
    font-size: .73rem; background: #f1f5f9; color: #475569;
    padding: 3px 9px; border-radius: 7px; font-weight: 600; margin-right: 5px;
}
.order-price { font-size: 1.2rem; font-weight: 800; color: var(--g0); }


.orders-loading { text-align: center; padding: 50px 20px; color: var(--text-muted); }
.orders-loading .spinner-border { color: var(--g0); }
.empty-orders { text-align: center; padding: 50px 20px; color: var(--text-muted); }
.empty-orders i { font-size: 2.4rem; display: block; margin-bottom: 10px; }


@keyframes spin { to { transform: rotate(360deg); } }
.spin { display: inline-block; animation: spin 1.1s linear infinite; }


@media (max-width: 991px) {
    .rp-shell { flex-direction: column; }
    .rp-sidebar {
        width: 100%; border-right: none; border-bottom: 1px solid var(--border);
        padding: 16px; display: flex; flex-wrap: wrap; gap: 6px;
    }
    .rp-nav-item { margin-bottom: 0; flex: 1; min-width: 130px; justify-content: center; }
    .rp-content { padding: 28px 20px; }
    .orders-card { padding: 20px 16px; }
}
@media (max-width: 575px) {
    .rp-form-footer { flex-direction: column; gap: 14px; align-items: flex-start; }
    .enc-note { display: none; }
}
</style>

<main class="rp-settings">
<div class="container" style="max-width:82%;">
<div class="rp-shell">


    <nav class="rp-sidebar" aria-label="Settings navigation">
        <div class="rp-nav-item active" data-target="personal-panel" role="button" tabindex="0">
            <i class="bi bi-person-vcard-fill"></i><span>Personal Info</span>
        </div>
        <div class="rp-nav-item" data-target="shipping-panel" role="button" tabindex="0">
            <i class="bi bi-truck"></i><span>Shipping Address</span>
        </div>
        <div class="rp-nav-item" data-target="security-panel" role="button" tabindex="0">
            <i class="bi bi-shield-lock-fill"></i><span>Security &amp; Password</span>
        </div>
        <div class="rp-nav-item" data-target="order-panel" role="button" tabindex="0">
            <i class="bi bi-box-seam-fill"></i><span>Order History</span>
        </div>
    </nav>

  
    <div class="rp-content">


        <div id="paneLoader">
            <div class="spinner-border" role="status" aria-label="Loading"></div>
            <p class="mt-2 mb-0">Loading your account&hellip;</p>
        </div>

        <form id="settingsForm" novalidate autocomplete="off" style="display:none;">


            <div class="rp-panel active" id="personal-panel">
                <h2 class="pane-title">Personal Information</h2>
                <p class="pane-sub">Update your name, email, and contact number.</p>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="field-label" for="setFirstName">First Name <span>*</span></label>
                        <input type="text" id="setFirstName" class="rp-input" placeholder="First Name" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="field-label" for="setLastName">Last Name <span>*</span></label>
                        <input type="text" id="setLastName" class="rp-input" placeholder="Last Name" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="field-label" for="setEmail">Email Address <span>*</span></label>
                        <input type="email" id="setEmail" class="rp-input" placeholder="email@example.com" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="field-label" for="setPhone">Phone Number</label>
                        <input type="tel" id="setPhone" class="rp-input" placeholder="+44 7123 456789">
                    </div>
                </div>
            </div>

  
            <div class="rp-panel" id="shipping-panel">
                <h2 class="pane-title">Shipping Destination</h2>
                <p class="pane-sub">Set your default shipping address for faster checkout.</p>
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label class="field-label" for="setAddress">Address Line</label>
                        <input type="text" id="setAddress" class="rp-input" placeholder="123 Luxury Road, Suite B">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="field-label" for="setPostalCode">Postal Code</label>
                        <input type="text" id="setPostalCode" class="rp-input" placeholder="E1 6AN">
                    </div>
                </div>
            </div>


            <div class="rp-panel" id="security-panel">
                <h2 class="pane-title">Security &amp; Password</h2>
                <p class="pane-sub">Leave blank if you don't want to change your password.</p>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="field-label" for="setNewPassword">New Password</label>
                        <input type="password" id="setNewPassword" class="rp-input" placeholder="••••••••">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="field-label" for="setConfirmPassword">Confirm Password</label>
                        <input type="password" id="setConfirmPassword" class="rp-input" placeholder="••••••••">
                    </div>
                </div>
            </div>

   
            <div class="rp-panel" id="order-panel">

    
                <div class="row g-3 mb-4" id="orderStatRow">
                    <div class="col-12 col-sm-6 col-md-5">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:var(--g-light); color:var(--g0);">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-medium">Total Placed</div>
                                <div class="fw-bold" id="statTotal" style="font-size:1.25rem; color:var(--text-dark);">--</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-5">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:#fffbeb; color:#b45309;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-medium">Active Transit</div>
                                <div class="fw-bold" id="statActive" style="font-size:1.25rem; color:var(--text-dark);">--</div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="orders-card">
                    <div class="orders-header">
                        <div>
                            <h3 class="fw-bold mb-1" style="font-size:1.3rem; color:var(--text-dark);">Order History</h3>
                            <p class="text-muted small mb-0">View, track, and manage all your orders.</p>
                        </div>
                        <div class="filter-pills" role="group" aria-label="Filter orders">
                            <button type="button" class="filter-pill active" data-filter="all">All</button>
                            <button type="button" class="filter-pill" data-filter="ongoing">Ongoing</button>
                            <button type="button" class="filter-pill" data-filter="completed">Completed</button>
                        </div>
                    </div>


                    <div id="orderListContainer">
                        <div class="orders-loading">
                            <div class="spinner-border mb-2" role="status"></div>
                            <p class="mb-0">Loading orders&hellip;</p>
                        </div>
                    </div>
                </div>

            </div>


            <div class="rp-form-footer" id="formFooter">
                <span class="enc-note">
                    <i class="bi bi-shield-check text-success"></i>
                    End-to-end data encryption enabled.
                </span>
                <button type="submit" class="rp-btn-save" id="submitBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                    <i class="bi bi-check2-all" id="btnIcon"></i>
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>
</div>
</main>

<script>
(function () {
    'use strict';


    const BASE = `${baseAPI}wp-json/wp/v2`;


    const navItems   = document.querySelectorAll('.rp-nav-item');
    const panels     = document.querySelectorAll('.rp-panel');
    const form       = document.getElementById('settingsForm');
    const submitBtn  = document.getElementById('submitBtn');
    const btnSpinner = document.getElementById('btnSpinner');
    const btnIcon    = document.getElementById('btnIcon');
    const paneLoader = document.getElementById('paneLoader');
    const formFooter = document.getElementById('formFooter');
    const orderList  = document.getElementById('orderListContainer');
    const statTotal  = document.getElementById('statTotal');
    const statActive = document.getElementById('statActive');


    function getSession() {
        try { return JSON.parse(localStorage.getItem('recycleproAccount')) || null; }
        catch { return null; }
    }
    function getUserId() {
        const s = getSession();
        return s ? (s.wp_user_id || s.id || '') : '';
    }
    function saveSession(patch) {
        const s = getSession();
        if (s) localStorage.setItem('recycleproAccount', JSON.stringify(Object.assign(s, patch)));
    }


    const toast = (msg, type = 'info') =>
        typeof showToast === 'function' ? showToast(msg, type) : console.info(`[${type}] ${msg}`);


    navItems.forEach(item => {
        const activate = () => {
            navItems.forEach(n => n.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            item.classList.add('active');

            const target = item.dataset.target;
            const panel  = document.getElementById(target);
            if (panel) panel.classList.add('active');


            formFooter.style.display = (target === 'order-panel') ? 'none' : '';


            if (target === 'order-panel' && !orderList.dataset.loaded) {
                loadOrders();
            }
        };
        item.addEventListener('click', activate);
        item.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(); }
        });
    });


    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            const filter = pill.dataset.filter;
            document.querySelectorAll('.order-row').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.orderType === filter) ? '' : 'none';
            });
        });
    });


    function productIcon(name) {
        const n = (name || '').toLowerCase();
        if (n.includes('macbook') || n.includes('laptop')) return 'bi-laptop';
        if (n.includes('ipad')   || n.includes('tablet'))  return 'bi-tablet';
        return 'bi-phone';
    }


    function renderOrderRow(order) {
        const status  = (order.status || '').toLowerCase();
        const isDone  = ['completed', 'delivered'].includes(status);
        const filter  = isDone ? 'completed' : 'ongoing';
        const badge   = isDone ? 'badge--delivered' : 'badge--processing';
        const bIcon   = isDone ? 'bi-check2-circle' : 'bi-arrow-repeat';
        const spin    = isDone ? '' : 'spin';
        const stText  = isDone ? 'Delivered' : status.replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        const aIcon   = isDone ? 'bi-arrow-counterclockwise' : 'bi-geo-alt';
        const aText   = isDone ? 'Reorder' : 'Track';

        const item     = (order.items && order.items[0]) || {};
        const product  = item.name     || 'Unknown Device';
        const qty      = item.quantity || 1;
        const currency = order.currency || 'GBP';
        const symbol   = currency === 'GBP' ? '£' : '$';
        const total    = parseFloat(order.total || 0).toFixed(2);
        const date     = order.date_created
            ? new Date(order.date_created).toLocaleDateString('en-GB', { year:'numeric', month:'long', day:'numeric' })
            : '';
        const ordNum   = order.order_number || order.order_id || '';
        const icon     = productIcon(product);
        const invoiceUrl = `${BASE_URL}templates/view.php?order_id=${encodeURIComponent(order.order_id || '')}`;

        return `
        <div class="order-row" data-order-type="${filter}">
            <div class="order-meta-row">
                <div class="order-ref">
                    #RP-${escHtml(String(ordNum))}
                    <small>Ordered on ${date}</small>
                </div>
                <span class="badge--base ${badge}">
                    <i class="bi ${bIcon} ${spin}"></i> ${escHtml(stText)}
                </span>
            </div>
            <div class="row align-items-center gy-3">
                <div class="col-12 col-md-6">
                    <div class="order-product">
                        <div class="product-icon-box"><i class="bi ${icon}"></i></div>
                        <div>
                            <p class="product-name">${escHtml(product)}</p>
                            <span class="spec-chip">Qty: ${qty}</span>
                            <span class="spec-chip">${escHtml(currency)}</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2 text-center">
                    <div class="small text-muted mb-1">Amount</div>
                    <div class="order-price">${symbol}${total}</div>
                </div>
                <div class="col-6 col-md-4 d-flex gap-2 justify-content-end">
                    <button type="button" class="btn-outline-rp">
                        <i class="bi ${aIcon}"></i> ${aText}
                    </button>
                    <button type="button" class="btn-outline-rp"
                        onclick="window.open('${invoiceUrl}','_blank')">
                        <i class="bi bi-receipt"></i> Invoice
                    </button>
                </div>
            </div>
        </div>`;
    }


    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }


    async function loadOrders() {
        const userId = getUserId();
        if (!userId) {
            orderList.innerHTML = '<div class="empty-orders"><i class="bi bi-person-x"></i><p>Please log in to view orders.</p></div>';
            return;
        }

        orderList.innerHTML = '<div class="orders-loading"><div class="spinner-border mb-2" role="status"></div><p class="mb-0">Loading orders&hellip;</p></div>';

        try {
            const res  = await fetch(`${BASE}/user-order-info/${userId}`);
            const data = await res.json();

            if (!res.ok || !data.success) throw new Error(data.message || 'Could not load orders.');

            const orders = data.orders || [];

            // Update stat counters
            const active = orders.filter(o => !['completed','delivered'].includes((o.status||'').toLowerCase())).length;
            statTotal.textContent  = String(orders.length).padStart(2,'0') + ' Orders';
            statActive.textContent = String(active).padStart(2,'0') + ' Active';

            if (orders.length === 0) {
                orderList.innerHTML = '<div class="empty-orders"><i class="bi bi-box-seam"></i><p class="mb-0">No orders found for this account.</p></div>';
            } else {
                orderList.innerHTML = orders.map(renderOrderRow).join('');
            }

            orderList.dataset.loaded = '1';

        } catch (err) {
            console.error('loadOrders:', err);
            orderList.innerHTML = `<div class="empty-orders"><i class="bi bi-exclamation-circle"></i><p class="mb-0">${escHtml(err.message)}</p></div>`;
        }
    }


    async function loadUserData() {
        const userId = getUserId();
        if (!userId) {
            paneLoader.innerHTML = '<p class="text-danger mt-4">Session not found. Please <a href="/login">log in</a>.</p>';
            paneLoader.style.display = 'block';
            return;
        }

        paneLoader.style.display = 'block';
        form.style.display = 'none';

        try {
            const res  = await fetch(`${BASE}/user/${userId}`);
            const data = await res.json();

            if (!res.ok || !data.success) throw new Error(data.message || 'Failed to load profile.');

            const u = data.user || {};
            document.getElementById('setFirstName').value  = u.first_name  || '';
            document.getElementById('setLastName').value   = u.last_name   || '';
            document.getElementById('setEmail').value      = u.email       || '';
            document.getElementById('setPhone').value      = u.phone       || '';
            document.getElementById('setAddress').value    = u.address     || '';
            document.getElementById('setPostalCode').value = u.postal_code || '';

        } catch (err) {
            console.error('loadUserData:', err);
            toast(err.message || 'Error loading profile.', 'error');
        } finally {
            paneLoader.style.display = 'none';
            form.style.display = 'block';
        }
    }


    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const userId = getUserId();
        if (!userId) { toast('Session expired. Please log in.', 'error'); return; }

        const newPass  = document.getElementById('setNewPassword').value;
        const confPass = document.getElementById('setConfirmPassword').value;

        if (newPass && newPass !== confPass) {
            toast('Passwords do not match!', 'warning');
            return;
        }

        submitBtn.disabled = true;
        btnSpinner.classList.remove('d-none');
        btnIcon.classList.add('d-none');

        const payload = {
            first_name:  document.getElementById('setFirstName').value.trim(),
            last_name:   document.getElementById('setLastName').value.trim(),
            email:       document.getElementById('setEmail').value.trim(),
            phone:       document.getElementById('setPhone').value.trim(),
            address:     document.getElementById('setAddress').value.trim(),
            postal_code: document.getElementById('setPostalCode').value.trim(),
        };
        if (newPass) payload.password = newPass;

        try {
            const res  = await fetch(`${BASE}/user/update/${userId}`, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(payload),
            });
            const data = await res.json();

            if (!res.ok || !data.success) throw new Error(data.message || 'Update failed.');

            toast('Settings saved successfully!', 'success');

  
            saveSession({ first_name: payload.first_name, last_name: payload.last_name, email: payload.email });

            document.getElementById('setNewPassword').value    = '';
            document.getElementById('setConfirmPassword').value = '';

        } catch (err) {
            console.error('saveSettings:', err);
            toast(err.message || 'Failed to save settings.', 'error');
        } finally {
            submitBtn.disabled = false;
            btnSpinner.classList.add('d-none');
            btnIcon.classList.remove('d-none');
        }
    });


    loadUserData();

})();
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>