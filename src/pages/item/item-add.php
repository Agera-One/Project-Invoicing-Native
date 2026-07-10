<?php
require_once '../../connection.php';
include '../../components/scripts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (strlen($ref_no) > 8) {
        echo '<script>alert("Maximum reference number length is 8 characters.")</script>';
    } elseif ($price < 1) {
        echo '<script>alert("The minimum price is 1.")</script>';
    } elseif (strlen($name) > 255) {
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } else {
        $check_ref_no = count($database->select('item', 'ref_no', [
            'ref_no' => $ref_no
        ]));

        if ($check_ref_no > 0) {
            echo '<script>alert("Reference number already exists. Please use a different reference number.")</script>';
        } else {
            $items = $database->insert('item', [
                'ref_no' => $ref_no,
                'name' => $name,
                'price' => $price
            ]);

            header("Location: item.php");
            exit();
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
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add New Item</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Reference Number</label>
                                <input value="<?= $ref_no ?? ''; ?>" name="ref_no" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Name</label>
                                <input value="<?= $name ?? ''; ?>" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Price</label>
                                <input value="<?= $price ?? ''; ?>" name="price" type="number" class="form-control" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="item.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>