<?php
require_once '../../connection.php';

$number = 1;

$invoice_id = $_GET['invoice_id'];

$details = $database->select('invoice', [
    '[>]customer' => ['customer_id' => 'id'],
    '[>]company_pic' => ['pic_id' => 'id'],
    '[>]invoice_detail' => ['id' => 'invoice_id'],
    '[>]item' => ['invoice_detail.item_id' => 'id'],
    '[><]company' => ['company_id' => 'id'],
], [
    'invoice.id(invoice_id)',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'company_pic.name(pic_name)',
    'company.signature(company_signature)',
    'invoice_detail.id',
    'invoice_detail.unit_price',
    'invoice_detail.quantity',
    'invoice_detail.amount',
    'item.id(item_id)',
    'item.name',
    'company.name(company_name)',
    'company.email(company_email)',
    'company.province(company_province)',
    'company.subdistrict(company_subdistrict)',
    'company.logo(company_logo)',
], [
    'invoice.id' => $invoice_id
]);

$invoice_details = [];

foreach ($details as $detail) {
    $invoice_details[] = $detail;
}

$invoice = $invoice_details[0];

$total_bill = 0;
foreach ($invoice_details as $invoice_detail) {
    $total_bill += $invoice_detail['amount'];
}

function image_to_base64($relative_path)
{
    if (empty($relative_path)) {
        return '';
    }

    $full_path = __DIR__ . '/../../../storage/' . $relative_path;

    if (!file_exists($full_path)) {
        return '';
    }

    $ext = pathinfo($full_path, PATHINFO_EXTENSION);
    $data = base64_encode(file_get_contents($full_path));

    return "data:image/{$ext};base64,{$data}";
}

$logo_src = image_to_base64($invoice['company_logo'] ?? null);
$signature_src = image_to_base64($invoice['company_signature'] ?? null);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .text-primary {
            color: #0d6efd;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        table.grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.grid-table td {
            vertical-align: top;
            border: none;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.items-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        table.items-table td {
            border-bottom: 1px solid #dee2e6;
            padding: 10px;
        }

        table.items-table th.text-right,
        table.items-table td.text-right {
            text-align: right;
        }

        table.items-table th.text-center,
        table.items-table td.text-center {
            text-align: center;
        }

        .total-box {
            width: 45%;
            float: right;
        }

        .total-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-box td {
            padding: 6px 0;
            border: none;
        }

        .total-box .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            margin-top: 5px;
        }

        .clearfix {
            clear: both;
        }

        .signature-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 60px;
        }

        .signature-section td {
            border: none;
            vertical-align: top;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-box .signature-label {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .signature-box .signature-spacer {
            height: 70px;
        }

        .signature-box .signature-image {
            max-height: 70px;
            max-width: 90%;
            display: block;
            margin-left: auto;
            margin-right: 0;
        }

        .signature-box .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
        }

        .signature-box .signature-name {
            font-weight: bold;
            font-size: 13px;
        }

        .signature-box .signature-role {
            color: #6c757d;
            font-size: 11px;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; padding: 10px;">

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <?php if ($logo_src): ?>
                    <img src="<?= $logo_src ?>" style="max-height: 100px; width: auto; margin-bottom: 20px;">
                <?php endif; ?>
                <h2 style="color: #0d6efd; margin: 0; font-size: 22px; font-weight: bold;"><?= $invoice['company_name'] ?></h2>
                <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 12px;">
                    <?= $invoice['company_province'] ?><br>
                    <?= $invoice['company_subdistrict'] ?><br>
                    <?= $invoice['company_email'] ?>
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
                <div style="font-weight: bold;"><?= $invoice['pic_name'] ?></div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <div style="color: #6c757d; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Issue date</div>
                <div style="margin-bottom: 15px;"><?= $invoice['date'] ?></div>

                <div style="color: #6c757d; font-size: 11px; text-transform: uppercase; margin-bottom: 3px;">Due date</div>
                <div><?= $invoice['due_date'] ?></div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="45%">Description</th>
                <th width="15%" class="text-center">Qty</th>
                <th width="15%" class="text-right">Unit price</th>
                <th width="20%" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice_details as $invoice_detail):
                if (!empty($invoice_detail['item_id'])):
                    $amount = $invoice_detail['quantity'] * $invoice_detail['unit_price']; ?>
                    <tr>
                        <td class="text-center"><?= $number++ ?></td>
                        <td><?= $invoice_detail['name'] ?></td>
                        <td class="text-center"><?= $invoice_detail['quantity'] ?></td>
                        <td class="text-right">Rp<?= number_format($invoice_detail['unit_price'], 0, ',', '.') ?></td>
                        <td class="text-right">Rp<?= number_format($invoice_detail['amount'], 0, ',', '.') ?></td>
                    </tr>
            <?php endif;
            endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        <table>
            <tr>
                <td class="grand-total">Total Bill:</td>
                <td class="text-right grand-total">Rp<?= number_format($total_bill, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <table class="signature-section">
        <tr>
            <td style="width: 100%;">&nbsp;</td>
            <td class="signature-box">
                <div class="signature-label">Hormat kami,</div>
                <?php if ($signature_src): ?>
                    <img src="<?= $signature_src ?>" class="signature-image" style="max-height: 100px; width: auto;">
                <?php else: ?>
                    <div class="signature-spacer"></div>
                <?php endif; ?>
                <!-- <div class="signature-name"><?= $invoice['pic_name'] ?></div> -->
                <div class="signature-name"><?= $invoice['company_name'] ?></div>
            </td>
        </tr>
    </table>
</body>

</html>