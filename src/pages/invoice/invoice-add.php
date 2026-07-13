<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$pic_id = '';
$customer_id = '';
$company_pics = $database->select('company_pic', ['id', 'name']);
$customers = $database->select('customer', ['id', 'name']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pic_id = $_POST['pic_id'];
    $customer_id = $_POST['customer_id'];
    $invoice_code = $_POST['invoice_code'];
    $date = $_POST['date'];
    $due_date = $_POST['due_date'];

    if (strlen($invoice_code) > 10) {
        echo '<script>alert("The invoice code is limited to a maximum of 10 characters")</script>';
    } elseif ($due_date < $date) {
        echo '<script>alert("The due date must not be earlier than the invoice date")</script>';
    } else {
        $check_code = count($database->select('invoice', 'invoice_code', [
            'invoice_code' => $invoice_code,
        ]));

        if ($check_code > 0) {
            echo '<script>alert("Code invoice already exists. Please use a different code invoice.")</script>';
        } else {
            $invoices = $database->insert('invoice', [
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
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add New Invoice</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Code Invoice</label>
                                    <input value="<?= $invoice_code ?? ''; ?>" name="invoice_code" type="text" class="form-control" required>
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
                                    <input value="<?= $date ?? ''; ?>" name="date" type="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input value="<?= $due_date ?? ''; ?>" name="due_date" type="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="invoice.php" class="btn btn-danger">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>