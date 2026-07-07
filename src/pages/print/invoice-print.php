<?php
require_once '../../connection.php';

$invoice_id = $_GET['invoice_id'];

$details = $database->select('invoice', [
    '[>]customer' => ['customer_id' => 'id'],
    '[>]user' => ['user_id' => 'id'],
    '[>]invoice_detail' => ['id' => 'invoice_id'],
    '[>]item' => ['invoice_detail.item_id' => 'id'],
], [
    'invoice.id(invoice_id)',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'user.name(user_name)',
    'invoice_detail.id',
    'invoice_detail.unit_price',
    'invoice_detail.quantity',
    'invoice_detail.amount',
    'item.id(item_id)',
    'item.name'
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
?>

<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            background: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-body {
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-5">
        <?php include '../invoice-detail/invoice-content.php'; ?>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };

        window.onafterprint = function() {
            window.location.href = "../invoice-detail/detail.php?invoice_id=<?= $invoice_id ?>";
        };
    </script>
</body>

</html>