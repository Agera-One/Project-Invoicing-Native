<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Invoice Item</title>
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/bootstrap.css' ?>">
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once __DIR__ . '/../../components/navbar.php' ?>
        <?php include_once __DIR__ . '/../../components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Add Invoice Item</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'invoice' ?>">Invoices Billing</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'invoice/detail' ?>/<?= $invoice_id ?>">Invoice Details</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Invoice Item</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add Some Item</div>
                    </div>
                    <form action="" method="POST" id="itemDetailForm">
                        <div class="card-body">
                            <div class="mb-3">
                                <input name="invoice_id" value="<?= $invoice_id ?>" type="hidden">
                                <div class="mb-3">
                                    <label class="form-label">Item Name</label>
                                    <select name="item_id" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled selected>Select item name</option>
                                        <?php foreach ($item_data as $item): ?>
                                            <option value="<?= $item['id']; ?>" <?= ($item_id == $item['id']) ? 'selected' : ''; ?>>
                                                <?= $item['name'] . ' = Rp' . number_format($item['price'], 0, ',', '.'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input value="<?= $_POST['quantity'] ?? ''; ?>" name="quantity" id="quantity" type="number" class="form-control" required>
                                    <div id="quantityError" class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unit Price</label>
                                    <input value="<?= $_POST['unit_price'] ?? ''; ?>" name="unit_price" id="unit_price" type="number" class="form-control">
                                    <div id="unitPriceError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="<?= BASEURL . 'invoice/detail' ?>/<?= $invoice_id ?>" class="btn btn-danger">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/detail.js' ?>"></script>
</body>

</html>