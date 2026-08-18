<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/bootstrap.css' ?>">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once __DIR__ . '/../../components/navbar.php' ?>
        <?php include_once __DIR__ . '/../../components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Edit User</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'user' ?>">User Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Item</div>
                    </div>
                    <form action="" method="POST" id="userForm" novalidate>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input value="<?= htmlspecialchars($data['name']) ?>" id="name" name="name" type="text" class="form-control" required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input value="<?= htmlspecialchars($data['email']) ?>" id="email" name="email" type="email" class="form-control" required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Leave blank if you don't want to change it">
                                <div class="invalid-feedback" id="passwordError"></div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input id="confirm_password" name="confirm_password" type="password" class="form-control" placeholder="Re-enter your new password">
                                    <div class="invalid-feedback" id="confirmPasswordError"></div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="<?= BASEURL . 'user' ?>" class="btn btn-danger">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/user.js' ?>"></script>
</body>

</html>