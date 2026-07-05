<?php
session_start();
require_once '../../connection.php';

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
        $items = $database->insert('customer', [
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

<body>
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Add New Customer</div>
        </div>
        <form action="" method="POST">
            <div class="card-body">
                <div class="mb-3">
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

    <script src="../../../assets//admin-lte/dist/js/adminlte.js"></script>
</body>

</html>