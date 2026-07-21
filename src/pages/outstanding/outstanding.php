<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

use Medoo\Medoo;

$today = date('Y-m-d');
$limit = 10;
$active_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($active_page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$select_columns = [
    'invoice.id',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'invoice.customer_id',
    'customer.name(customer_name)',
    'total_bill' => Medoo::raw('SUM(<invoice_detail.amount>)'),
    'total_amount_paid' => Medoo::raw('(SELECT COALESCE(SUM(payment.amount), 0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
];

$join_structure = [
    '[><]customer' => ['customer_id' => 'id'],
    '[><]invoice_detail' => ['id' => 'invoice_id'],
];

$where_condition = [
    'invoice.due_date[>=]' => $today,
    'HAVING' => Medoo::raw('SUM(<invoice_detail.amount>) > (SELECT COALESCE(SUM(payment.amount), 0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
];

if ($search !== '') {
    $where_condition['customer.name[~]'] = $search;
}

$count_options = $where_condition;
$count_options['GROUP'] = ['invoice.id', 'customer.name', 'invoice.due_date'];
$rows = count($database->select('invoice', $join_structure, 'invoice.id', $count_options));
$total_page = ceil($rows / $limit);

$query_options = $where_condition;
$query_options['GROUP'] = 'invoice.id';
$query_options['ORDER'] = ['invoice.id' => 'DESC'];
$query_options['LIMIT'] = [$offset, $limit];

$invoices = $database->select('invoice', $join_structure, $select_columns, $query_options);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outstanding Invoices</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Outstanding Invoices</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Outstanding Invoices</li>
                        </ol>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="col-md-4 d-flex gap-2">
                        <form action="" method="GET" class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input name="search" id="table-filter" type="search"
                                    class="form-control border-start-0 ps-0" placeholder="Filter rows…"
                                    aria-label="Filter rows" autofocus autocomplete="off"
                                    value="<?= $_GET['search'] ?? '' ?>">
                            </div>
                        </form>
                        <a href="arrears.php" class="btn btn-outline-secondary w-25">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Invoice Code</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Total Bill</th>
                                        <th scope="col">Amount Paid</th>
                                        <th scope="col">Remaining Unpaid</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice):
                                        $remaining_unpaid = $invoice['total_bill'] - $invoice['total_amount_paid']; ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$offset ?></th>
                                            <td class="fw-medium"><?= $invoice['invoice_code'] ?></td>
                                            <td><?= $invoice['customer_name'] ?></td>
                                            <td><?= $invoice['date'] ?></td>
                                            <td><?= $invoice['due_date'] ?></td>
                                            <td>Rp<?= number_format($invoice['total_bill'], 0, ',', '.') ?></td>
                                            <td>Rp<?= number_format($invoice['total_amount_paid'], 0, ',', '.') ?></td>
                                            <td class="text-danger">Rp<?= number_format($remaining_unpaid, 0, ',', '.') ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-success" href="../payment/payment-add.php?invoice_id=<?= $invoice['id'] ?>&customer_id=<?= $invoice['customer_id'] ?>">Pay</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-top d-flex justify-content-end p-3">
                        <nav aria-label="Page navigation example" class="m-0">
                            <ul class="pagination pagination-sm m-0">
                                <?php if ($active_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $active_page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Previous</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                                    <li class="page-item <?= ($i == $active_page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($active_page < $total_page): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $active_page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Next</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>