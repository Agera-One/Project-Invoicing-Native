<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

$user = $database->get('user', '*', [
    'id' => $id
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $confirm_password = $_POST['confirm_password'];

    $error = false;

    if (!empty($_POST['password']) || !empty($confirm_password)) {
        if (empty($_POST['password']) || empty($confirm_password)) {
            $error = true;
            echo "<script>alert('Password and Confirm Password are required.');</script>";
        } elseif ($_POST['password'] !== $confirm_password) {
            $error = true;
            echo "<script>alert('Password and Confirm Password do not match.');</script>";
        } elseif (strlen($_POST['password']) < 8 && strlen($confirm_password) < 8) {
            $error = true;
            echo "<script>alert('Password must be at least 8 characters.');</script>";
        }
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    } elseif (empty($_POST['password']) && empty($confirm_password)) {
        $password = $user['password'];
    }

    $check_email = count($database->select('user', 'email', [
        'AND' => [
            'email' => $email,
            'id[!]' => $id
        ]
    ]));

    if ($check_email > 0) {
        $error = true;
        echo '<script>alert("Email already exists. Please use a different email.")</script>';
    } elseif (strlen($name) > 255) {
        $error = true;
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif (strlen($email) > 50) {
        $error = true;
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } elseif ($error === false) {
        $database->update('user', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
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
    <title>Edit User</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Edit User</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../user-management/user.php">User Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Item</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input value="<?= htmlspecialchars($user['name']) ?>" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input value="<?= htmlspecialchars($user['email']) ?>" name="email" type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" placeholder="Leave blank if you don't want to change it">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input name="confirm_password" type="password" class="form-control" placeholder="Re-enter your new password">
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

    <?php include '../../components/scripts.php'; ?>
</body>

</html>