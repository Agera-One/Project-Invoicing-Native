<?php
require_once '../../connection.php';

$invoice_id = $_GET['invoice_id'];

$details = $database->select('invoice', [
    '[>]customer' => ['customer_id' => 'id'],
    '[>]user' => ['user_id' => 'id'],
    '[>]invoice_detail' => ['id' => 'invoice_id'],
    '[>]item' => ['invoice_detail.item_id' => 'id'],
], [
    'invoice.id(invoice_id)',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'user.name(user_name)',
    'invoice_detail.id',
    'invoice_detail.unit_price',
    'invoice_detail.quantity',
    'invoice_detail.amount',
    'item.id(item_id)',
    'item.name'
], [
    'invoice.id' => $invoice_id
]);

$invoice_details = [];

foreach ($details as $detail) {
    $invoice_details[] = $detail;
}

$invoice = $invoice_details[0];

$total_bill = 0;
foreach ($invoice_details as $invoice_detail) {
    $total_bill += $invoice_detail['amount'];
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
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body p-4 p-md-5">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h2 class="h4 mb-0 text-primary fw-semibold">AdminLTE, Inc.</h2>
                        <p class="text-secondary mb-0 small">
                            795 Folsom Ave, Suite 600<br>
                            San Francisco, CA 94107<br>
                            billing@example.com
                        </p>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h1 class="h2 mb-1">Invoice</h1>
                        <p class="text-secondary mb-0">
                            <span class="fw-semibold">#</span><?= $invoice['invoice_code'] ?>
                        </p>
                    </div>
                </div>

                <!-- Billing details -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <div class="mb-4">
                            <p class="text-secondary small mb-1">Billed to</p>
                            <p class="mb-0 fw-semibold"><?= $invoice['customer_name'] ?></p>
                        </div>
                        <div class="mb-4">
                            <p class="text-secondary small mb-1">Handled by</p>
                            <p class="mb-0 fw-semibold"><?= $invoice['user_name'] ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <p class="text-secondary small mb-1">Issue date</p>
                        <p class="mb-2"><?= $invoice['date'] ?></p>
                        <p class="text-secondary small mb-1">Due date</p>
                        <p class="mb-0"><?= $invoice['due_date'] ?></p>
                    </div>
                </div>

                <!-- Items -->
                <div class="table-responsive mb-3">
                    <table class="table align-middle mb-0" role="table">
                        <thead>
                            <tr>
                                <th class="border-top-0" scope="col">Description</th>
                                <th class="border-top-0 text-end" style="width: 6rem" scope="col">Qty</th>
                                <th class="border-top-0 text-end" style="width: 9rem" scope="col">Unit price</th>
                                <th class="border-top-0 text-end" style="width: 9rem" scope="col">Amount</th>
                                <th class="border-top-0 text-end" style="width: 9rem" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoice_details as $invoice_detail): ?>
                                <?php
                                if (!empty($invoice_detail['item_id'])):
                                    $amount = $invoice_detail['quantity'] * $invoice_detail['unit_price'];
                                ?>
                                    <tr>
                                        <td>
                                            <p class="mb-0 fw-semibold"><?= $invoice_detail['name'] ?></p>
                                        </td>
                                        <td class="text-end"><?= $invoice_detail['quantity'] ?></td>
                                        <td class="text-end">Rp<?= number_format($invoice_detail['unit_price'], 0, ',', '.') ?></td>
                                        <td class="text-end">Rp<?= number_format($invoice_detail['amount'], 0, ',', '.') ?></td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-success" href="detail-edit.php?id=<?= $invoice_detail['id'] ?>&item_id=<?= $invoice_detail['item_id'] ?>&invoice_id=<?= $invoice_detail['invoice_id'] ?>">
                                                Edit
                                            </a>
                                            <a class="btn btn-sm btn-danger" href="detail-delete.php?id=<?= $invoice_detail['id'] ?>&invoice_id=<?= $invoice_detail['invoice_id'] ?>"
                                                onclick="return confirm('Are you sure you want to delete this detail?');">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <a href="detail-add.php?invoice_id=<?= $invoice_id ?>" class="btn btn-primary">Add Item</a>

                <!-- Totals -->
                <div class="row justify-content-end">
                    <div class="col-md-5 col-lg-4">
                        <dl class="row mb-0">
                            <dt class="col-7 fw-semibold border-top pt-2">Total bill</dt>
                            <dd class="col-5 text-end fw-semibold border-top pt-2 mb-0">Rp<?= number_format($total_bill, 0, ',', '.') ?></dd>
                        </dl>
                    </div>
                </div>

                <!-- Footer note -->
                <hr class="my-4">
                <p class="text-secondary small mb-0">
                    Thanks for your business. Payment is due within 14 days. If you have any questions
                    about this invoice, please contact
                    <a href="mailto:billing@example.com">billing@example.com</a>.
                </p>
            </div>

            <a href="../invoice/invoice.php" class="btn btn-danger">Back to invoice</a>
        </div>
    </div>

    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>