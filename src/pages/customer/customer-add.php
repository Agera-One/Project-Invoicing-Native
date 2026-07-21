<?php
session_start();
require_once '../../connection.php';
include '../../functions/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$customer_code = generate_code($database, "customer", "customer_code", "CUST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone  = $_POST['phone'];
    $address = $_POST['address'];

    $check_email = count($database->select('customer', 'email', [
        'email' => $email,
    ]));

    $check_phone = count($database->select('customer', 'phone', [
        'phone' => $phone,  
    ]));

    if ($check_email > 0) {
        echo '<script>alert("Email already exists. Please use a different email.")</script>';
    } elseif ($check_phone > 0) {
        echo '<script>alert("phone already exists. Please use a different phone.")</script>';
    } elseif (strlen($name) > 255) {
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif (strlen($email) > 50) {
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } elseif (strlen($phone) > 20) {
        echo '<script>alert("Maximum phone length is 20 characters.")</script>';
    } else {
        $database->insert('customer', [
            'customer_code' => $customer_code,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address
        ]);

        header("Location: customer.php");
        exit();
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
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Add Customer</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../customer/customer.php">Customers Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Customer</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add New Customer</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Customer Code</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-control-plaintext fs-5 fw-bold text-primary bg-body-secondary border rounded px-3 py-2 mb-0">
                                        <i class="bi bi-upc-scan me-2"></i><span><?= $customer_code ?></span>
                                    </div>
                                </div>
                                <input type="hidden" name="customer_code" required="" value="<?= $customer_code ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input value="<?= $name ?? ''; ?>" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input value="<?= $email ?? ''; ?>" name="email" type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input value="<?= $phone ?? ''; ?>" name="phone" type="tel" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input value="<?= $address ?? ''; ?>" name="address" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="customer.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>