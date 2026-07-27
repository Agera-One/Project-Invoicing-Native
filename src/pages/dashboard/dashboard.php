<?php
session_start();
require_once '../../connection.php';

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

use Medoo\Medoo;

$today = date('Y-m-d');
$number = 1;
$total_unpaid = 0;
$total_overdue = 0;

$invoice_value = $database->sum('invoice', [
    '[><]invoice_detail' => ['id' => 'invoice_id']
], 'invoice_detail.amount', [
    'company_id' => $company_id
]) ?: 0;

$total_revenue = $database->sum('payment', [
    '[><]invoice' => ['invoice_id' => 'id']
], 'payment.amount', [
    'invoice.company_id' => $company_id
]) ?: 0;

$invoices = $database->select('invoice', [
    '[><]customer' => ['customer_id' => 'id'],
], [
    'invoice.id',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
    'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
], [
    'ORDER' => ['invoice.id' => 'DESC'],
    'invoice.company_id' => $company_id,
    'LIMIT' => 6
]);

$top_products = $database->select('item', [
    '[><]invoice_detail' => [
        'id' => 'item_id'
    ]
], [
    'item.name(item_name)',
    'invoice_detail.unit_price',
    'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
    'total_revenue' => Medoo::raw('SUM(<invoice_detail.unit_price> * <invoice_detail.quantity>)')
], [
    'GROUP' => 'item.id',
    'ORDER' => [
        'total_unit_sold' => 'DESC'
    ],
    'item.company_id' => $company_id,
    'LIMIT' => 5
]);

$all_invoices = $database->select('invoice', [
    'id',
    'due_date',
    'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
    'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
], [
    'invoice.company_id' => $company_id,
]);

foreach ($all_invoices as $invoice) {
    $remaining = $invoice['total_bill'] - $invoice['total_payment'];

    if ($remaining > 0) {
        if ($invoice['due_date'] >= $today) {
            $total_unpaid += $remaining;
        } else {
            $total_overdue += $remaining;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

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
                                    <?php foreach ($top_products as $top_product): ?>
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
                                                <?php foreach ($invoices as $invoice):
                                                    $item_count = $database->count('invoice_detail', [
                                                        'invoice_id' => $invoice['id']
                                                    ]);

                                                    $remaining_unpaid = $invoice['total_bill'] - $invoice['total_payment']; ?>
                                                    <tr>
                                                        <td class="fw-medium"><?= $invoice['invoice_code'] ?></td>
                                                        <td><?= $invoice['customer_name'] ?></td>
                                                        <td><?= $invoice['date'] ?></td>
                                                        <td><?= $invoice['due_date'] ?></td>
                                                        <td>Rp<?= number_format($invoice['total_bill'], 0, ',', '.') ?></td>
                                                        <?php if ($remaining_unpaid > 0 && $invoice['due_date'] < $today): ?>
                                                            <td class="text-center"><span class="badge text-bg-danger">Overdue</span></td>
                                                        <?php elseif ($item_count == 0): ?>
                                                            <td class="text-center"><span class="badge text-bg-secondary">No Item</span></td>
                                                        <?php elseif ($invoice['total_payment'] < $invoice['total_bill']): ?>
                                                            <td class="text-center"><span class="badge text-bg-warning">Unpaid</span></td>
                                                        <?php elseif ($invoice['total_payment'] == $invoice['total_bill']): ?>
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

    <script src="../../../assets/js/lte-theme.js"></script>
    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>