<?php
require_once '../../connection.php';

$sql = 'SELECT invoice.*, customer.name AS customer_name
        FROM invoice
        INNER JOIN customer
        ON invoice.customer_id = customer.id';
        
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
        <a href="invoice-add.php" class="btn btn-primary">Add New Invoice</a>
        <a href="../index/index.php" class="btn btn-warning">Index Page</a>
        <a href="../customer/customer.php" class="btn btn-warning">Customer Page</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Code Invoice</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($invoices = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <th scope="row"><?= $invoices['id'] ?></th>
                        <td><?= $invoices['code_invoice'] ?></td>
                        <td><?= $invoices['customer_name'] ?></td>
                        <td><?= $invoices['date'] ?></td>
                        <td>
                            <a class="btn btn-sm btn-success" href="invoice-edit.php?id=<?= $invoices['id'] ?>">
                                Edit
                            </a>
                            <a class="btn btn-sm btn-danger" href="invoice-delete.php?id=<?= $invoices['id'] ?>"
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