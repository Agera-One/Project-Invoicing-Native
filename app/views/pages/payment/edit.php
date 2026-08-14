<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
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
                        <h3 class="fw-bold h4 m-0 text-white">Edit Payment</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'payment' ?>">Payment Transactions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Payment</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Form Payment Invoice</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Choose Invoice <span class="text-danger">*</span></label>
                                <select name="invoice_id" id="invoice-select" class="form-select" aria-label="Default select example" required>
                                    <?php foreach ($invoice_data as $invoice):
                                        $remaining = $invoice['total_bill'] - $invoice['total_amount_paid']; ?>
                                        <option
                                            value="<?= $invoice['id'] ?>"
                                            data-code="<?= htmlspecialchars($invoice['invoice_code']) ?>"
                                            data-customer="<?= htmlspecialchars($invoice['customer_name']) ?>"
                                            data-date="<?= htmlspecialchars($invoice['date']) ?>"
                                            data-due-date="<?= htmlspecialchars($invoice['due_date']) ?>"
                                            data-total="<?= (int) $invoice['total_bill'] ?>"
                                            data-paid="<?= (int) $invoice['total_amount_paid'] ?>"
                                            data-remaining="<?= (int) $remaining ?>"
                                            <?= ($payment_data['invoice_id'] == $invoice['id']) ? 'selected' : ''; ?>>
                                            <?= $invoice['invoice_code'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="bg-body-secondary bg-opacity-10 border rounded-2 p-3 mb-3" id="invoice-summary-card" style="<?= $selected_invoice ? '' : 'display:none;' ?>">
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Invoice Code</span>
                                    <span class="fw-semibold" id="summary-code"><?= $selected_invoice['invoice_code'] ?? '-' ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Customer</span>
                                    <span class="fw-semibold" id="summary-customer"><?= $selected_invoice['customer_name'] ?? '-' ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Invoice Date</span>
                                    <span id="summary-date"><?= $selected_invoice['date'] ?? '-' ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Due Date</span>
                                    <span id="summary-due-date"><?= $selected_invoice['due_date'] ?? '-' ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Total Bill</span>
                                    <span id="summary-total">
                                        Rp<?= number_format($selected_invoice['total_bill'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Amount Paid</span>
                                    <span id="summary-paid">
                                        Rp<?= number_format($selected_invoice['total_amount_paid'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-muted">Remaining Unpaid</span>
                                    <span class="fw-bold fs-4 text-danger" id="summary-remaining">
                                        Rp<?= number_format(($selected_invoice['total_bill'] ?? 0) - ($selected_invoice['total_amount_paid'] ?? 0), 0, ',', '.') ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Payment Code</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-control-plaintext fs-5 fw-bold text-primary bg-body-secondary border rounded px-3 py-2 mb-0">
                                        <i class="bi bi-upc-scan me-2"></i><span id="noFakturText"><?= $payment_data['payment_code'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Date</label>
                                <input value="<?= $_POST['date'] ?? $payment_data['date'] ?? ''; ?>" name="date" type="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount Paid</label>
                                <input value="<?= $_POST['amount'] ?? $payment_data['amount'] ?? ''; ?>" name="amount" id="amount-input" type="number" min="1" class="form-control" required>
                                <div class="form-text" id="amount-hint">
                                    <?= $selected_invoice ? 'Max: Rp' . number_format(($selected_invoice['total_bill'] - $selected_invoice['total_amount_paid']), 0, ',', '.') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="<?= BASEURL . 'payment' ?>" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/payment.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
</body>

</html>