<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
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
                        <h3 class="fw-bold h4 m-0 text-white">Edit Invoice</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'invoice' ?>">Invoices Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Invoice</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Invoice</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Invoice Code</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-control-plaintext fs-5 fw-bold text-primary bg-body-secondary border rounded px-3 py-2 mb-0">
                                        <i class="bi bi-upc-scan me-2"></i><span id="noFakturText"><?= $invoices['invoice_code'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Customer Name</label>
                                <select name="customer_id" class="form-select" aria-label="Default select example">
                                    <?php foreach ($customer_data as $customer): ?>
                                        <option value="<?= $customer['id']; ?>" <?= ($invoices['customer_id'] == $customer['id']) ? 'selected' : ''; ?>><?= $customer['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PIC Name</label>
                                <select name="pic_id" class="form-select" aria-label="Default select example">
                                    <?php foreach ($pic_data as $pic): ?>
                                        <option value="<?= $pic['id']; ?>" <?= ($invoices['pic_id'] == $pic['id']) ? 'selected' : ''; ?>><?= $pic['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input value="<?= $invoices['date']; ?>" name="date" type="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input value="<?= $invoices['due_date']; ?>" name="due_date" type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="<?= BASEURL . 'invoice' ?>" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/invoice.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>z
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
</body>

</html>