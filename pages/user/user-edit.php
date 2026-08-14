<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/User.php";
require_once '../../src/functions/functions.php';

$db = (new Database())->getConnection();
$user = new User($db);

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

$data = $user->find($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $error = false;

    if (!empty($_POST['password']) || !empty($_POST['confirm_password'])) {
        if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
            $error = true;
            echo "<script>alert('Password and Confirm Password are required.');</script>";
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $error = true;
            echo "<script>alert('Password and Confirm Password do not match.');</script>";
        } elseif (strlen($_POST['password']) < 8 && strlen($_POST['confirm_password']) < 8) {
            $error = true;
            echo "<script>alert('Password must be at least 8 characters.');</script>";
        }
    } elseif (empty($_POST['password']) && empty($_POST['confirm_password'])) {
        $_POST['password'] = $data['password'];
    }

    $email_exists = $db->has('pic', [
        'AND' => [
            'email' => $_POST['email'],
            'id[!]' => $id
        ]
    ]);

    if ($email_exists) {
        $error = true;
        echo '<script>alert("Email already exists. Please use a different email.")</script>';
    } elseif (strlen($_POST['name']) > 255) {
        $error = true;
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif (strlen($_POST['email']) > 50) {
        $error = true;
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } elseif ($error === false) {
        $user->update($id, $_POST);

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
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once '../../src/components/navbar.php' ?>
        <?php include_once '../../src/components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Edit User</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="user.php">User Management</a></li>
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
                                <input value="<?= htmlspecialchars($data['name']) ?>" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input value="<?= htmlspecialchars($data['email']) ?>" name="email" type="email" class="form-control" required>
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

    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>