<?php
require_once '../../connection.php';
include '../../components/scripts.php';

$id = $_GET['id'];

$customer = $database->get('customer', '*', [
    'id' => $id
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $check_email = count($database->select('customer', 'email', [
        'AND' => [
            'email' => $email,
            'id[!]' => $id
        ]
    ]));

    $check_phone = count($database->select('customer', 'phone', [
        'AND' => [
            'phone' => $phone,
            'id[!]' => $id
        ]
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
        $customers = $database->update(
            'customer',
            [
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'address' => $address
            ],
            [
                'id' => $id
            ]
        );

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
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Customer</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input name="name" value="<?= $customer['name'] ?>" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" value="<?= $customer['email'] ?>" type="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input name="phone" value="<?= $customer['phone'] ?>" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input name="address" value="<?= $customer['address'] ?>" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="customer.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    
</body>

</html>