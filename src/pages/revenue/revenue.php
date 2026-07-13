<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

use Medoo\Medoo;

$number = 1;
$period = $_GET['period'] ?? 'daily';

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
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <!-- Page Title -->
                <div class="mb-3">
                    <h3 class="fw-bold h4 m-0 text-white">Revenue Overview</h3>
                    <p class="text-muted small m-0">
                        Analyze revenue trends, sales performance, and business growth over time
                    </p>
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
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= $number++ ?></th>
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
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>