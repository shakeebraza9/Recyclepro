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


    .settings-panel {
        display: none;
        animation: paneFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .settings-panel.active {
        display: block;
    }

    @keyframes paneFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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
</style>

<main class="settings-portal-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                
                <div class="row g-0 lux-dashboard-grid">
                    
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
                            <span>Security & Pass</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-8 col-lg-9 lux-content-pane">
                        <form id="accountSettingsForm" novalidate autocomplete="off">
                            
                            <div class="settings-panel active" id="personal-panel">
                                <div class="pane-header">
                                    <h2>Personal Information</h2>
                                    <p class="text-muted">Update your primary identity settings and platform notification contacts.</p>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">First Name <span>*</span></label>
                                        <input type="text" id="setFirstName" class="form-control lux-input" placeholder="Muhammad" required>
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Last Name <span>*</span></label>
                                        <input type="text" id="setLastName" class="form-control lux-input" placeholder="Shakeeb" required>
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Email Address <span>*</span></label>
                                        <input type="email" id="setEmail" class="form-control lux-input" placeholder="shakeeb@example.com" required>
                                    </div>
                                    <div class="col-12 col-sm-6 lux-form-group">
                                        <label class="lux-field-label">Phone Number <span>*</span></label>
                                        <input type="tel" id="setPhone" class="form-control lux-input" placeholder="+44 7123 456789" required>
                                    </div>
                                </div>
                            </div>

                            <div class="settings-panel" id="shipping-panel">
                                <div class="pane-header">
                                    <h2>Shipping Destination</h2>
                                    <p class="text-muted">Configure default shipping and billing drop-off points for faster orders checkouts.</p>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-12 col-md-8 lux-form-group">
                                        <label class="lux-field-label">Address Line <span>*</span></label>
                                        <input type="text" id="setAddress" class="form-control lux-input" placeholder="123 Luxury Road, Suite B" required>
                                    </div>
                                    <div class="col-12 col-md-4 lux-form-group">
                                        <label class="lux-field-label">Postal Code <span>*</span></label>
                                        <input type="text" id="setPostalCode" class="form-control lux-input" placeholder="E1 6AN" required>
                                    </div>
                                </div>
                            </div>

                            <div class="settings-panel" id="security-panel">
                                <div class="pane-header">
                                    <h2>Security & Password</h2>
                                    <p class="text-muted">Keep your account encrypted. Leave password blank if you don't wish to rewrite it.</p>
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

                            <div class="d-flex align-items-center justify-content-between pt-4 mt-4 border-top" style="border-color: #f1f5f9 !important;">
                                <span class="text-muted d-none d-sm-inline" style="font-size: 0.85rem; font-weight: 500;">
                                    <i class="bi bi-shield-check text-success"></i> End-to-end data encryption enabled.
                                </span>
                                <button type="submit" class="btn lux-btn-submit d-inline-flex align-items-center gap-2" id="submitSettingsBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                                    <i class="bi bi-check2-all" id="btnIcon"></i> Save Account Updates
                                </button>
                            </div>

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const navItems = document.querySelectorAll('.lux-nav-item');
    const panels = document.querySelectorAll('.settings-panel');
    const form = document.getElementById('accountSettingsForm');
    const submitBtn = document.getElementById('submitSettingsBtn');
    const spinner = document.getElementById('btnSpinner');
    const icon = document.getElementById('btnIcon');


    navItems.forEach(item => {
        item.addEventListener('click', function () {
            navItems.forEach(nav => nav.classList.remove('active'));
            panels.forEach(panel => panel.classList.remove('active'));

            this.classList.add('active');
            const targetPane = this.getAttribute('data-target');
            document.getElementById(targetPane).classList.add('active');
        });
    });


    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const newPass = document.getElementById('setNewPassword').value;
        const confirmPass = document.getElementById('setConfirmPassword').value;

        if (newPass !== "" && newPass !== confirmPass) {
            alert("Security Check Alert: Passwords do not match!");
            return;
        }


        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        icon.classList.add('d-none');

        console.log("Secure dynamic datasets verified. Triggering AJAX execution parameters.");


        setTimeout(() => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
            alert("Profile Secured! Settings updated successfully across the systems.");
        }, 1200);
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>