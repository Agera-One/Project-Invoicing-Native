<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PIC</title>
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
                        <h3 class="fw-bold h4 m-0 text-white">Edit PIC</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'pic' ?>">Person in Charge (PIC)</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit PIC</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Item</div>
                    </div>
                    <form id="picForm" action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input name="name" value="<?= $name ?>" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input name="phone" value="<?= $phone ?>" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" value="<?= $email ?>" type="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <input name="position" value="<?= $position ?>" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status PIC</label>
                                <select name="is_active" class="form-select" aria-label="Default select example" required>
                                    <option value="" disabled selected>Select status PIC</option>
                                    <option value="1" <?= ($is_active == '1') ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?= ($is_active == '0') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="<?= BASEURL . 'pic' ?>" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>z
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/pic.js' ?>"></script>
</body>

</html>