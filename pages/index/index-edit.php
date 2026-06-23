<?php
require_once '../../connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM item WHERE id = $id";
$result = mysqli_query($conn, $sql);
$item = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (!is_numeric($price)) {
        echo '<script>alert("The price must be a number.")</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit;
    }

    if ($price < 0) {
        echo '<script>alert("The price must not be negative.")</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit;
    }

    $sql = "UPDATE item SET ref_no='$ref_no', name='$name', price='$price' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
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
                    <label for="exampleInputEmail1" class="form-label">Reference Number</label>
                    <input name="ref_no" value="<?= $item['ref_no'] ?>" type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Name</label>
                    <input name="name" value="<?= $item['name'] ?>" type="text" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Price</label>
                    <input name="price" value="<?= $item['price'] ?>" type="text" class="form-control">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="index.php" type="submit" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>

    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
</body>

</html>
