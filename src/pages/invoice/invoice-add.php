<?php
session_start();
require_once '../../connection.php';
include '../../functions/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$invoice_code = generate_code($database, "invoice", "invoice_code", "INV");

$pic_id = '';
$customer_id = '';
$customers = $database->select('customer', ['id', 'name']);
$company_pics = $database->select('company_pic', ['id', 'name'], [
    'status' => 'active'
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pic_id = $_POST['pic_id'];
    $customer_id = $_POST['customer_id'];
    $date = $_POST['date'];
    $due_date = $_POST['due_date'];

    if ($due_date < $date) {
        echo '<script>alert("The due date must not be earlier than the invoice date")</script>';
    } else {
        $database->insert('invoice', [
            'pic_id' => $pic_id,
            'customer_id' => $customer_id,
            'invoice_code' => $invoice_code,
            'date' => $date,
            'due_date' => $due_date
        ]);

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
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

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
                                    <input type="hidden" name="invoice_code" required="" value="<?= $invoice_code ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PIC Name</label>
                                    <select name="pic_id" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled selected>Select PIC name</option>
                                        <?php foreach ($company_pics as $company_pic): ?>
                                            <option value="<?= $company_pic['id']; ?>" <?= ($pic_id == $company_pic['id']) ? 'selected' : ''; ?>>
                                                <?= $company_pic['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <select name="customer_id" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled selected>Select customer name</option>
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer['id']; ?>" <?= ($customer_id == $customer['id']) ? 'selected' : ''; ?>>
                                                <?= $customer['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input id="invoice_date" value="<?= $date ?? date('Y-m-d') ?>" name="date" type="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input id="due_date" value="<?= $due_date ?? date('Y-m-d', strtotime('+7 days')) ?>" name="due_date" type="date" class="form-control" required>
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

    <?php include '../../components/scripts.php'; ?>
    <script>
        const invoiceDate = document.getElementById('invoice_date');
        const dueDate = document.getElementById('due_date');

        invoiceDate.addEventListener('change', function() {
            if (!this.value) return;

            const date = new Date(this.value);

            date.setDate(date.getDate() + 7);

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            dueDate.value = `${year}-${month}-${day}`;
        });
    </script>
</body>

</html>