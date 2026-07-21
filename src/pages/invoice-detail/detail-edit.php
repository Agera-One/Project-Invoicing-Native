<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$item_id = $_GET['item_id'];
$invoice_id = $_GET['invoice_id'];

$detail = $database->get('invoice_detail', '*', [
    'id' => $id
]);

$items =  $database->select('item', '*');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_id = $_POST['invoice_id'];
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $unit_price = (int)$_POST['unit_price'];
    $amount = 0;

    if (empty($unit_price)) {
        $units_price = $database->get('item', 'price', [
            'id' => $item_id
        ]);

        if ($units_price) {
            $unit_price = $units_price;
            $amount = $quantity * $unit_price;
        }
    }

    if ($quantity < 1) {
        echo '<script>alert("The minimum quantity is 1.")</script>';
    } elseif ($unit_price < 1) {
        echo '<script>alert("The minimum price is 1.")</script>';
    } else {
        $amount = $quantity * $unit_price;

        $invoices = $database->update('invoice_detail', [
            'invoice_id' => $invoice_id,
            'item_id' => $item_id,
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'amount' => $amount
        ], [
            'id' => $id
        ]);

        header("Location: detail.php?invoice_id=" . $invoice_id);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice Item</title>
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
                        <h3 class="fw-bold h4 m-0 text-white">Edit Invoice Item</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../invoice/invoice.php">Invoices Billing</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="detail.php?invoice_id=<?= $invoice_id ?>">Invoice Details</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Invoice Item</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Detail</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <input name="invoice_id" value="<?= $invoice_id ?>" type="hidden">
                                <div class="mb-3">
                                    <label class="form-label">Item Name</label>
                                    <select name="item_id" class="form-select" aria-label="Default select example">
                                        <?php foreach ($items as $item): ?>
                                            <option value="<?= $item['id']; ?>" <?= ($item_id == $item['id']) ? 'selected' : ''; ?>>
                                                <?= $item['name'] . ' = Rp' . number_format($item['price'], 2, ',', '.'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input value="<?= $detail['quantity'] ?? ''; ?>" name="quantity" type="number" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unit Price</label>
                                    <input value="<?= $detail['unit_price'] ?? ''; ?>" name="unit_price" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="detail.php?invoice_id=<?= $invoice_id ?>" class="btn btn-danger">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>