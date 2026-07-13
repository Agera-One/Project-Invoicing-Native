<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

use Medoo\Medoo;

$today = date('Y-m-d');
$number = 1;
$total_unpaid = 0;
$total_overdue = 0;

$where = [
    "date[>=]" => Medoo::raw("DATE_FORMAT(CURDATE(), '%Y-%m-01')"),
    "date[<]"  => Medoo::raw("DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01')")
];

$invoices = $database->select('invoice', [
    '[><]customer' => ['customer_id' => 'id'],
    '[>]invoice_detail' => ['id' => 'invoice_id'],
    '[>]payment' => ['id' => 'invoice_id'],
], [
    'invoice.id',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
    'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
], [
    'GROUP' => [
        'invoice.id',
        'invoice.invoice_code',
        'invoice.date',
        'invoice.due_date',
    ],
    'ORDER' => [
        'invoice.id' => 'DESC'
    ],
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
    'LIMIT' => 5
]);

$total_invoice = count($database->select("invoice", "*"));
$total_revenue = array_sum($database->select("payment", "amount"));

foreach ($invoices as $invoice) {
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
    <title>Document</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <style>
        .dash-section {
            margin-bottom: 2rem;
        }

        .dash-section-title {
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--bs-secondary-color);
            margin-bottom: .9rem;
        }

        /* ── Top products ────────────────────────────────────── */
        .product-row {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .65rem 0;
        }

        .product-row+.product-row {
            border-top: 1px solid var(--bs-border-color);
        }

        .product-rank {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: var(--bs-tertiary-bg);
            font-size: .75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <!--begin::Col-->
                    <div class="col-lg-3 col-6">
                        <!--begin::Small Box Widget 1-->
                        <div class="small-box text-bg-primary">
                            <div class="inner">
                                <h3><?= $total_invoice ?></h3>

                                <p>Total Invoice</p>
                            </div>
                            <i class="small-box-icon bi bi-receipt-cutoff"></i>
                            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                More info <i class="bi bi-link-45deg"></i>
                            </a>
                        </div>
                        <!--end::Small Box Widget 1-->
                    </div>
                    <!--end::Col-->
                    <div class="col-lg-3 col-6">
                        <!--begin::Small Box Widget 2-->
                        <div class="small-box text-bg-success">
                            <div class="inner">
                                <h3>Rp<?= number_format($total_revenue, 0, ',', '.') ?></h3>

                                <p>Total Revenue</p>
                            </div>
                            <i class="small-box-icon bi bi-cash-coin"></i>
                            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                More info <i class="bi bi-link-45deg"></i>
                            </a>
                        </div>
                        <!--end::Small Box Widget 2-->
                    </div>
                    <!--end::Col-->
                    <div class="col-lg-3 col-6">
                        <!--begin::Small Box Widget 3-->
                        <div class="small-box text-bg-warning">
                            <div class="inner">
                                <h3>Rp<?= number_format($total_unpaid, 0, ',', '.') ?></h3>

                                <p>Total Unpaid</p>
                            </div>
                            <i class="small-box-icon bi bi-hourglass-split"></i>
                            <a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                                More info <i class="bi bi-link-45deg"></i>
                            </a>
                        </div>
                        <!--end::Small Box Widget 3-->
                    </div>
                    <!--end::Col-->
                    <div class="col-lg-3 col-6">
                        <!--begin::Small Box Widget 4-->
                        <div class="small-box text-bg-danger">
                            <div class="inner">
                                <h3>Rp<?= number_format($total_overdue, 0, ',', '.') ?></h3>

                                <p>Total Overdue</p>
                            </div>
                            <i class="small-box-icon bi bi-exclamation-triangle"></i>
                            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                                More info <i class="bi bi-link-45deg"></i>
                            </a>
                        </div>
                        <!--end::Small Box Widget 4-->
                    </div>
                    <!--end::Col-->
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
                                                        <td>Rp<?= number_format($invoice['total_bill'] ?? 0, 0, ',', '.') ?></td>
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

    <?php include '../../components/scripts.php'; ?>
</body>

</html>