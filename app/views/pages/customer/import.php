<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Customer Data</title>
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/bootstrap.css' ?>">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once __DIR__ . '/../../components/navbar.php' ?>
        <?php include_once __DIR__ . '/../../components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Import CSV</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                                <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'customer' ?>">Customers Management</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Import CSV</li>
                            </ol>
                        </ol>
                    </div>
                </div>

                <?php if (!empty($import_errors)): ?>
                    <div class="alert alert-danger shadow-sm border-0">
                        <h6 class="fw-bold mb-2"><i class="bi bi-x-circle me-1"></i> Import Failed</h6>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($import_errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif (isset($imported_count)): ?>
                    <div class="alert alert-success shadow-sm border-0">
                        <i class="bi bi-check-circle me-1"></i>
                        Import complete: <strong><?= $imported_count ?></strong> new record(s) added,
                        <strong><?= $updated_count ?></strong> record(s) updated.
                    </div>

                    <?php if (!empty($skipped_rows)): ?>
                        <div class="alert alert-warning shadow-sm border-0">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <?= count($skipped_rows) ?> row(s) skipped
                            </h6>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($skipped_rows as $skip): ?>
                                    <li>Row <?= (int) $skip['row'] ?>: <?= htmlspecialchars($skip['reason']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Import Customer via CSV</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="mb-4">
                                <label for="file_name" class="form-label fw-semibold text-secondary">Choose CSV File</label>
                                <input type="file" class="form-control" id="file_name" name="file_name" accept=".csv" required>
                                <div class="form-text text-muted mt-2">
                                    Column structure must match the export template: <code>CUSTOMER CODE, NAME, EMAIL, PHONE, ADDRESS</code>
                                    <br>
                                    <code>CUSTOMER CODE</code> is optional — leave it blank and one will be generated automatically in the format <code>CUST-YYYY-XXXX</code>. If filled in, it must follow that same format and must be unique.
                                </div>
                            </div>

                            <hr class="text-muted my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?= BASEURL . 'customer' ?>" class="btn btn-outline-secondary px-4">
                                    Back
                                </a>
                                <button type="submit" class="btn btn-success px-4">
                                    Upload & Import
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
</body>

</html>