<?php
require_once '../../connection.php';
include '../../components/scripts.php';

$id = $_GET['id'];

$user = $database->get('user', '*', [
    'id' => $id
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $status = $_POST['status'];

    $check_email = count($database->select('user', 'email', [
        'AND' => [
            'email' => $email,
            'id[!]' => $id
        ]
    ]));

    $check_phone = count($database->select('user', 'phone', [
        'AND' => [
            'phone' => $phone,
            'id[!]' => $id
        ]
    ]));

    $check_status = count($database->select('user', 'status', [
        'AND' => [
            'status' => 'active',
            'id[!]' => $id
        ]
    ]));

    if ($check_email > 0) {
        echo '<script>alert("Email already exists. Please use a different email.")</script>';
    } elseif ($check_phone > 0) {
        echo '<script>alert("phone already exists. Please use a different phone.")</script>';
    } elseif (strlen($name) > 255) {
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif (strlen($phone) > 15) {
        echo '<script>alert("Maximum phone length is 15 characters.")</script>';
    } elseif (strlen($email) > 50) {
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } elseif (strlen($position) > 50) {
        echo '<script>alert("Maximum position length is 50 characters.")</script>';
    } elseif ($check_status > 0 && $status === 'active') {
        echo '<script>alert("There can only be a maximum of one active PIC.")</script>';
    } else {
        $users = $database->update('user', [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'position' => $position,
            'status' => $status
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

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
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
                                <input value="<?= $user['phone'] ?>" name="phone" type="tel" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Email</label>
                                <input value="<?= $user['email'] ?>" name="email" type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Position</label>
                                <input value="<?= $user['position'] ?>" name="position" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status User</label>
                                <select name="status" class="form-select" aria-label="Default select example" required>
                                    <option value="" disabled selected>Select status user</option>
                                    <option value="active" <?= ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?= ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="user.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>