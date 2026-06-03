<?php
ob_start();
// Dynamic data fallbacks just in case
$customer_name = $customer_name ?? 'Alex Rivera';
$invoice_no = $invoice_no ?? 'INV-1024';
$date = $date ?? 'October 24, 2026';
$subtotal = $subtotal ?? 1687.00;
$shipping = $shipping ?? 15.00;
$tax = $tax ?? 137.20;
$grand_total = $grand_total ?? 1839.20;
$status = $status ?? 'PAID';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page {
        margin: 40px 50px;
    }
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #2b2b2b;
        font-size: 13px;
        line-height: 1.5;
    }
    
    /* Top Header Meta */
    .invoice-header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }
    .brand-title {
        font-size: 26px;
        font-weight: bold;
        color: #13564f;
        letter-spacing: 0.5px;
        margin: 0;
        text-transform: uppercase;
    }
    .brand-subtitle {
        font-size: 11px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }
    .badge-paid {
        background-color: #13564f;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 4px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        display: inline-block;
        text-align: center;
    }
    .meta-label {
        font-size: 10px;
        color: #a0aec0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: right;
    }
    .meta-value {
        font-size: 14px;
        font-weight: bold;
        color: #2d3748;
        text-align: right;
        margin-top: 2px;
    }

    /* Addresses block */
    .address-container-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }
    .address-column {
        width: 50%;
        vertical-align: top;
    }
    .address-title {
        font-size: 11px;
        font-weight: bold;
        color: #a0aec0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 6px;
        margin-bottom: 10px;
        width: 90%;
    }
    .address-title.bill-to {
        width: 100%;
    }
    .address-text {
        font-size: 13px;
        color: #2d3748;
        line-height: 1.6;
    }
    .address-text strong {
        color: #1a202c;
        display: block;
        margin-bottom: 2px;
    }
    .address-email {
        color: #718096;
        font-size: 12px;
        margin-top: 5px;
    }

    /* Items Table Layout */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .items-table th {
        background-color: #f7fafc;
        color: #718096;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 10px;
        border-bottom: 2px solid #edf2f7;
    }
    .items-table td {
        padding: 15px 10px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: top;
    }
    .item-name {
        font-weight: bold;
        color: #1a202c;
        font-size: 13px;
    }
    .item-desc {
        font-size: 11px;
        color: #718096;
        margin-top: 3px;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }

    /* Summary Totals Block */
    .totals-container-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .summary-label {
        font-size: 11px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 7px 10px;
        text-align: right;
        width: 80%;
    }
    .summary-value {
        font-size: 13px;
        color: #2d3748;
        font-weight: 600;
        padding: 7px 10px;
        text-align: right;
        width: 20%;
    }
    .grand-total-row td {
        padding-top: 25px;
        padding-bottom: 15px;
        border-top: 2px solid #2d3748;
    }
    .grand-total-label {
        font-size: 13px;
        font-weight: bold;
        color: #1a202c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .grand-total-value {
        font-size: 22px;
        font-weight: bold;
        color: #13564f;
    }

    /* Bottom Warranty Note */
    .footer-note {
        margin-top: 60px;
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
    }
    .note-title {
        font-size: 11px;
        font-weight: bold;
        color: #13564f;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .note-text {
        font-size: 11px;
        color: #718096;
        line-height: 1.6;
        text-align: justify;
    }
</style>
</head>
<body>

<table class="invoice-header-table">
    <tr>
        <td>
            <div class="brand-title">BK Recycle</div>
            <div class="brand-subtitle">Technical Hardware Specialists</div>
        </td>
        <td style="text-align: right; vertical-align: top;">
            <?php if(strtoupper($status) === 'PAID'): ?>
                <div class="badge-paid">Paid</div>
            <?php endif; ?>
            <div class="meta-label" style="margin-top: 15px;">Date Issued</div>
            <div class="meta-value"><?= $date ?></div>
            <div class="meta-label" style="margin-top: 5px;">Invoice No</div>
            <div class="meta-value">#<?= $invoice_no ?></div>
        </td>
    </tr>
</table>

<table class="address-container-table">
    <tr>
        <td class="address-column">
            <div class="address-title">From</div>
            <div class="address-text">
                <strong>BK Recycle Inc.</strong>
                Tech Hub<br>
                San Francisco, CA 94103
                <div class="address-email">support@recyclepro.co.uk</div>
            </div>
        </td>
        <td class="address-column">
            <div class="address-title bill-to">Bill To</div>
            <div class="address-text">
                <strong><?= $customer_name ?></strong>
                123 Tech Lane<br>
                Austin, TX 78701
                <div class="address-email"><?= $customer_email ?? 'customer@example.com' ?></div>
            </div>
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th style="text-align: left; width: 50%;">Item Description</th>
            <th class="text-center" style="width: 10%;">Qty</th>
            <th class="text-right" style="width: 20%;">Unit Price</th>
            <th class="text-right" style="width: 20%;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $item): ?>
        <tr>
            <td>
                <div class="item-name"><?= $item['name'] ?></div>
                <?php if(!empty($item['description'])): ?>
                    <div class="item-desc"><?= $item['description'] ?></div>
                <?php endif; ?>
            </td>
            <td class="text-center" style="font-weight: 600; color: #4a5568;">
                <?= sprintf("%02d", $item['qty']) ?>
            </td>
            <td class="text-right" style="color: #4a5568;">
                $<?= number_format($item['price'], 2) ?>
            </td>
            <td class="text-right" style="font-weight: bold; color: #1a202c;">
                $<?= number_format($item['qty'] * $item['price'], 2) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="totals-container-table">
    <tr>
        <td class="summary-label">Subtotal</td>
        <td class="summary-value">$<?= number_format($subtotal, 2) ?></td>
    </tr>
    <tr>
        <td class="summary-label">Shipping</td>
        <td class="summary-value">$<?= number_format($shipping, 2) ?></td>
    </tr>
    <tr>
        <td class="summary-label">Tax (8%)</td>
        <td class="summary-value">$<?= number_format($tax, 2) ?></td>
    </tr>
    <tr class="grand-total-row">
        <td class="summary-label grand-total-label">Total Amount</td>
        <td class="summary-value grand-total-value">$<?= number_format($grand_total, 2) ?></td>
    </tr>
</table>

<div class="footer-note">
    <div class="note-title">Technical Warranty Note</div>
    <div class="note-text">
        All hardware components are covered by a 12-month standard limited warranty. Serial 
        numbers for recorded units are tracked in our centralized database for automated support 
        verification. Please retain this invoice as proof of purchase for all technical service requests.
    </div>
</div>

</body>
</html>
<?php
return ob_get_clean();