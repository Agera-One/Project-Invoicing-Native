<?php
require_once '../../connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM customer WHERE id = $id";
$result = mysqli_query($conn, $sql);
$customer = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE customer SET name='$name', email='$email', phone='$phone', address='$address' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: customer.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
</head>

<body>
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Quick Example</div>
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
                <a href="customer.php" type="submit" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>

    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
</body>

</html>
