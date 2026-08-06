<?php

use Medoo\Medoo;

session_start();
require_once "../../config/database.php";
require_once "../../classes/Invoice.php";
require_once '../../src/functions/functions.php';

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$db = (new Database())->getConnection();
$invoice = new Invoice($db, $company_id);

$today = date('Y-m-d');
$where_condition = [];
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;

$join_structure = [
    '[><]customer' => ['customer_id' => 'id'],
    '[><]invoice_detail' => ['id' => 'invoice_id'],
    '[><]pic' => ['pic_id' => 'id'],
];

$where_condition = [
    'invoice.company_id' => $company_id,
    'invoice.due_date[<]' => $today,
    'HAVING' => Medoo::raw('SUM(<invoice_detail.amount>) > (SELECT COALESCE(SUM(payment.amount), 0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
];

$where_condition = search($search, $where_condition, ['invoice.invoice_code', 'customer.name', 'invoice.date', 'invoice.due_date']);
$pagination = pagination($db, $page, 'invoice', 'invoice.id', $where_condition, $join_structure);

$datas = $invoice->getAll($join_structure, $where_condition, $pagination['offset'], $pagination['limit']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Invoices</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once '../../src/components/navbar.php' ?>
        <?php include_once '../../src/components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Overdue Invoices</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Overdue Invoices</li>
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
                        <a href="overdue.php" class="btn btn-outline-secondary w-25">
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
                                    <?php foreach ($datas as $data):
                                        $remaining_unpaid = $data['total_bill'] - $data['total_amount_paid'] ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$pagination['offset'] ?></th>
                                            <td class="fw-medium"><?= $data['invoice_code'] ?></td>
                                            <td><?= $data['customer_name'] ?></td>
                                            <td><?= $data['date'] ?></td>
                                            <td><?= $data['due_date'] ?></td>
                                            <td>Rp<?= number_format($data['total_bill'], 0, ',', '.') ?></td>
                                            <td>Rp<?= number_format($data['total_amount_paid'], 0, ',', '.') ?></td>
                                            <td class="text-danger">Rp<?= number_format($remaining_unpaid, 0, ',', '.') ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-success" href="../payment/payment-add.php?invoice_id=<?= $data['id'] ?>">Pay</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php include_once '../../src/components/pagination.php' ?>
                </div>

            </div>
        </main>
    </div>

    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>