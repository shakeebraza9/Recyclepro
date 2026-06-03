<?php
$config = require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Recycle Pro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: #f5f8fa;
    color: #0b0c10;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}


.header {
    background-color: #ffffff;
    border-bottom: 1px solid #e1e8ed;
    padding: 14px 0;
    display: flex;
    align-items: center;
}

.header .container {
    display: flex;
    align-items: center;
}

.header-logo-link {
    display: inline-block;
    line-height: 0;
}


.logo-img {
    max-height: 50px;
    width: auto;
    display: block;
    object-fit: contain;
    
    
    filter: brightness(0.1) contrast(1.2); 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}


.header-logo-link:hover .logo-img {
    transform: scale(1.05);
    filter: brightness(1) drop-shadow(0px 4px 10px rgba(19, 86, 79, 0.15));
}


.main-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 60px 24px;
    max-width: 900px;
    margin: 0 auto;
    width: 100%;
}

.card-container {
    width: 100%;
    text-align: left;
}


.badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #13564f ;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 24px;
}

.success-icon {
    width: 20px;
    height: 20px;
}

.main-title {
    font-size: 42px;
    font-weight: 700;
    letter-spacing: -0.5px;
    margin-bottom: 28px;
    color: #000000;
}

.order-reference-box {
    margin-bottom: 28px;
}

.order-reference-box .label {
    display: block;
    font-size: 14px;
    color: #657786;
    margin-bottom: 6px;
}

.reference-number {
    display: inline-block;
    background-color: #e1e8ed;
    padding: 6px 12px;
    font-family: monospace;
    font-size: 15px;
    font-weight: 600;
    border-radius: 4px;
    color: #14171a;
}

.description {
    font-size: 16px;
    line-height: 1.6;
    color: #333333;
    max-width: 550px;
    margin-bottom: 40px;
}

.description strong {
    font-weight: 600;
    color: #000000;
}


.button-group {
    display: flex;
    gap: 16px;
    margin-bottom: 48px;
    flex-wrap: wrap;
}


.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 14px 28px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-primary {
    background-color: #13564f ;
    color: #ffffff;
    border: 1px solid #13564f ;
}

.btn-primary:hover {
    background-color: #13564fa9;
    border-color: #13564f;
}

.arrow-icon {
    width: 16px;
    height: 16px;
}

.btn-secondary {
    background-color: #ffffff;
    color: #000000;
    border: 1px solid #ccd6dd;
}

.btn-secondary:hover {
    background-color: #f5f8fa;
    border-color: #aab8c2;
}


.divider {
    border: 0;
    height: 1px;
    background-color: #e1e8ed;
    margin-bottom: 24px;
}


.footer-info {
    display: flex;
    gap: 80px;
    flex-wrap: wrap;
}

.info-block {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 11px;
    font-weight: 600;
    color: #8899a6;
    letter-spacing: 0.8px;
}

.info-value {
    font-size: 15px;
    font-weight: 500;
    color: #14171a;
}

.info-value a {
    color: #14171a;
    text-decoration: none;
}

.info-value a:hover {
    text-decoration: underline;
}


.site-footer {
    background-color: #ffffff;
    border-top: 1px solid #e1e8ed;
    padding: 30px 0;
    margin-top: auto;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.footer-brand {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.footer-logo-link {
    display: inline-block;
    line-height: 0;
}

.footer-logo-link .logo-img {
    max-height: 35px; 
}

.copyright {
    font-size: 13px;
    color: #657786;
}

.footer-links {
    display: flex;
    gap: 24px;
}

.footer-link {
    font-size: 14px;
    color: #657786;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.footer-link:hover {
    color: #13564f;
}


@media (max-width: 600px) {
    .main-title {
        font-size: 32px;
    }
    .button-group {
        flex-direction: column;
    }
    .btn {
        width: 100%;
    }
    .footer-info {
        gap: 32px;
    }
    .footer-content {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    .footer-links {
        justify-content: center;
        gap: 20px;
    }
}
</style>
<body>


    <header class="header">
        <div class="container">
            <a href="/shop/" class="header-logo-link">
                <img class="logo-img img-fluid" src="/shop/img/rplogo.png" alt="Recycle Pro Logo">
            </a>
        </div>
    </header>

    <main class="main-content">
        <div class="card-container">
            
            <div class="badge">
                <svg class="success-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span>ORDER CONFIRMED</span>
            </div>

            <h1 class="main-title">Thank you for your order!</h1>

            <div class="order-reference-box">
                <span class="label">Order reference</span>
                <div class="reference-number">#ORD-2024-001</div>
            </div>

            <p class="description">
                        Your order has been successfully placed. We’ve sent a detailed confirmation email to
                        <strong>Order@Recyclepro.co.uk</strong> with your invoice and product details.
                        We’re now processing your order with precision. You’ll receive tracking updates as soon as your package is dispatched
                    </p>

            <div class="button-group">
                <a href="<?php echo $config['BASE_URL']; ?>/user/settings" class="btn btn-primary">
                    <span>Return To Your Dashboard</span>
                    <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <a href="<?php echo $config['BASE_URL']; ?>" class="btn btn-secondary">Continue Shopping</a>
            </div>

            <hr class="divider">

            <div class="footer-info">
                <div class="info-block">
                    <span class="info-label">ESTIMATE DELIVERY</span>
                    <p class="info-value">2 to 5 Days</p>
                </div>
                <div class="info-block">
                    <span class="info-label">SUPPORT</span>
                    <p class="info-value"><a href="mailto:orders@recyclepro.co.uk">orders@recyclepro.co.uk</a></p>
                </div>
            </div>

        </div>
    </main>


    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <a href="/shop/" class="footer-logo-link">
                        <img class="logo-img img-fluid" src="/shop/img/rplogo.png" alt="Recycle Pro Logo">
                    </a>
                    <span class="copyright">&copy; <?php echo date('Y'); ?> RecyclePro. All rights reserved.</span>
                </div>
                <div class="footer-links">
                    <a href="<?php echo $config['BASE_URL']; ?>/returns-policy" class="footer-link">Returns Policy</a>
                    <a href="<?php echo $config['BASE_URL']; ?>/privacy-policy" class="footer-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>