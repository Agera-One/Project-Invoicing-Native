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
    $periodKeyExpr   = "DATE(<payment.date>)";
    $periodLabelExpr = "DATE_FORMAT(<payment.date>, '%W, %d %M %Y')";
    $limit           = 7;
} elseif ($period === 'weekly') {
    $periodKeyExpr   = "YEARWEEK(<payment.date>, 1)";
    $periodLabelExpr = "CONCAT('Week ', WEEK(MIN(<payment.date>), 1), ' (', DATE_FORMAT(MIN(<payment.date>), '%M'), ')')";
    $limit           = 5;
} else {
    $periodKeyExpr   = "DATE_FORMAT(<payment.date>, '%Y-%m')";
    $periodLabelExpr = "DATE_FORMAT(<payment.date>, '%Y-%m')";
    $limit           = 6;
}

$omsets = $database->select('payment', [
    'period_key' => Medoo::raw($periodKeyExpr),
    'period' => Medoo::raw($periodLabelExpr),
    'total_invoice' => Medoo::raw('COUNT(DISTINCT <payment.invoice_id>)'),
    'total_payment' => Medoo::raw('COUNT(<payment.id>)'),
    'revenue' => Medoo::raw('SUM(<payment.amount>)')
], [
    'GROUP' => 'period_key',
    'ORDER' => ['period_key' => 'DESC'],
    'LIMIT' => $limit
]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Overview</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <style>
        .period-filter .btn {
            min-width: 100px;
        }

        .period-filter .btn.active {
            font-weight: 600;
        }
    </style>
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Revenue Overview</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Revenue Overview</li>
                        </ol>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div class="btn-group period-filter" role="group" aria-label="Period filter">
                        <a href="?period=daily"
                            class="btn btn-outline-light <?= $period === 'daily' ? 'active btn-light text-dark' : '' ?>">
                            Daily
                        </a>
                        <a href="?period=weekly"
                            class="btn btn-outline-light <?= $period === 'weekly' ? 'active btn-light text-dark' : '' ?>">
                            Weekly
                        </a>
                        <a href="?period=monthly"
                            class="btn btn-outline-light <?= $period === 'monthly' ? 'active btn-light text-dark' : '' ?>">
                            Monthly
                        </a>
                    </div>
                    <span class="text-white-50 mt-2 mt-sm-0">
                        Showing: <span class="fw-semibold text-white"><?= ucfirst($period) ?></span>
                    </span>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Period</th>
                                        <th scope="col">Invoices Paid</th>
                                        <th scope="col">Total Payments</th>
                                        <th scope="col" class="pe-4">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($omsets)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No revenue data found.</td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($omsets as $omset): ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= $number++ ?></th>
                                            <td class="fw-medium"><?= $omset['period'] ?></td>
                                            <td><?= $omset['total_invoice'] ?></td>
                                            <td><?= $omset['total_payment'] ?></td>
                                            <td class="pe-4 fw-semibold">Rp<?= number_format($omset['revenue'], 0, ',', '.') ?></td>
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