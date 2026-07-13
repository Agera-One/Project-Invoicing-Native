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

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    } else {
        $password = $user['password'];
    }

    $check_email = count($database->select('user', 'email', [
        'AND' => [
            'email' => $email,
            'id[!]' => $id
        ]
    ]));

    if ($check_email > 0) {
        echo '<script>alert("Email already exists. Please use a different email.")</script>';
    } elseif (strlen($name) > 255) {
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif (strlen($email) > 50) {
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } else {
        $database->update('user', [
            'name' => $name,
            'password' => $password,
            'email' => $email,
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
        <?php include '../../components/navbar.php'; ?>

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
                                <label class="form-label">Name</label>
                                <input value="<?= htmlspecialchars($user['name']) ?>" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <!-- Ubah type ke password, hapus required, dan kosongkan value-nya -->
                                <input name="password" type="password" class="form-control" placeholder="Leave blank if you don't want to change it">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input value="<?= htmlspecialchars($user['email']) ?>" name="email" type="email" class="form-control" required>
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