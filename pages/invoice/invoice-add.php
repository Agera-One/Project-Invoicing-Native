<?php
require_once '../../connection.php';

$sql = 'SELECT id, name FROM customer';
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code_invoice = $_POST['code_invoice'];
    $date = $_POST['date'];

    $checkCode = "SELECT code_invoice FROM invoice WHERE code_invoice='$code_invoice'";
    $result = mysqli_query($conn, $checkCode);

    if (mysqli_num_rows($result) > 0) {
        echo '<script>alert("Code invoice already exists. Please use a different code invoice.")</script>';
        echo '<script>window.location.href = "invoice-add.php";</script>';
        exit;
    } else {
        $sql = "INSERT INTO customer (code_invoice, date) VALUES ('$code_invoice', '$date');";

        if (mysqli_query($conn, $sql)) {
            header("Location: customer.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
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
                        <label class="form-label">Code Invoice</label>
                        <input name="code_invoice" type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <select name="customer_id" class="form-select" aria-label="Default select example">
                            <option selected>Select customer name</option>
                            <?php while ($customers = mysqli_fetch_assoc($result)): ?>
                                <option value="<?= $customers['id']; ?>"><?= $customers['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input name="date" type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input name="address" type="text" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="customer.php" type="submit" class="btn btn-danger">Cancel</a>
                </div>
        </form>
    </div>

    <script src="../../assets//admin-lte/dist/js/adminlte.js"></script>
</body>

</html>