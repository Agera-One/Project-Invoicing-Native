<?php
require_once '../../connection.php';

$id = $_GET['id'];

$customers = $database->select('customer', '*', [
    'id' => $id
]);

foreach ($customers as $customer);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $customers = $database->update('customer', [
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'address' => $address
        ], [
            'id' => $id
        ]
    );

    header("Location: customer.php");
    exit();
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

<body>
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

    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
</body>

</html>