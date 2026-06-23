<?php
require_once '../../connection.php';

$sql = 'SELECT * FROM item';
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body>
    <div class="container mt-5">
        <a href="index-add.php" class="btn btn-primary">Add New Item</a>
        <a href="../customer/customer.php" class="btn btn-warning">Customer Page</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Reference Number</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($items = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <th scope="row"><?= $items['id'] ?></th>
                        <td><?= $items['ref_no'] ?></td>
                        <td><?= $items['name'] ?></td>
                        <td>Rp<?= number_format($items['price'], 2, ',', '.') ?></td>
                        <td>
                            <a class="btn btn-sm btn-success" href="index-edit.php?id=<?= $items['id'] ?>">
                                Edit
                            </a>
                            <a class="btn btn-sm btn-danger" href="index-delete.php?id=<?= $items['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>
