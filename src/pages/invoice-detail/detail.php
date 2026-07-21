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
    '[><]company' => ['company_id' => 'id'],
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details</title>
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
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <a href="../invoice/invoice.php" class="text-decoration-none small">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Invoices
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../invoice/invoice.php">Invoices Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice Details</li>
                        </ol>
                    </div>
                </div>

                <div class="app-content">
                    <div class="conntainer-fluid">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                            <div>
                                <h3 class="fw-bold h4 m-0 text-white">Invoice Details</h3>
                                <!-- <p class="text-muted small m-0">
                                    View and manage invoice information, billing details, and payment records
                                </p> -->
                            </div>

                            <div class="d-flex flex-wrap justify-content-lg-end gap-2 d-print-none">
                                <a href="../document/invoice-print.php?invoice_id=<?= $invoice_id ?>"
                                    class="btn btn-outline-secondary" target="_blank">
                                    <i class="bi bi-printer me-1"></i>
                                    Print
                                </a>
                                <a href="../document/invoice-download.php?invoice_id=<?= $invoice_id ?>"
                                    class="btn btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i>
                                    Download PDF
                                </a>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body p-4 p-md-5">
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <img src="../../../storage/<?= $invoice['company_logo']; ?>" alt="<?= $invoice['company_name']; ?>"
                                            style="max-height: 100px; width: auto;"
                                            class="mb-4">
                                        <h2 class="h4 text-primary fw-semibold"><?= $invoice['company_name'] ?></h2>
                                        <p class="text-secondary mb-0 small">
                                            <?= $invoice['company_province'] ?><br>
                                            <?= $invoice['company_subdistrict'] ?><br>
                                            <?= $invoice['company_email'] ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <h1 class="h2 mb-1">Invoice</h1>
                                        <p class="text-secondary mb-0">
                                            <span class="fw-semibold">#</span><?= $invoice['invoice_code'] ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <div class="mb-4">
                                            <p class="text-secondary small mb-1">Billed to</p>
                                            <p class="mb-0 fw-semibold"><?= $invoice['customer_name'] ?></p>
                                        </div>
                                        <div class="mb-4">
                                            <p class="text-secondary small mb-1">Handled by</p>
                                            <p class="mb-0 fw-semibold"><?= $invoice['pic_name'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <p class="text-secondary small mb-1">Issue date</p>
                                        <p class="mb-2"><?= $invoice['date'] ?></p>
                                        <p class="text-secondary small mb-1">Due date</p>
                                        <p class="mb-0"><?= $invoice['due_date'] ?></p>
                                    </div>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table class="table align-middle mb-0" role="table">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0" scope="col">Description</th>
                                                <th class="border-top-0 text-end" style="width: 6rem" scope="col">Qty</th>
                                                <th class="border-top-0 text-end" style="width: 9rem" scope="col">Unit price</th>
                                                <th class="border-top-0 text-end" style="width: 9rem" scope="col">Amount</th>
                                                <th class="border-top-0 text-end d-print-none" style="width: 9rem" scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoice_details as $invoice_detail):
                                                if (!empty($invoice_detail['item_id'])):
                                                    $amount = $invoice_detail['quantity'] * $invoice_detail['unit_price']; ?>
                                                    <tr>
                                                        <td>
                                                            <p class="mb-0 fw-semibold"><?= $invoice_detail['name'] ?></p>
                                                        </td>
                                                        <td class="text-end"><?= $invoice_detail['quantity'] ?></td>
                                                        <td class="text-end">Rp<?= number_format($invoice_detail['unit_price'], 0, ',', '.') ?></td>
                                                        <td class="text-end">Rp<?= number_format($invoice_detail['amount'], 0, ',', '.') ?></td>
                                                        <td class="text-end d-print-none">
                                                            <a class="btn btn-sm btn-success" href="detail-edit.php?id=<?= $invoice_detail['id'] ?>&item_id=<?= $invoice_detail['item_id'] ?>&invoice_id=<?= $invoice_detail['invoice_id'] ?>">
                                                                Edit
                                                            </a>
                                                            <a class="btn btn-sm btn-danger" href="detail-delete.php?id=<?= $invoice_detail['id'] ?>&invoice_id=<?= $invoice_detail['invoice_id'] ?>"
                                                                onclick="return confirm('Are you sure you want to delete this detail?');">
                                                                Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <a href="detail-add.php?invoice_id=<?= $invoice_id ?>" class="btn btn-primary d-print-none">Add Item</a>

                                <div class="row justify-content-end">
                                    <div class="col-md-5 col-lg-4">
                                        <dl class="row mb-0">
                                            <dt class="col-7 fw-semibold border-top pt-2">Total bill</dt>
                                            <dd class="col-5 text-end fw-semibold border-top pt-2 mb-0">Rp<?= number_format($total_bill, 0, ',', '.') ?></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>