<?php
require_once '../../connection.php';
include '../../components/scripts.php';

use Medoo\Medoo;

$number_omset = 1;
$number_top_product = 1;
$period = $_GET['period'] ?? 'daily';

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


if ($period === 'daily') {
    $omsets = $database->select('invoice', [
        '[><]invoice_detail' => [
            'id' => 'invoice_id'
        ]
    ], [
        'period' => Medoo::raw("DATE_FORMAT(<invoice.date>, '%W, %d %M %Y')"),
        'total_invoice' => Medoo::raw('COUNT(DISTINCT <invoice.id>)'),
        'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
        'total_revenue' => Medoo::raw('SUM(<invoice_detail.unit_price> * <invoice_detail.quantity>)')
    ], [
        'GROUP' => 'invoice.date',
        'ORDER' => [
            'invoice.date' => 'DESC'
        ],
        'LIMIT' => 7
    ]);


} elseif ($period === 'weekly') {
    $omsets = $database->select('invoice', [
        '[><]invoice_detail' => [
            'id' => 'invoice_id'
        ]
    ], [
        'period_code' => Medoo::raw('YEARWEEK(<invoice.date>, 1)'),
        'period' => Medoo::raw("CONCAT('Week ', WEEK(MIN(<invoice.date>), 1), ' (', DATE_FORMAT(MIN(<invoice.date>), '%M'), ')')"),
        'total_invoice' => Medoo::raw('COUNT(DISTINCT <invoice.id>)'),
        'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
        'total_revenue' => Medoo::raw('SUM(<invoice_detail.unit_price> * <invoice_detail.quantity>)')
    ], [
        'GROUP' => 'period_code',
        'ORDER' => [
            'period_code' => 'DESC'
        ],
        'LIMIT' => 5
    ]);

} else {
    $omsets = $database->select('invoice', [
        '[><]invoice_detail' => [
            'id' => 'invoice_id'
        ]
    ], [
        'period' => Medoo::raw("DATE_FORMAT(<invoice.date>, '%Y-%m')"),
        'total_invoice' => Medoo::raw('COUNT(DISTINCT <invoice.id>)'),
        'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
        'total_revenue' => Medoo::raw('SUM(<invoice_detail.unit_price> * <invoice_detail.quantity>)')
    ], [
        'GROUP' => 'period',
        'ORDER' => [
            'period' => 'DESC'
        ],
        'LIMIT' => 6
    ]);

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
        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="mb-3">
                    <h3 class="fw-bold h4 m-0 text-white">Sales Analysis Report</h3>
                    <p class="text-muted small m-0">Overview of earnings performance and product statistics</p>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="btn-group shadow-sm" role="group">
                        <?php if ($period == "daily"): ?>
                            <a class="btn btn-sm btn-primary active px-3" href="?period=daily">Daily</a>
                        <?php else: ?>
                            <a class="btn btn-sm btn-outline-primary px-3" href="?period=daily">Daily</a>
                        <?php endif; ?>

                        <?php if ($period == "weekly"): ?>
                            <a class="btn btn-sm btn-primary active px-3" href="?period=weekly">Weekly</a>
                        <?php else: ?>
                            <a class="btn btn-sm btn-outline-primary px-3" href="?period=weekly">Weekly</a>
                        <?php endif; ?>

                        <?php if ($period == "monthly"): ?>
                            <a class="btn btn-sm btn-primary active px-3" href="?period=monthly">Monthly</a>
                        <?php else: ?>
                            <a class="btn btn-sm btn-outline-primary px-3" href="?period=monthly">Monthly</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tabel Omset -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Period</th>
                                        <th scope="col">Total Invoices</th>
                                        <th scope="col">Total Units Sold</th>
                                        <th scope="col" class="pe-4">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($omsets as $omset): ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= $number_omset++ ?></th>
                                            <td class="fw-medium"><?= $omset['period'] ?></td>
                                            <td><?= $omset['total_invoice'] ?></td>
                                            <td><?= $omset['total_unit_sold'] ?></td>
                                            <td class="pe-4">Rp<?= number_format($omset['total_revenue'], 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabel Top Products -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Units Price</th>
                                        <th scope="col">Units Sold</th>
                                        <th scope="col" class="pe-4">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_products as $top_product): ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= $number_top_product++ ?></th>
                                            <td class="fw-medium"><?= $top_product['item_name'] ?></td>
                                            <td>Rp<?= number_format($top_product['unit_price'], 0, ',', '.') ?></td>
                                            <td><?= $top_product['total_unit_sold'] ?></td>
                                            <td class="pe-4">Rp<?= number_format($top_product['total_revenue'], 2, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>

</html>