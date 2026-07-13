<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$invoice_id = $_GET['invoice_id'];

$details = $database->select('invoice', [
    '[>]customer' => ['customer_id' => 'id'],
    '[>]company_pic' => ['pic_id' => 'id'],
    '[>]invoice_detail' => ['id' => 'invoice_id'],
    '[>]item' => ['invoice_detail.item_id' => 'id'],
], [
    'invoice.id(invoice_id)',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'company_pic.name(pic_name)',
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="mb-3">
                    <a href="../invoice/invoice.php" class="text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Invoices
                    </a>
                </div>

                <div class="app-content">
                    <div class="conntainer-fluid">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

                            <!-- Page Title -->
                            <div>
                                <h3 class="fw-bold h4 m-0 text-white">Invoice Details</h3>
                                <p class="text-muted small m-0">
                                    View and manage invoice information, billing details, and payment records
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-wrap justify-content-lg-end gap-2 d-print-none">
                                <a href="../document/invoice-print.php?invoice_id=<?= $invoice_id ?>"
                                    class="btn btn-outline-secondary">
                                    <i class="bi bi-printer me-1"></i>
                                    Print
                                </a>
                                <a href="../document/invoice-pdf.php?invoice_id=<?= $invoice_id ?>"
                                    class="btn btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>
                                    Download PDF
                                </a>
                            </div>
                        </div>

                        <?php include 'invoice-content.php'; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>