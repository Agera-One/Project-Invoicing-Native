<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$status = '';
$position_id = '';
$department_id = '';

$positions = $database->select('position', ['id', 'name']);
$departments = $database->select('department', ['id', 'name']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position_id = $_POST['position_id'];
    $department_id = $_POST['department_id'];
    $status = $_POST['status'];

    if (strlen($name) > 255) {
        echo '<script>alert("Maximum name length is 255 characters.")</script>';
    } elseif (strlen($phone) > 15) {
        echo '<script>alert("maximum phone length is 15 characters.")</script>';
    } elseif (strlen($email) > 50) {
        echo '<script>alert("Maximum email length is 50 characters.")</script>';
    } else {
        $check_phone = count($database->select('company_pic', 'phone', [
            'phone' => $phone
        ]));

        $check_email = count($database->select('company_pic', 'email', [
            'email' => $email
        ]));

        $check_status = count($database->select('company_pic', 'status', [
            'status' => 'active'
        ]));

        if ($check_phone > 0) {
            echo '<script>alert("Phone number already exists. Please use a different phone number.")</script>';
        } elseif ($check_email > 0) {
            echo '<script>alert("Email already exists. Please use a different email.")</script>';
        } elseif ($check_status > 0 && $status === 'active') {
            echo '<script>alert("There can only be a maximum of one active PIC.")</script>';
        } else {
            $company_pics = $database->insert('company_pic', [
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'position_id' => $position_id,
                'department_id' => $department_id,
                'status' => $status
            ]);

            header("Location: pic.php");
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
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add New User</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Name</label>
                                <input value="<?= $name ?? ''; ?>" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Phone</label>
                                <input value="<?= $phone ?? ''; ?>" name="phone" type="tel" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Email</label>
                                <input value="<?= $email ?? ''; ?>" name="email" type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <select name="position_id" class="form-select" aria-label="Default select example" required>
                                    <option value="" disabled selected>Select position</option>
                                    <?php foreach ($positions as $position): ?>
                                        <option value="<?= $position['id']; ?>" <?= ($position_id == $position['id']) ? 'selected' : ''; ?>>
                                            <?= $position['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select" aria-label="Default select example" required>
                                    <option value="" disabled selected>Select department</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?= $department['id']; ?>" <?= ($department_id == $department['id']) ? 'selected' : ''; ?>>
                                            <?= $department['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status PIC</label>
                                <select name="status" class="form-select" aria-label="Default select example" required>
                                    <option value="" disabled selected>Select status PIC</option>
                                    <option value="active" <?= ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?= ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="pic.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>