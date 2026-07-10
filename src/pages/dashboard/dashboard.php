<?php
require_once '../../connection.php';
include '../../components/scripts.php';

use Medoo\Medoo;

$number = 1;

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
    'invoice.customer_id',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
    'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
], [
    'GROUP' => [
        'invoice.id',
        'invoice.customer_id',
        'invoice.invoice_code',
        'invoice.date',
        'invoice.due_date',
        'customer.name'
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
$total_customer = count($database->select("customer", "*"));
$total_item = count($database->select("item", "*"));
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
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                            </svg>
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
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"></path>
                            </svg>
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
                                <h3><?= $total_customer ?></h3>

                                <p>Total Customers</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                            </svg>
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
                                <h3><?= $total_item ?></h3>

                                <p>Total Item</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
                            </svg>
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
                                                    <th scope="col">Total Bill</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($invoices as $invoice):
                                                    $item_count = $database->count('invoice_detail', [
                                                        'invoice_id' => $invoice['id']
                                                    ]); ?>
                                                    <tr>
                                                        <td class="fw-medium"><?= $invoice['invoice_code'] ?></td>
                                                        <td><?= $invoice['customer_name'] ?></td>
                                                        <td><?= $invoice['date'] ?></td>
                                                        <td>Rp <?= number_format($invoice['total_bill'] ?? 0, 0, ',', '.') ?></td>
                                                        <?php if ($item_count == 0): ?>
                                                            <td class="text-center"><span class="badge text-bg-secondary">No Item</span></td>
                                                        <?php elseif ($invoice['total_payment'] == 0): ?>
                                                            <td class="text-center"><span class="badge text-bg-danger">Unpaid</span></td>
                                                        <?php elseif ($invoice['total_payment'] < $invoice['total_bill']): ?>
                                                            <td class="text-center"><span class="badge text-bg-warning">Partially Paid</span></td>
                                                        <?php else: ?>
                                                            <td class="text-center"><span class="badge text-bg-success">Paid</span></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center border-top py-2">
                                        <a href="../invoice/table.invoice.php" class="btn btn-sm btn-link text-decoration-none">View All Transactions
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
</body>

</html>