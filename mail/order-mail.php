<?php

// 1. Necessary Dependencies Include
require '../vendor/autoload.php';
require_once '../includes/smtp.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/* ==========================================================================
   STEP 1: STATIC INVOICE PDF GENERATION (Dompdf)
   ========================================================================== */

$invoice_no = 'INV-1001';

// Direct Static HTML Template for PDF
$invoice_html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #1d2939; padding: 20px; }
        .header { border-bottom: 2px solid #13564f; padding-bottom: 10px; margin-bottom: 30px; }
        .title { color: #13564f; font-size: 28px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; color: #475467; text-align: left; padding: 10px; font-size: 12px; }
        td { padding: 12px 10px; border-bottom: 1px solid #eaecf0; font-size: 14px; }
        .total-box { text-align: right; margin-top: 30px; font-size: 18px; color: #13564f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <span class="title">OFFICIAL INVOICE</span>
        <table style="margin-top: 5px;">
            <tr>
                <td><strong>Invoice:</strong> #' . $invoice_no . '</td>
                <td align="right"><strong>Date:</strong> ' . date('d M Y') . '</td>
            </tr>
        </table>
    </div>

    <p><strong>Customer Name:</strong> Muhammad Shakeeb</p>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Laptop</td>
                <td align="center">1</td>
                <td align="right">£500.00</td>
                <td align="right">£500.00</td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td align="center">2</td>
                <td align="right">£300.00</td>
                <td align="right">£600.00</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        Total Amount: £1,100.00
    </div>
</body>
</html>
';

// Render PDF via Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($invoice_html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Server par dynamic folders create karke file save karna
$dir_path = __DIR__ . '/invoices/generated/';
if (!file_exists($dir_path)) {
    mkdir($dir_path, 0755, true);
}

$pdf_file_path = $dir_path . $invoice_no . '.pdf';
file_put_contents($pdf_file_path, $dompdf->output());


/* ==========================================================================
   STEP 2: STATIC EMAIL TEMPLATE BODY
   ========================================================================== */

$email_body = '
<div style="background-color: #f4f6f8; padding: 30px 15px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-collapse: collapse;">
        
        <tr>
            <td style="background: #13564f; padding: 4px; text-align: center;"></td>
        </tr>

        <tr>
            <td style="padding: 30px 40px 20px 40px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            <span style="color: #13564f; text-transform: uppercase; font-size: 11px; font-weight: 700; letter-spacing: 1px; display: block; margin-bottom: 4px;">CONFIRMATION</span>
                            <h1 style="color: #1d2939; font-size: 26px; font-weight: 800; margin: 0; padding: 0; line-height: 1.2;">Thank you for your order</h1>
                            <p style="color: #475467; font-size: 14px; margin: 8px 0 0 0; line-height: 1.5;">Hi Muhammad Shakeeb, we\'ve received your order and are preparing it for shipment.</p>
                        </td>
                        <td align="right" valign="top" style="white-space: nowrap;">
                            <span style="font-size: 11px; color: #667085; text-transform: uppercase; font-weight: 600; display: block;">ORDER NUMBER</span>
                            <strong style="font-size: 15px; color: #1d2939; font-weight: 700; display: block; margin-top: 2px;">#' . $invoice_no . '</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding: 0 40px 20px 40px;">
                <table width="100%" cellpadding="15" cellspacing="0" style="background: #f8f9fa; border-radius: 12px; border-collapse: collapse;">
                    <tr>
                        <td width="50%" valign="top" style="padding: 15px;">
                            <span style="font-size: 11px; color: #667085; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 6px;">CURRENT STATUS</span>
                            <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="background: #e6f4f2; color: #13564f; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.3px;">
                                        ✓ Confirmed
                                    </td>
                                </tr>
                            </table>
                            <span style="font-size: 11px; color: #667085; display: block; margin-top: 10px;">Order Date:</span>
                            <strong style="font-size: 13px; color: #1d2939; display: block; margin-top: 2px;">' . date('d M Y') . '</strong>
                        </td>
                        <td width="50%" valign="top" style="padding: 15px; border-left: 1px solid #eaecf0;">
                            <span style="font-size: 11px; color: #667085; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 4px;">SHIPPING TO</span>
                            <strong style="font-size: 14px; color: #1d2939; display: block; font-weight: 700;">Muhammad Shakeeb</strong>
                            <p style="font-size: 13px; color: #475467; margin: 4px 0 0 0; line-height: 1.4;">
                                123 Tech Lane<br>
                                Austin, TX 78701<br>
                                United States
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px 40px 20px 40px;">
                <h3 style="color: #1d2939; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">ORDER SUMMARY</h3>
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 1px solid #eaecf0;">
                            <th align="left" style="padding: 10px 12px; font-size: 11px; font-weight: 700; color: #475467; text-transform: uppercase;">Item Description</th>
                            <th align="center" style="padding: 10px 12px; font-size: 11px; font-weight: 700; color: #475467; text-transform: uppercase; width: 10%;">Qty</th>
                            <th align="right" style="padding: 10px 12px; font-size: 11px; font-weight: 700; color: #475467; text-transform: uppercase; width: 20%;">Price</th>
                            <th align="right" style="padding: 10px 12px; font-size: 11px; font-weight: 700; color: #475467; text-transform: uppercase; width: 20%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #f2f4f7;">
                            <td style="padding: 14px 12px;">
                                <strong style="font-size: 14px; color: #1d2939; display: block; font-weight: 600;">Laptop</strong>
                            </td>
                            <td align="center" style="padding: 14px 12px; font-size: 13px; color: #344054;">1</td>
                            <td align="right" style="padding: 14px 12px; font-size: 13px; color: #344054;">£500.00</td>
                            <td align="right" style="padding: 14px 12px; font-size: 13px; font-weight: 600; color: #1d2939;">£500.00</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f2f4f7;">
                            <td style="padding: 14px 12px;">
                                <strong style="font-size: 14px; color: #1d2939; display: block; font-weight: 600;">Mobile</strong>
                            </td>
                            <td align="center" style="padding: 14px 12px; font-size: 13px; color: #344054;">2</td>
                            <td align="right" style="padding: 14px 12px; font-size: 13px; color: #344054;">£300.00</td>
                            <td align="right" style="padding: 14px 12px; font-size: 13px; font-weight: 600; color: #1d2939;">£600.00</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding: 0 40px 30px 40px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 16px 0 0 0; font-size: 14px; font-weight: 700; color: #1d2939; text-transform: uppercase; letter-spacing: 0.5px;">Total Amount</td>
                                    <td align="right" style="padding: 16px 0 0 0; font-size: 22px; font-weight: 800; color: #13564f;">£1,100.00</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="background: #f8f9fa; padding: 24px 40px; text-align: center;">
                <p style="font-size: 12px; color: #667085; margin: 0 0 8px 0; line-height: 1.4;">
                    Please find your official purchase receipt attached as a PDF file with this email.
                </p>
                <p style="font-size: 12px; font-weight: 600; color: #475467; margin: 0;">
                    © ' . date('Y') . ' BK Recycle. All Rights Reserved.
                </p>
            </td>
        </tr>

    </table>
</div>
';


/* ==========================================================================
   STEP 3: EXECUTE MAIL WITH ATTACHMENT
   ========================================================================== */

$response = sendMail(
    'arsalan.ahmed@knizaam.com',
    'Order Confirmation #' . $invoice_no,
    $email_body,
    $pdf_file_path
);

echo "<pre>";
print_r($response);
echo "</pre>";