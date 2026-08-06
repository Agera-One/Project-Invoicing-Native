<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/Invoice.php";
require_once "../../classes/Customer.php";
require_once "../../classes/Pic.php";
require_once '../../src/functions/functions.php';

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$db = (new Database())->getConnection();
$invoice = new Invoice($db, $company_id);
$customer = new Customer($db);
$pic = new Pic($db);

$invoice_code = generate_code($db, "invoice", "invoice_code", "INV");

$pic_id = $_POST['pic_id'] ?? '';
$customer_id = $_POST['customer_id'] ?? '';

$customer_data = $customer->getAll(['company_id' => $company_id]);

$pic_data = $pic->getAll([
    'AND' => [
        'is_active' => 1,
        'company_id' => $company_id
    ]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['invoice_code'] = $invoice_code;
    $_POST['company_id'] = $company_id;

    if ($_POST['due_date'] < $_POST['date']) {
        echo '<script>alert("The due date must not be earlier than the invoice date")</script>';
    } else {
        $invoice->create($_POST);

        header("Location: invoice.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Invoice</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../src/components/navbar.php' ?>
        <?php include '../../src/components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Add New Invoice</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../invoice/invoice.php">Invoices Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add New Invoice</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add New Invoice</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Invoice Code</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-control-plaintext fs-5 fw-bold text-primary bg-body-secondary border rounded px-3 py-2 mb-0">
                                            <i class="bi bi-upc-scan me-2"></i><span><?= $invoice_code ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PIC Name</label>
                                    <select name="pic_id" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled selected>Select PIC name</option>
                                        <?php foreach ($pic_data as $pic): ?>
                                            <option value="<?= $pic['id'] ?>" <?= ($pic_id == $pic['id']) ? 'selected' : ''; ?>>
                                                <?= $pic['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <select name="customer_id" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled selected>Select customer name</option>
                                        <?php foreach ($customer_data as $customer): ?>
                                            <option value="<?= $customer['id'] ?>" <?= ($customer_id == $customer['id']) ? 'selected' : ''; ?>>
                                                <?= $customer['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input id="invoice_date" value="<?= $_POST['date'] ?? date('Y-m-d') ?>" name="date" type="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input id="due_date" value="<?= $_POST['due_date'] ?? date('Y-m-d', strtotime('+7 days')) ?>" name="due_date" type="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="invoice.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>