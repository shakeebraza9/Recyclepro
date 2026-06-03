<?php
$pageTitle = 'Account Settings';
include __DIR__ . '/../../includes/header.php';
?>
<style>
    :root {
        --lux-green: #044339;
        --lux-emerald: #0b6e5f;
        --lux-bg: #f4f6f6;
        --lux-border: #e2e8f0;
        --lux-text-dark: #0f172a;
        --lux-text-muted: #64748b;
    }
    .settings-portal-section {
        padding: 80px 0 120px 0;
        background-color: var(--lux-bg);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .lux-dashboard-grid {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid rgba(4, 67, 57, 0.05);
        box-shadow: 0 20px 50px rgba(4, 67, 57, 0.03);
        overflow: hidden;
    }
    .lux-sidebar-nav {
        background-color: #fafbfb;
        border-right: 1px solid var(--lux-border);
        padding: 40px 24px;
    }
    .lux-nav-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        color: var(--lux-text-muted);
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        margin-bottom: 8px;
        border: 1px solid transparent;
    }
    .lux-nav-item i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }
    .lux-nav-item:hover {
        color: var(--lux-green);
        background-color: rgba(4, 67, 57, 0.03);
    }
    .lux-nav-item.active {
        color: var(--lux-green);
        background-color: #ffffff;
        border-color: rgba(4, 67, 57, 0.08);
        box-shadow: 0 4px 12px rgba(4, 67, 57, 0.04);
    }
    .lux-nav-item.active i {
        transform: scale(1.1);
        color: var(--lux-emerald);
    }
    .lux-content-pane {
        padding: 45px 50px;
    }
    .pane-header {
        margin-bottom: 35px;
    }
    .pane-header h2 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--lux-text-dark);
        letter-spacing: -0.5px;
    }
    .pane-header p {
        font-size: 0.9rem;
        color: var(--lux-text-muted);
    }
    .lux-form-group {
        position: relative;
        margin-bottom: 24px;
    }
    .lux-field-label {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 8px;
        display: block;
    }
    .lux-field-label span {
        color: #f43f5e;
    }
    .lux-input {
        height: 52px;
        border-radius: 14px !important;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        padding: 12px 18px;
        font-size: 0.95rem;
        color: var(--lux-text-dark);
        font-weight: 500;
        transition: all 0.25s ease;
    }
    .lux-input:focus {
        border-color: var(--lux-green) !important;
        box-shadow: 0 0 0 4px rgba(4, 67, 57, 0.06) !important;
        background-color: #ffffff;
    }

    /* FIX 1: settings-panel — all panels use this class */
    .settings-panel {
        display: none;
        animation: paneFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .settings-panel.active {
        display: block;
    }
    @keyframes paneFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* FIX 2: spin animation for processing icon */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    .spin {
        display: inline-block;
        animation: spin 1.2s linear infinite;
    }

    .lux-btn-submit {
        background: linear-gradient(135deg, var(--lux-green) 0%, #033029 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: -0.2px;
        padding: 14px 36px;
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 25px rgba(4, 67, 57, 0.18);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .lux-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(4, 67, 57, 0.28);
        color: #ffffff;
    }
    .lux-btn-submit:active {
        transform: translateY(0);
    }

    /* Orders styles */
    .stat-counter-card {
        background: #ffffff;
        border: 1px solid rgba(4, 67, 57, 0.06);
        border-radius: 18px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .lux-master-orders-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 15px 40px rgba(4, 67, 57, 0.02);
    }
    .orders-navigation-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        border-bottom: 1px solid var(--lux-border);
        padding-bottom: 20px;
        margin-bottom: 35px;
    }
    .filter-pill {
        background: none;
        border: none;
        padding: 8px 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--lux-text-muted);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .filter-pill.active {
        background-color: var(--lux-green);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(4, 67, 57, 0.15);
    }
    .standalone-order-row {
        background: #ffffff;
        border: 1px solid var(--lux-border);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .standalone-order-row:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.03);
    }
    .meta-top-strip {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .order-reference-id {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--lux-text-dark);
    }
    .order-reference-id span {
        color: var(--lux-text-muted);
        font-weight: 400;
        font-size: 0.88rem;
    }
    .status-badge {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 100px;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-processing { background-color: #fffbeb; color: #b45309; }
    .status-delivered  { background-color: #f0fdf4; color: #166534; }
    .order-media-block {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .order-media-icon-box {
        width: 64px;
        height: 64px;
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--lux-text-muted);
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .product-title-specs h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--lux-text-dark);
        margin: 0 0 6px 0;
    }
    .spec-chip {
        font-size: 0.75rem;
        background-color: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        margin-right: 6px;
    }
    .row-price-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--lux-green);
    }
    .lux-btn-action-outline {
        border: 1px solid var(--lux-border);
        background: #ffffff;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .lux-btn-action-outline:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: var(--lux-text-dark);
    }

    @media (max-width: 768px) {
        .lux-content-pane { padding: 30px 20px; }
        .lux-master-orders-card { padding: 20px; }
        .responsive-center-align { text-align: left !important; margin-top: 15px; }
        .action-buttons-wrapper { justify-content: flex-start !important; margin-top: 15px; }
        .orders-navigation-row { flex-direction: column; align-items: flex-start; }
    }
</style>

<main class="settings-portal-section">
    <div class="container" style="max-width: 100% !important;">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">

                <div class="row g-0 lux-dashboard-grid">

                    <!-- SIDEBAR -->
                    <div class="col-12 col-md-4 col-lg-3 lux-sidebar-nav">
                        <div class="lux-nav-item active" data-target="personal-panel">
                            <i class="bi bi-person-vcard-fill"></i>
                            <span>Personal Info</span>
                        </div>
                        <div class="lux-nav-item" data-target="shipping-panel">
                            <i class="bi bi-truck"></i>
                            <span>Shipping Address</span>
                        </div>
                        <div class="lux-nav-item" data-target="security-panel">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>Security &amp; Pass</span>
                        </div>
                        <!-- FIX 3: data-target matches id="order-panel" exactly -->
                        <div class="lux-nav-item" data-target="order-panel">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>Order Details</span>
                        </div>
                    </div>

                    <!-- CONTENT PANE -->
                    <div class="col-12 col-md-8 col-lg-9 lux-content-pane">

                        <!-- Loader -->
                        <div id="paneLoader" class="text-center py-5 d-none">
                            <div class="spinner-border" style="color: var(--lux-green);" role="status"></div>
                            <p class="text-muted mt-2">Loading your account configurations...</p>
                        </div>

                        <form id="accountSettingsForm" novalidate autocomplete="off">

                            <!-- ── Personal Info Panel ── -->
                            <div class="settings-panel active" id="personal-panel">
                                <div class="pane-header">
                                    <h2>Personal Information</h2>
                                    <p class="text-muted">Update your primary identity settings and platform notification contacts.</p>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">First Name <span>*</span></label>
                                        <input type="text" id="setFirstName" class="form-control lux-input" placeholder="First Name" required>
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Last Name <span>*</span></label>
                                        <input type="text" id="setLastName" class="form-control lux-input" placeholder="Last Name" required>
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Email Address <span>*</span></label>
                                        <input type="email" id="setEmail" class="form-control lux-input" placeholder="email@example.com" required>
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Phone Number</label>
                                        <input type="tel" id="setPhone" class="form-control lux-input" placeholder="+44 7123 456789">
                                    </div>
                                </div>
                            </div>

                            <!-- ── Shipping Panel ── -->
                            <div class="settings-panel" id="shipping-panel">
                                <div class="pane-header">
                                    <h2>Shipping Destination</h2>
                                    <p class="text-muted">Configure default shipping and billing drop-off points for faster order checkouts.</p>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-8 lux-form-group">
                                        <label class="lux-field-label">Address Line</label>
                                        <input type="text" id="setAddress" class="form-control lux-input" placeholder="123 Luxury Road, Suite B">
                                    </div>
                                    <div class="col-12 col-md-4 lux-form-group">
                                        <label class="lux-field-label">Postal Code</label>
                                        <input type="text" id="setPostalCode" class="form-control lux-input" placeholder="E1 6AN">
                                    </div>
                                </div>
                            </div>

                            <!-- ── Security Panel ── -->
                            <div class="settings-panel" id="security-panel">
                                <div class="pane-header">
                                    <h2>Security &amp; Password</h2>
                                    <p class="text-muted">Keep your account encrypted. Leave password blank if you don't wish to change it.</p>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">New Password</label>
                                        <input type="password" id="setNewPassword" class="form-control lux-input" placeholder="••••••••">
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Confirm New Password</label>
                                        <input type="password" id="setConfirmPassword" class="form-control lux-input" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <!-- ── Order Panel ──
                                 FIX 4: class="settings-panel" (was "order-panel") so tab switching works
                                 FIX 3: id="order-panel" matches data-target above
                            -->
                            <div class="settings-panel" id="order-panel">
                                <div class="container-fluid px-0">

                                    <!-- Stat Counters -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="stat-counter-card">
                                                <div class="stat-icon-wrapper" style="background-color: rgba(4,67,57,0.05); color: var(--lux-green);">
                                                    <i class="bi bi-box-seam-fill"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small fw-medium">Total Placed</div>
                                                    <h3 class="mb-0 fw-bold" style="font-size:1.3rem; color:var(--lux-text-dark);">02 Orders</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="stat-counter-card">
                                                <div class="stat-icon-wrapper" style="background-color:#fffbeb; color:#b45309;">
                                                    <i class="bi bi-clock-history"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small fw-medium">Active Transit</div>
                                                    <h3 class="mb-0 fw-bold" style="font-size:1.3rem; color:var(--lux-text-dark);">01 Active</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Orders Card -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="lux-master-orders-card">

                                                <div class="orders-navigation-row">
                                                    <div>
                                                        <h3 class="fw-bold mb-1" style="font-size:1.35rem; color:var(--lux-text-dark);">Order History</h3>
                                                        <p class="text-muted small mb-0">Manage statements, parameters, and live logistical streams.</p>
                                                    </div>
                                                    <!-- FIX 5: filter pills are now wired via JS below -->
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <button type="button" class="filter-pill active" data-filter="all">All Logs</button>
                                                        <button type="button" class="filter-pill" data-filter="ongoing">Ongoing</button>
                                                        <button type="button" class="filter-pill" data-filter="completed">Completed</button>
                                                    </div>
                                                </div>

                                                <div id="standaloneListingContainer">

                                                    <!-- Order 1 — Ongoing -->
                                                    <div class="standalone-order-row" data-order-type="ongoing">
                                                        <div class="meta-top-strip">
                                                            <div class="order-reference-id">#RP-94820 <span class="ms-2">Ordered on May 28, 2026</span></div>
                                                            <span class="status-badge status-processing">
                                                                <i class="bi bi-arrow-repeat spin"></i> Processing
                                                            </span>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-md-6">
                                                                <div class="order-media-block">
                                                                    <div class="order-media-icon-box"><i class="bi bi-phone"></i></div>
                                                                    <div class="product-title-specs">
                                                                        <h4>iPhone 13 Pro Max</h4>
                                                                        <span class="spec-chip">Grade A Mint</span>
                                                                        <span class="spec-chip">256GB</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 col-md-2 responsive-center-align" style="text-align:center;">
                                                                <div class="text-muted small">Amount</div>
                                                                <div class="row-price-value">£549.00</div>
                                                            </div>
                                                            <div class="col-6 col-md-4 text-end action-buttons-wrapper d-flex gap-2 justify-content-end">
                                                                <button type="button" class="btn lux-btn-action-outline"><i class="bi bi-geo-alt"></i> Track</button>
                                                                <button type="button" class="btn lux-btn-action-outline" onclick="window.open('https://localhost/shop/templates/view.php','_blank');">
                                                                    <i class="bi bi-receipt"></i> Invoice
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Order 2 — Completed -->
                                                    <div class="standalone-order-row" data-order-type="completed">
                                                        <div class="meta-top-strip">
                                                            <div class="order-reference-id">#RP-81204 <span class="ms-2">Ordered on April 14, 2026</span></div>
                                                            <span class="status-badge status-delivered">
                                                                <i class="bi bi-check2-circle"></i> Delivered
                                                            </span>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-md-6">
                                                                <div class="order-media-block">
                                                                    <div class="order-media-icon-box"><i class="bi bi-laptop"></i></div>
                                                                    <div class="product-title-specs">
                                                                        <h4>MacBook Air M1</h4>
                                                                        <span class="spec-chip">Grade B Good</span>
                                                                        <span class="spec-chip">8GB RAM</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 col-md-2 responsive-center-align" style="text-align:center;">
                                                                <div class="text-muted small">Amount</div>
                                                                <div class="row-price-value">£610.00</div>
                                                            </div>
                                                            <div class="col-6 col-md-4 text-end action-buttons-wrapper d-flex gap-2 justify-content-end">
                                                                <button type="button" class="btn lux-btn-action-outline"><i class="bi bi-arrow-counterclockwise"></i> Reorder</button>
                                                                <button type="button" class="btn lux-btn-action-outline" onclick="window.open('https://localhost/shop/templates/view.php','_blank');">
                                                                    <i class="bi bi-receipt"></i> Invoice
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div><!-- /standaloneListingContainer -->
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /order-panel -->

                            <!-- FIX 6: id="formFooter" so JS can hide it on Order tab -->
                            <div class="d-flex align-items-center justify-content-between pt-4 mt-4 border-top"
                                 id="formFooter" style="border-color:#f1f5f9 !important;">
                                <span class="text-muted d-none d-sm-inline" style="font-size:0.85rem; font-weight:500;">
                                    <i class="bi bi-shield-check text-success"></i> End-to-end data encryption enabled.
                                </span>
                                <button type="submit" class="btn lux-btn-submit d-inline-flex align-items-center gap-2" id="submitSettingsBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                                    <i class="bi bi-check2-all" id="btnIcon"></i> Save Account Updates
                                </button>
                            </div>

                        </form>
                    </div><!-- /lux-content-pane -->
                </div><!-- /lux-dashboard-grid -->
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const navItems   = document.querySelectorAll('.lux-nav-item');
    const panels     = document.querySelectorAll('.settings-panel');
    const form       = document.getElementById('accountSettingsForm');
    const submitBtn  = document.getElementById('submitSettingsBtn');
    const spinner    = document.getElementById('btnSpinner');
    const icon       = document.getElementById('btnIcon');
    const paneLoader = document.getElementById('paneLoader');
    const formFooter = document.getElementById('formFooter');   // FIX 6

    // ── Tab Navigation ────────────────────────────────────────────────────────
    navItems.forEach(item => {
        item.addEventListener('click', function () {
            navItems.forEach(nav => nav.classList.remove('active'));
            panels.forEach(panel => panel.classList.remove('active'));

            this.classList.add('active');

            const targetId = this.getAttribute('data-target');   // FIX 3 (lowercase match)
            const targetEl = document.getElementById(targetId);
            if (targetEl) targetEl.classList.add('active');

            // FIX 6: hide Save button when Order Details tab is open
            if (targetId === 'order-panel') {
                formFooter.classList.add('d-none');
            } else {
                formFooter.classList.remove('d-none');
            }
        });
    });

    // ── Order Filter Pills ────────────────────────────────────────────────────
    // FIX 5: wire up All / Ongoing / Completed filter buttons
    const filterPills = document.querySelectorAll('.filter-pill');
    const orderRows   = document.querySelectorAll('.standalone-order-row');

    filterPills.forEach(pill => {
        pill.addEventListener('click', function () {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            orderRows.forEach(row => {
                const type = row.getAttribute('data-order-type'); // "ongoing" | "completed"
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === type) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // ── STEP 1: Load user data via AJAX ──────────────────────────────────────
    async function fetchUserAccountData() {
        const accountData = localStorage.getItem('recycleproAccount');
        if (!accountData) {
            if (typeof showToast === 'function') showToast("Please login first!", "error");
            return;
        }

        const loggedInUser = JSON.parse(accountData);
        const userId = loggedInUser.wp_user_id || loggedInUser.id || '';
        if (!userId) {
            if (typeof showToast === 'function') showToast("User ID session missing.", "warning");
            return;
        }

        form.classList.add('d-none');
        paneLoader.classList.remove('d-none');

        const fetchUrl = `https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/user/${userId}`;
        // const fetchUrl = `https://localhost/bkrecyclepro/wp-json/wp/v2/user/1`;

        try {
            const response = await fetch(fetchUrl, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });
            const result = await response.json();

            if (response.ok && result.success) {
                document.getElementById('setFirstName').value  = result.user.first_name  || '';
                document.getElementById('setLastName').value   = result.user.last_name   || '';
                document.getElementById('setEmail').value      = result.user.email       || '';
                if (result.user.phone)       document.getElementById('setPhone').value      = result.user.phone;
                if (result.user.address)     document.getElementById('setAddress').value    = result.user.address;
                if (result.user.postal_code) document.getElementById('setPostalCode').value = result.user.postal_code;
            } else {
                throw new Error(result.message || 'Failed to read database parameters.');
            }
        } catch (error) {
            console.error("AJAX Fetch Exception Error:", error);
            if (typeof showToast === 'function') showToast("Error loading account data.", "error");
        } finally {
            paneLoader.classList.add('d-none');
            form.classList.remove('d-none');
        }
    }

    fetchUserAccountData();

    // ── STEP 2: Save user data via AJAX ──────────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const accountData = localStorage.getItem('recycleproAccount');
        if (!accountData) return;

        const loggedInUser = JSON.parse(accountData);
        const userId       = loggedInUser.wp_user_id || loggedInUser.id || '';
        const newPass      = document.getElementById('setNewPassword').value;
        const confirmPass  = document.getElementById('setConfirmPassword').value;

        if (newPass !== "" && newPass !== confirmPass) {
            if (typeof showToast === 'function') showToast("Passwords do not match!", "warning");
            else alert("Security Check Alert: Passwords do not match!");
            return;
        }

        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        icon.classList.add('d-none');

        const updateUrl = `https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/user/update/${userId}`;
        // const updateUrl = `https://localhost/bkrecyclepro/wp-json/wp/v2/user/update/1`;

        const payload = {
            first_name:  document.getElementById('setFirstName').value,
            last_name:   document.getElementById('setLastName').value,
            email:       document.getElementById('setEmail').value,
            phone:       document.getElementById('setPhone').value,
            address:     document.getElementById('setAddress').value,
            postal_code: document.getElementById('setPostalCode').value
        };
        if (newPass !== "") payload.password = newPass;

        try {
            const response = await fetch(updateUrl, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(payload)
            });
            const result = await response.json();

            if (response.ok && result.success) {
                if (typeof showToast === 'function') showToast("Profile Settings updated successfully!", "success");
                else alert("Profile Secured! Settings updated successfully.");

                // Sync localStorage
                loggedInUser.first_name = payload.first_name;
                loggedInUser.last_name  = payload.last_name;
                loggedInUser.email      = payload.email;
                localStorage.setItem('recycleproAccount', JSON.stringify(loggedInUser));

                document.getElementById('setNewPassword').value    = "";
                document.getElementById('setConfirmPassword').value = "";
            } else {
                throw new Error(result.message || 'Update request rejected by endpoint backend.');
            }
        } catch (error) {
            console.error("AJAX Submit Exception Error:", error);
            if (typeof showToast === 'function') showToast(error.message || "Failed to update backend records.", "error");
            else alert("Error saving settings data.");
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
        }
    });

});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>