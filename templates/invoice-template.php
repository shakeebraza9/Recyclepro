<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 40px 50px; }
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2b2b2b; font-size: 13px; line-height: 1.5; }
    .invoice-header-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
    

    .logo-container {
        width: 150px; 
        vertical-align: middle;
    }
    .logo-img {
        width: 58%;
        height: auto;
        display: block;
    }
    
    .brand-subtitle { font-size: 11px; color: #718096; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; }
    .badge-paid { background-color: #13564f; color: white; font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 4px; text-transform: uppercase; display: inline-block; }
    .meta-label { font-size: 10px; color: #a0aec0; text-transform: uppercase; text-align: right; }
    .meta-value { font-size: 14px; font-weight: bold; color: #2d3748; text-align: right; margin-bottom: 5px; }

    .address-container-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
    .address-column { width: 50%; vertical-align: top; }
    .address-title { font-size: 11px; font-weight: bold; color: #a0aec0; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px; width: 90%; }
    .address-text { font-size: 13px; color: #2d3748; line-height: 1.6; }
    .address-email { color: #718096; font-size: 12px; margin-top: 5px; }

    .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .items-table th { background-color: #f7fafc; color: #718096; font-size: 11px; font-weight: bold; text-transform: uppercase; padding: 12px 10px; border-bottom: 2px solid #edf2f7; }
    .items-table td { padding: 15px 10px; border-bottom: 1px solid #edf2f7; vertical-align: top; }
    .item-name { font-weight: bold; color: #1a202c; font-size: 13px; }
    
    .totals-container-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .summary-label { font-size: 11px; color: #718096; text-transform: uppercase; padding: 7px 10px; text-align: right; width: 80%; }
    .summary-value { font-size: 13px; color: #2d3748; font-weight: 600; padding: 7px 10px; text-align: right; width: 20%; }
    .grand-total-row td { padding-top: 20px; border-top: 2px solid #2d3748; }
    .grand-total-value { font-size: 22px; font-weight: bold; color: #13564f; }
</style>
</head>
<body>

<table class="invoice-header-table">
    <tr>
        <td>
            <div class="logo-container">
        <?php 
            $logo_base64 = '';
            $local_logo_path = $_SERVER['DOCUMENT_ROOT'] . '/shop/img/rplogo.png';

            if (file_exists($local_logo_path)) {
                $im = imagecreatefrompng($local_logo_path);
                if ($im) {
                    imagealphablending($im, false);
                    imagesavealpha($im, true);
                    
                    imagefilter($im, IMG_FILTER_NEGATE); 
                    ob_start();
                    imagepng($im);
                    $image_data = ob_get_clean();
                    imagedestroy($im);
                    
                    $logo_base64 = 'data:image/png;base64,' . base64_encode($image_data);
                }
            } 
            ?>

            <?php if (!empty($logo_base64)): ?>
                <img src="<?php echo $logo_base64; ?>" alt="Recycle Pro Logo" class="logo-img">
            <?php else: ?>
                <div style="font-size: 26px; font-weight: bold; color: #13564f;">RECYCLE PRO</div>
            <?php endif; ?>
            </div>
        </td>
        <td style="text-align: right; vertical-align: top;">
            <?php if(in_array(strtolower($status), ['processing', 'completed', 'paid'])): ?>
                <div class="badge-paid">Paid</div>
            <?php endif; ?>
            <div class="meta-label" style="margin-top: 15px;">Date Issued</div>
            <div class="meta-value"><?= $date ?></div>
            <div class="meta-label">Invoice No</div>
            <div class="meta-value">#<?= $invoice_no ?></div>
        </td>
    </tr>
</table>

<table class="address-container-table">
    <tr>
        <td class="address-column">
            <div class="address-title">From</div>
            <div class="address-text">
                <strong>Recycle pro.</strong>
              
                <div class="address-email">Order@recyclepro.co.uk</div>
            </div>
        </td>
        <td class="address-column">
            <div class="address-title">Bill To</div>
            <div class="address-text">
                <strong><?= htmlspecialchars($customer_name) ?></strong>
                <?= htmlspecialchars($billing_address) ?><br>
                <?= htmlspecialchars($billing_location) ?><br>
                <?= htmlspecialchars($billing['country']) ?><br>
                <div class="address-email"><?= htmlspecialchars($customer_email) ?></div>
                <div class="address-email"><?= htmlspecialchars($customer_phone) ?></div>
            </div>
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th style="text-align: left; width: 50%;">Item Description</th>
            <th style="text-align: center; width: 10%;">Qty</th>
            <th style="text-align: right; width: 20%;">Unit Price</th>
            <th style="text-align: right; width: 20%;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $item): ?>
        <tr>
            <td>
                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
            </td>
            <td style="text-align: center; color: #4a5568;">
                <?= sprintf("%02d", $item['qty']) ?>
            </td>
            <td style="text-align: right; color: #4a5568;">
                <?= $currency . number_format($item['price'], 2) ?>
            </td>
            <td style="text-align: right; font-weight: bold; color: #1a202c;">
                <?= $currency . number_format($item['qty'] * $item['price'], 2) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="totals-container-table">
    <tr>
        <td class="summary-label">Subtotal</td>
        <td class="summary-value"><?= $currency . number_format($subtotal, 2) ?></td>
    </tr>
    <?php if($shipping_cost > 0): ?>
    <tr>
        <td class="summary-label">Shipping</td>
        <td class="summary-value"><?= $currency . number_format($shipping_cost, 2) ?></td>
    </tr>
    <?php endif; ?>
    <tr class="grand-total-row">
        <td class="summary-label" style="font-weight: bold; color: #1a202c;">Total Amount</td>
        <td class="summary-value grand-total-value"><?= $currency . number_format($grand_total, 2) ?></td>
    </tr>
</table>

<div style="margin-top: 50px; text-align: center; font-size: 11px; color: #a0aec0;">
    Payment Method: <?= strtoupper($order['payment_method'] ?? 'N/A') ?> | Thank you for your business!
</div>

</body>
</html>