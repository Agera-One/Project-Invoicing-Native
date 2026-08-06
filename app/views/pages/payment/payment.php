<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/Payment.php";
require_once '../../src/functions/functions.php';

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$db = (new Database())->getConnection();
$payment = new Payment($db, $company_id);

$where_condition = [];
$where_condition['invoice.company_id'] = $company_id;
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;

$join_structure = [
    '[><]invoice' => ['invoice_id' => 'id'],
    '[>]customer' => ['invoice.customer_id' => 'id']
];

$where_condition = search($search, $where_condition, ['payment.payment_code', 'invoice.invoice_code', 'customer.name', 'payment.date']);
$pagination = pagination($db, $page, 'payment', 'payment.id', $where_condition, $join_structure);

$datas = $payment->getAll($join_structure, $where_condition, $pagination['offset'], $pagination['limit']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Transactions</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once '../../src/components/navbar.php' ?>
        <?php include_once '../../src/components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Payment Transactions</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment Transactions</li>
                        </ol>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-Payments-center justify-content-between gap-3 mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="payment-add.php" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New Payment
                        </a>
                    </div>

                    <div class="col-md-4 d-flex align-Payments-end gap-2">
                        <form action="" method="GET" class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input name="search" id="table-filter" type="search" class="form-control border-start-0 ps-0" placeholder="Filter rows…" aria-label="Filter rows" autofocus autocomplete="off" value="<?= $_GET['search'] ?? ''; ?>">
                            </div>
                        </form>
                        <a href="payment.php" class="btn btn-outline-secondary w-25">
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
                                        <th scope="col">Payment Code</th>
                                        <th scope="col">Invoice Code</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Payment Date</th>
                                        <th scope="col">Amount Paid</th>
                                        <th scope="col" class="pe-4" width="160">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datas as $data): ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$pagination['offset'] ?></th>
                                            <td class="fw-medium"><?= $data['payment_code'] ?></td>
                                            <td><?= $data['invoice_code'] ?></td>
                                            <td><?= $data['customer_name'] ?></td>
                                            <td><?= $data['date'] ?></td>
                                            <td>Rp<?= number_format($data['amount'], 0, ',', '.') ?></td>
                                            <td class="pe-4">
                                                <div class="d-flex gap-1">
                                                    <a class="btn btn-sm btn-success" href="payment-edit.php?id=<?= $data['id'] ?>&customer_id=<?= $data['customer_id'] ?>&invoice_id=<?= $data['invoice_id'] ?>">Edit</a>
                                                    <a class="btn btn-sm btn-danger" href="payment-delete.php?id=<?= $data['id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this payment?');">Delete</a>
                                                </div>
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