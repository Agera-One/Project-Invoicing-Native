<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

use Medoo\Medoo;

$number = 1;

$allowed_periods = ['all', 'yearly', 'monthly', 'weekly'];
$period = $_GET['period'] ?? 'all';
if (!in_array($period, $allowed_periods)) {
    $period = 'all';
}

$where = [
    'GROUP' => 'item.id',
    'ORDER' => [
        'total_unit_sold' => 'DESC'
    ],
    'LIMIT' => 10
];

$period_label = 'All Time';

switch ($period) {
    case 'weekly':
        $start = date('Y-m-d', strtotime('monday this week'));
        $end   = date('Y-m-d', strtotime('sunday this week'));
        $where['invoice.date[>=]'] = $start;
        $where['invoice.date[<=]'] = $end;
        $period_label = 'This Week';
        break;

    case 'monthly':
        $start = date('Y-m-01');
        $end   = date('Y-m-t');
        $where['invoice.date[>=]'] = $start;
        $where['invoice.date[<=]'] = $end;
        $period_label = 'This Month';
        break;

    case 'yearly':
        $start = date('Y-01-01');
        $end   = date('Y-12-31');
        $where['invoice.date[>=]'] = $start;
        $where['invoice.date[<=]'] = $end;
        $period_label = 'This Year';
        break;

    case 'all':
    default:
        $period_label = 'All Time';
        break;
}

$top_products = $database->select('item', [
    '[><]invoice_detail' => [
        'id' => 'item_id'
    ],
    '[><]invoice' => [
        'invoice_detail.invoice_id' => 'id'
    ]
], [
    'item.name(item_name)',
    'item.price',
    'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
    'total_sales' => Medoo::raw('SUM(<invoice_detail.amount>)')
], $where);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Selling Products</title>
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
                        <h3 class="fw-bold h4 m-0 text-white">Best Selling Products</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Best Selling Products</li>
                        </ol>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div class="btn-group period-filter" role="group" aria-label="Period filter">
                        <a href="?period=all"
                            class="btn btn-outline-light <?= $period === 'all' ? 'active btn-light text-dark' : '' ?>">
                            All Time
                        </a>
                        <a href="?period=yearly"
                            class="btn btn-outline-light <?= $period === 'yearly' ? 'active btn-light text-dark' : '' ?>">
                            Yearly
                        </a>
                        <a href="?period=monthly"
                            class="btn btn-outline-light <?= $period === 'monthly' ? 'active btn-light text-dark' : '' ?>">
                            Monthly
                        </a>
                        <a href="?period=weekly"
                            class="btn btn-outline-light <?= $period === 'weekly' ? 'active btn-light text-dark' : '' ?>">
                            Weekly
                        </a>
                    </div>
                    <span class="text-white-50 mt-2 mt-sm-0">
                        Showing: <span class="fw-semibold text-white"><?= $period_label ?></span>
                    </span>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Unit Price</th>
                                        <th scope="col">Units Sold</th>
                                        <th scope="col" class="pe-4">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($top_products)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                No sales data found for this period.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($top_products as $top_product): ?>
                                            <tr>
                                                <th scope="row" class="ps-4 text-muted fw-normal"><?= $number++ ?></th>
                                                <td class="fw-medium"><?= $top_product['item_name'] ?></td>
                                                <td>Rp<?= number_format($top_product['price'], 0, ',', '.') ?></td>
                                                <td><?= $top_product['total_unit_sold'] ?></td>
                                                <td class="pe-4 fw-semibold">Rp<?= number_format($top_product['total_sales'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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