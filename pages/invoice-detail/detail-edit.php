<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/InvoiceDetail.php";
require_once "../../classes/Item.php";

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$db = (new Database())->getConnection();
$invoice_detail = new InvoiceDetail($db, $company_id);
$item = new Item($db);

$id = $_GET['id'];
$item_id = $_GET['item_id'];
$invoice_id = $_GET['invoice_id'];

$detail_data = $invoice_detail->find($id);
$item_data = $item->getAll(['company_id' => $company_id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = 0;

    if (empty($_POST['unit_price'])) {
        $units_price = $db->get('item', 'price', [
            'id' => $item_id
        ]);

        $_POST['unit_price'] = $units_price;
        $_POST['amount'] = $_POST['quantity'] * $_POST['unit_price'];
    }

    if ($_POST['quantity'] < 1) {
        echo '<script>alert("The minimum quantity is 1.")</script>';
    } elseif ($_POST['unit_price'] < 1) {
        echo '<script>alert("The minimum price is 1.")</script>';
    } else {
        $_POST['amount'] = $_POST['quantity'] * $_POST['unit_price'];
        $invoice_detail->update($id, $_POST);

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
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once '../../src/components/navbar.php' ?>
        <?php include_once '../../src/components/sidebar.php' ?>

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
                                        <?php foreach ($item_data as $data): ?>
                                            <option value="<?= $data['id']; ?>" <?= ($item_id == $data['id']) ? 'selected' : ''; ?>>
                                                <?= $data['name'] . ' = Rp' . number_format($data['price'], 2, ',', '.'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input value="<?= $detail_data['quantity'] ?? ''; ?>" name="quantity" type="number" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unit Price</label>
                                    <input value="<?= $detail_data['unit_price'] ?? ''; ?>" name="unit_price" type="number" class="form-control">
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

    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>