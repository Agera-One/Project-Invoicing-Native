<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/Invoice.php";
require_once "../../classes/Payment.php";
require_once "../../classes/Item.php";
require_once "../../classes/InvoiceDetail.php";

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$db = (new Database())->getConnection();
$invoice = new Invoice($db, $company_id);
$payment = new Payment($db, $company_id);
$item = new Item($db);
$invoice_detail = new InvoiceDetail($db, $company_id);

$number = 1;
$today = date('Y-m-d');

$invoice_value = $invoice->sumInvoiceValue();
$total_revenue = $payment->sumRevenue();
$datas = $invoice->getAllCompact();
$top_item = $item->getTopItem($company_id);
$sum_unpaid_overdue = $invoice->sumUnpaidOverdue($today);
extract($sum_unpaid_overdue);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once '../../src/components/navbar.php' ?>
        <?php include_once '../../src/components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Dashboard</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </div>
                </div>

                <div class="row mb-4 g-3">
                    <div class="col-lg-3 col-6">
                        <div class="finance-card finance-card--primary">
                            <div class="finance-card-top">
                                <div class="finance-card-label">Invoice Value</div>
                                <div class="finance-card-icon"><i class="bi bi-receipt-cutoff"></i></div>
                            </div>
                            <div class="finance-card-value">Rp<?= number_format($invoice_value, 0, ',', '.') ?></div>
                            <div class="finance-card-footer">
                                <a href="../invoice/invoice.php">More info <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="finance-card finance-card--success">
                            <div class="finance-card-top">
                                <div class="finance-card-label">Total Revenue</div>
                                <div class="finance-card-icon"><i class="bi bi-cash-coin"></i></div>
                            </div>
                            <div class="finance-card-value">Rp<?= number_format($total_revenue, 0, ',', '.') ?></div>
                            <div class="finance-card-footer">
                                <a href="../revenue/revenue.php">More info <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="finance-card finance-card--warning">
                            <div class="finance-card-top">
                                <div class="finance-card-label">Total Unpaid</div>
                                <div class="finance-card-icon"><i class="bi bi-hourglass-split"></i></div>
                            </div>
                            <div class="finance-card-value">Rp<?= number_format($total_unpaid, 0, ',', '.') ?></div>
                            <div class="finance-card-footer">
                                <a href="../outstanding/outstanding.php">More info <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="finance-card finance-card--danger">
                            <div class="finance-card-top">
                                <div class="finance-card-label">Total Overdue</div>
                                <div class="finance-card-icon"><i class="bi bi-exclamation-triangle"></i></div>
                            </div>
                            <div class="finance-card-value">Rp<?= number_format($total_overdue, 0, ',', '.') ?></div>
                            <div class="finance-card-footer">
                                <a href="../overdue/overdue.php">More info <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dash-section">
                    <div class="row g-3">
                        <div class="col-12 col-lg-5">
                            <div class="dash-section-title">Top Selling Products</div>
                            <div class="card h-100">
                                <div class="card-body">
                                    <?php foreach ($top_item as $top_product): ?>
                                        <div class="product-row">
                                            <span class="product-rank"><?= $number++ ?></span>
                                            <div class="flex-grow-1">
                                                <div class="small fw-semibold"><?= $top_product['item_name'] ?></div>
                                                <small class="text-muted"><?= $top_product['total_unit_sold'] ?> sold</small>
                                            </div>
                                            <div class="text-end small fw-semibold">Rp <?= number_format($top_product['total_revenue'], 0, ',', '.') ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-7">
                            <div class="dash-section-title">Recent Invoices</div>
                            <div class="card h-100">
                                <div class="card-body p-0 d-flex flex-column">
                                    <div class="table-responsive flex-grow-1">
                                        <table class="table table-hover align-middle mb-0" role="table">
                                            <thead class="table table-hover align-middle mb-0" role="table">
                                                <tr>
                                                    <th scope="col">Invoice Code</th>
                                                    <th scope="col">Customer Name</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Due Date</th>
                                                    <th scope="col">Total Bill</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($datas as $data):
                                                    $invoice_item = $invoice_detail->invoiceItemCount($data['id']);
                                                    $remaining_unpaid = $data['total_bill'] - $data['total_payment']; ?>
                                                    <tr>
                                                        <td class="fw-medium"><?= $data['invoice_code'] ?></td>
                                                        <td><?= $data['customer_name'] ?></td>
                                                        <td><?= $data['date'] ?></td>
                                                        <td><?= $data['due_date'] ?></td>
                                                        <td>Rp<?= number_format($data['total_bill'], 0, ',', '.') ?></td>
                                                        <?php if ($remaining_unpaid > 0 && $data['due_date'] < $today): ?>
                                                            <td class="text-center"><span class="badge text-bg-danger">Overdue</span></td>
                                                        <?php elseif ($invoice_item == 0): ?>
                                                            <td class="text-center"><span class="badge text-bg-secondary">No Item</span></td>
                                                        <?php elseif ($data['total_payment'] < $data['total_bill']): ?>
                                                            <td class="text-center"><span class="badge text-bg-warning">Unpaid</span></td>
                                                        <?php elseif ($data['total_payment'] == $data['total_bill']): ?>
                                                            <td class="text-center"><span class="badge text-bg-success">Paid</span></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center border-top py-2">
                                        <a href="../invoice/invoice.php" class="btn btn-sm btn-link text-decoration-none">View All Transactions
                                            <i class="bi bi-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>