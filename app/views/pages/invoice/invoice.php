<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/Invoice.php";
require_once "../../classes/InvoiceDetail.php";
require_once '../../src/functions/functions.php';

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$db = (new Database())->getConnection();
$invoice = new Invoice($db, $company_id);
$invoice_detail = new InvoiceDetail($db, $company_id);

$today = date('Y-m-d');
$where_condition = [];
$keyword = $_GET['keyword'] ?? '';
$page = $_GET['page'] ?? 1;
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

$join_structure = [
    '[><]customer' => ['customer_id' => 'id'],
    '[>]invoice_detail' => ['id' => 'invoice_id'],
    '[>]payment' => ['id' => 'invoice_id'],
    '[><]pic' => ['pic_id' => 'id'],
];

$where_condition['invoice.company_id'] = $company_id;

if (!empty($date_from) && !empty($date_to)) {
    $where_condition['invoice.date[<>]'] = [$date_from, $date_to];
} elseif (!empty($date_from)) {
    $where_condition['invoice.date[>=]'] = $date_from;
} elseif (!empty($date_to)) {
    $where_condition['invoice.date[<=]'] = $date_to;
}

$where_condition = search($keyword, $where_condition, ['invoice.invoice_code', 'customer.name', 'pic.name']);
$pagination = pagination($db, $page, 'invoice', 'invoice.id', $where_condition, $join_structure);

$datas = $invoice->getAll($join_structure, $where_condition, $pagination['offset'], $pagination['limit']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices Billing</title>
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
                        <h3 class="fw-bold h4 m-0 text-white">Invoices Billing</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoices Billing</li>
                        </ol>
                    </div>
                </div>

                <div class="flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="invoice-add.php" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New Invoice
                        </a>
                    </div>

                    <form action="" method="GET">
                        <div class="row g-2 my-3">
                            <div class="col-md-4">
                                <label class="form-label">Keyword</label>
                                <input
                                    type="text"
                                    name="keyword"
                                    class="form-control"
                                    placeholder="Search for customers and invoice codes..."
                                    value="<?= $_GET['keyword'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date From</label>
                                <input
                                    type="date"
                                    name="date_from"
                                    class="form-control"
                                    value="<?= $_GET['date_from'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date To</label>
                                <input
                                    type="date"
                                    name="date_to"
                                    class="form-control"
                                    value="<?= $_GET['date_to'] ?? ''; ?>">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button id="btn-search" type="submit" class="btn btn-md btn-primary w-100" name="search">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="invoice.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Invoice Code</th>
                                        <th scope="col">PIC Name</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Invoice Date</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Total Bill</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="pe-4" width="200">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datas as $data):
                                        $invoice_item = $invoice_detail->invoiceItemCount($data['id']);
                                        $remaining_unpaid = $data['total_bill'] - $data['total_payment']; ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$pagination['offset'] ?></th>
                                            <td class="fw-medium"><?= $data['invoice_code'] ?></td>
                                            <td><?= $data['pic_name'] ?></td>
                                            <td><?= $data['customer_name'] ?></td>
                                            <td><?= $data['date'] ?></td>
                                            <td><?= $data['due_date'] ?></td>
                                            <td>Rp<?= number_format($data['total_bill'] ?? 0, 0, ',', '.') ?></td>
                                            <?php if ($remaining_unpaid > 0 && $data['due_date'] < $today): ?>
                                                <td class="text-center"><span class="badge text-bg-danger">Overdue</span></td>
                                            <?php elseif ($invoice_item == 0): ?>
                                                <td class="text-center"><span class="badge text-bg-secondary">No Item</span></td>
                                            <?php elseif ($data['total_payment'] < $data['total_bill']): ?>
                                                <td class="text-center"><span class="badge text-bg-warning">Unpaid</span></td>
                                            <?php elseif ($data['total_payment'] == $data['total_bill']): ?>
                                                <td class="text-center"><span class="badge text-bg-success">Paid</span></td>
                                            <?php endif; ?>
                                            <td class="pe-4">
                                                <div class="d-flex gap-1">
                                                    <a class="btn btn-sm btn-info text-black" href="../invoice-detail/detail.php?invoice_id=<?= $data['id'] ?>">Detail</a>
                                                    <a class="btn btn-sm btn-success" href="invoice-edit.php?id=<?= $data['id'] ?>&customer_id=<?= $data['customer_id'] ?>&pic_id=<?= $data['pic_id'] ?>">Edit</a>
                                                    <a class="btn btn-sm btn-danger" href="invoice-delete.php?id=<?= $data['id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
                                                </div>
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
                                <?php $filter_params = '&keyword=' . urlencode($keyword) . '&date_from=' . urlencode($date_from) . '&date_to=' . urlencode($date_to) . '&search='; ?>

                                <?php if ($pagination['active_page'] > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $pagination['active_page'] - 1 ?><?= $filter_params ?>">Previous</a></li>
                                <?php else: ?>
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $pagination['total_page']; $i++): ?>
                                    <li class="page-item <?= ($i == $pagination['active_page']) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= $filter_params ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($pagination['active_page'] < $pagination['total_page']): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $pagination['active_page'] + 1 ?><?= $filter_params ?>">Next</a></li>
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

    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>