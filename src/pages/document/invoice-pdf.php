<?php

require_once '../../../vendor/autoload.php';
require_once '../../connection.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$invoice_id = $_GET['invoice_id'];

$details = $database->select(
    'invoice',
    [
        '[>]customer' => ['customer_id' => 'id'],
        '[>]user' => ['user_id' => 'id'],
        '[>]invoice_detail' => ['id' => 'invoice_id'],
        '[>]item' => ['invoice_detail.item_id' => 'id'],
    ],
    [
        'invoice.id(invoice_id)',
        'invoice.invoice_code',
        'invoice.date',
        'invoice.due_date',
        'customer.name(customer_name)',
        'user.name(user_name)',
        'invoice_detail.id',
        'invoice_detail.quantity',
        'invoice_detail.unit_price',
        'invoice_detail.amount',
        'item.id(item_id)',
        'item.name'
    ],
    [
        'invoice.id' => $invoice_id
    ]
);

$invoice_details = $details;
$invoice = $invoice_details[0];

$total_bill = 0;
foreach ($invoice_details as $detail) {
    $total_bill += $detail['amount'];
}

ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; padding: 10px;">

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <h2 style="color: #0d6efd; margin: 0; font-size: 22px; font-weight: bold;">Red Hat, Inc.</h2>
                <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 12px;">
                    100 East Davie Street<br>
                    Raleigh, NC 27601<br>
                    redhat@example.com
                </p>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <h1 style="margin: 0; font-size: 26px; font-weight: normal; color: #222;">Invoice</h1>
                <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 14px;">
                    <strong>#</strong><?= $invoice['invoice_code'] ?>
                </p>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="color: #6c757d; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Billed to</div>
                <div style="font-weight: bold; font-size: 14px; margin-bottom: 15px;"><?= $invoice['customer_name'] ?></div>

                <div style="color: #6c757d; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Handled by</div>
                <div style="font-weight: bold;"><?= $invoice['user_name'] ?></div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <div style="color: #6c757d; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Issue date</div>
                <div style="margin-bottom: 15px;"><?= $invoice['date'] ?></div>

                <div style="color: #6c757d; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Due date</div>
                <div><?= $invoice['due_date'] ?></div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="border-bottom: 2px solid #dee2e6; text-align: left; padding: 10px; font-weight: bold;">Description</th>
                <th style="border-bottom: 2px solid #dee2e6; text-align: right; padding: 10px; font-weight: bold; width: 10%;">Qty</th>
                <th style="border-bottom: 2px solid #dee2e6; text-align: right; padding: 10px; font-weight: bold; width: 20%;">Unit price</th>
                <th style="border-bottom: 2px solid #dee2e6; text-align: right; padding: 10px; font-weight: bold; width: 20%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice_details as $invoice_detail): ?>
                <?php if (!empty($invoice_detail['item_id'])): ?>
                    <tr>
                        <td style="border-bottom: 1px solid #dee2e6; padding: 10px; font-weight: bold;"><?= $invoice_detail['name'] ?></td>
                        <td style="border-bottom: 1px solid #dee2e6; padding: 10px; text-align: right;"><?= $invoice_detail['quantity'] ?></td>
                        <td style="border-bottom: 1px solid #dee2e6; padding: 10px; text-align: right;">Rp<?= number_format($invoice_detail['unit_price'], 0, ',', '.') ?></td>
                        <td style="border-bottom: 1px solid #dee2e6; padding: 10px; text-align: right;">Rp<?= number_format($invoice_detail['amount'], 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; font-weight: bold; font-size: 13px; border-top: 1px solid #333;">Total bill</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: bold; font-size: 15px; color: #0d6efd; border-top: 1px solid #333;">
                            Rp<?= number_format($total_bill, 0, ',', '.') ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="border-top: 1px solid #dee2e6; padding-top: 15px; font-size: 11px; color: #6c757d;">
        Thanks for your business. Payment is due within 14 days. If you have any questions
        about this invoice, please contact
        <a href="mailto:billing@example.com" style="color: #0d6efd; text-decoration: none;">billing@example.com</a>.
    </div>

</body>

</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream(
    'Invoice-' . $invoice['invoice_code'] . '.pdf',
    [
        'Attachment' => false
    ]
);
