<?php
$pageTitle = 'Your Orders';
include __DIR__ . '/../../includes/header.php';
?>

<style>
    :root {
        --lux-green: #044339;       /* Recycle Pro Dark Bottle Green */
        --lux-emerald: #0b6e5f;     /* Vibrant Accent Green */
        --lux-bg: #f8fafc;          /* Fresh crisp lighter canvas background */
        --lux-border: #e2e8f0;
        --lux-text-dark: #0f172a;
        --lux-text-muted: #64748b;
    }

    .orders-portal-section {
        padding: 60px 0 100px 0;
        background-color: var(--lux-bg);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Top Stats Counters Row Layout */
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
    }

    /* Master Container Card */
    .lux-master-orders-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 15px 40px rgba(4, 67, 57, 0.02);
    }

    /* Centered Filter Layout Navigation */
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
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .filter-pill.active {
        background-color: var(--lux-green);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(4, 67, 57, 0.15);
    }

    /* Standalone Order Card Configuration */
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

    /* Header Meta Grid inside Cards */
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

    /* Standardized Status Badges */
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
    .status-delivered { background-color: #f0fdf4; color: #166534; }

    /* Media Display Items */
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

    /* Price tag styling */
    .row-price-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--lux-green);
    }

    /* Call to action generic buttons */
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

    @media(max-width: 768px) {
        .responsive-center-align { text-align: left !important; margin-top: 15px; }
        .action-buttons-wrapper { justify-content: flex-start !important; margin-top: 15px; }
    }
</style>

<main class="orders-portal-section">
    <div class="container">
        
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="stat-counter-card">
                    <div class="stat-icon-wrapper" style="background-color: rgba(4, 67, 57, 0.05); color: var(--lux-green);">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Placed</div>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.3rem; color: var(--lux-text-dark);">02 Orders</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="stat-counter-card">
                    <div class="stat-icon-wrapper" style="background-color: #fffbeb; color: #b45309;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Active Transit</div>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.3rem; color: var(--lux-text-dark);">01 Active</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="lux-master-orders-card">
                    
                    <div class="orders-navigation-row">
                        <div>
                            <h3 class="fw-bold mb-1" style="font-size: 1.35rem; color: var(--lux-text-dark);">Order History</h3>
                            <p class="text-muted small mb-0">Manage statements, parameters, and live logistical streams.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="filter-pill active" data-filter="all">All Logs</button>
                            <button class="filter-pill" data-filter="ongoing">Ongoing</button>
                            <button class="filter-pill" data-filter="completed">Completed</button>
                        </div>
                    </div>

                    <div id="standaloneListingContainer">
                        
                        <div class="standalone-order-row" data-order-type="ongoing">
                            <div class="meta-top-strip">
                                <div class="order-reference-id">#RP-94820 <span class="ms-2">Ordered on May 28, 2026</span></div>
                                <span class="status-badge status-processing"><i class="bi bi-arrow-repeat spin"></i> Processing</span>
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
                                <div class="col-6 col-md-2 responsive-center-align" style="text-align: center;">
                                    <div class="text-muted small">Amount</div>
                                    <div class="row-price-value">£549.00</div>
                                </div>
                                <div class="col-6 col-md-4 text-end action-buttons-wrapper d-flex gap-2 justify-content-end">
                                    <button class="btn lux-btn-action-outline"><i class="bi bi-geo-alt"></i> Track</button>
                                    <button class="btn lux-btn-action-outline"><i class="bi bi-receipt"></i> Invoice</button>
                                </div>
                            </div>
                        </div>

                        <div class="standalone-order-row" data-order-type="completed">
                            <div class="meta-top-strip">
                                <div class="order-reference-id">#RP-81204 <span class="ms-2">Ordered on April 14, 2026</span></div>
                                <span class="status-badge status-delivered"><i class="bi bi-check2-circle"></i> Delivered</span>
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
                                <div class="col-6 col-md-2 responsive-center-align" style="text-align: center;">
                                    <div class="text-muted small">Amount</div>
                                    <div class="row-price-value">£610.00</div>
                                </div>
                                <div class="col-6 col-md-4 text-end action-buttons-wrapper d-flex gap-2 justify-content-end">
                                    <button class="btn lux-btn-action-outline"><i class="bi bi-arrow-counterclockwise"></i> Reorder</button>
                                    <button class="btn lux-btn-action-outline"><i class="bi bi-receipt"></i> Invoice</button>
                                </div>
                            </div>
                        </div>

                    </div> </div>
            </div>
        </div>

    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const filterPills = document.querySelectorAll('.filter-pill');
    const orderRows = document.querySelectorAll('.standalone-order-row');

    filterPills.forEach(pill => {
        pill.addEventListener('click', function () {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const selectedFilter = this.getAttribute('data-filter');

            orderRows.forEach(row => {
                const targetType = row.getAttribute('data-order-type');
                
                if (selectedFilter === 'all') {
                    row.style.display = 'block';
                } else if (targetType === selectedFilter) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>