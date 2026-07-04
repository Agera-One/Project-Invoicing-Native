<?php
require_once '../../connection.php';

$id = $_GET['id'];

$user = $database->get('user', '*', [
    'id' => $id
]);

// foreach ($users as $user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    if (strlen($name) > 255) {
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif ($phone > 15 && $phone < 0) {
        echo '<script>alert("The price must not be negative and maximum phone length is 15 characters.")</script>';
    } elseif (strlen($email) > 50) {
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } elseif (strlen($position) > 50) {
        echo '<script>alert("Maximum position length is 50 characters.")</script>';
    } else {
        $users = $database->update('user', [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'position' => $position
        ], [
            'id' => $id
        ]);

        header("Location: user.php");
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

<body>
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Edit Item</div>
        </div>
        <form action="" method="POST">
            <div class="card-body">
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Name</label>
                    <input value="<?= $user['name'] ?>" name="name" type="text" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Phone</label>
                    <input value="<?= $user['phone'] ?>" name="phone" type="number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Email</label>
                    <input value="<?= $user['email'] ?>" name="email" type="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Position</label>
                    <input value="<?= $user['position'] ?>" name="position" type="text" class="form-control" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="user.php" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>

    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
</body>

</html>