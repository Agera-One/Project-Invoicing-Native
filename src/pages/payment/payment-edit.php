<?php
session_start();
require_once '../../connection.php';
include '../../functions/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$payment_code = generate_code($database, "payment", "payment_code", "PAY");

use Medoo\Medoo;

$id = $_GET['id'];
$customer_id = $_GET['customer_id'];
$invoice_id = $_GET['invoice_id'];

$payment = $database->get('payment', '*', [
    'id' => $id
]);

$invoices = $database->select('invoice', [
    '[><]customer' => ['customer_id' => 'id'],
    '[><]invoice_detail' => ['id' => 'invoice_id']
], [
    'invoice.id',
    'invoice.invoice_code',
    'invoice.customer_id',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'total_bill' => Medoo::raw('SUM(<invoice_detail.amount>)'),
    'total_amount_paid' => Medoo::raw('(SELECT COALESCE(SUM(payment.amount), 0) FROM payment WHERE payment.invoice_id = <invoice.id> AND payment.id != ' . (int) $id . ')')
], [
    'GROUP' => [
        'invoice.id',
        'invoice.invoice_code',
        'invoice.customer_id',
        'invoice.date',
        'invoice.due_date',
        'customer.name'
    ]
]);

$selected_invoice = null;
foreach ($invoices as $invoice) {
    if ((string)$invoice['id'] === (string)$invoice_id) {
        $selected_invoice = $invoice;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_data = $_POST['invoice_data'];

    if (!empty($invoice_data)) {
        $data_splitting = explode('-', $invoice_data);
        $customer_id = $data_splitting[0];
        $invoice_id  = $data_splitting[1];
        $date = $_POST['date'];
        $amount = (int) $_POST['amount'];

        if ($amount < 1) {
            echo '<script>alert("The minimum amount is 1.")</script>';
        } else {
            $total_bill_query = $database->select('invoice_detail', 'amount', ['invoice_id' => $invoice_id]);
            $total_bill = array_sum($total_bill_query) ?? 0;

            $total_paid_query = $database->select('payment', 'amount', [
                'AND' => [
                    'invoice_id' => $invoice_id,
                    'id[!]' => $id
                ]
            ]);

            $total_already_paid = array_sum($total_paid_query) ?? 0;

            $remaining_bill = $total_bill - $total_already_paid;

            if ($amount > $remaining_bill) {
                echo '<script>alert("Failed! Payment amount (Rp ' . number_format($amount, 0, ',', '.') . ') exceeds the remaining balance due (Rp ' . number_format($remaining_bill, 0, ',', '.') . ').")</script>';
            } else {
                $database->update('payment', [
                    'customer_id' => $customer_id,
                    'invoice_id' => $invoice_id,
                    'date' => $date,
                    'amount' => $amount
                ], [
                    'id' => $id
                ]);

                header("Location: payment.php");
                exit();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Edit Payment</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../payment/payment.php">Payment Transactions</a></li>
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
                                <select name="invoice_data" id="invoice-select" class="form-select" aria-label="Default select example" required>
                                    <?php foreach ($invoices as $invoice):
                                        $remaining = $invoice['total_bill'] - $invoice['total_amount_paid']; ?>
                                        <option
                                            value="<?= $invoice['customer_id'] . '-' . $invoice['id']; ?>"
                                            data-code="<?= htmlspecialchars($invoice['invoice_code']) ?>"
                                            data-customer="<?= htmlspecialchars($invoice['customer_name']) ?>"
                                            data-date="<?= htmlspecialchars($invoice['date']) ?>"
                                            data-due-date="<?= htmlspecialchars($invoice['due_date']) ?>"
                                            data-total="<?= (int) $invoice['total_bill'] ?>"
                                            data-paid="<?= (int) $invoice['total_amount_paid'] ?>"
                                            data-remaining="<?= (int) $remaining ?>"
                                            <?= ($invoice_id == $invoice['id']) ? 'selected' : ''; ?>>
                                            <?= $invoice['invoice_code'] . ' -- ' . $invoice['customer_name'] . ' (Rp' . number_format($invoice['total_bill'], 0, ',', '.') . ')'; ?>
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
                                        <i class="bi bi-upc-scan me-2"></i><span id="noFakturText"><?= $payment['payment_code'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Date</label>
                                <input value="<?= $date ?? $payment['date'] ?? ''; ?>" name="date" type="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount Paid</label>
                                <input value="<?= $amount ?? $payment['amount'] ?? ''; ?>" name="amount" id="amount-input" type="number" min="1" class="form-control" required>
                                <div class="form-text" id="amount-hint">
                                    <?= $selected_invoice ? 'Max: Rp' . number_format(($selected_invoice['total_bill'] - $selected_invoice['total_amount_paid']), 0, ',', '.') : '' ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="payment.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>

    <script>
        const invoiceSelect = document.getElementById('invoice-select');
        const summaryCard = document.getElementById('invoice-summary-card');
        const amountInput = document.getElementById('amount-input');
        const amountHint = document.getElementById('amount-hint');

        const rupiah = (value) => 'Rp' + Number(value).toLocaleString('id-ID');

        function updateInvoiceSummary() {
            const selected = invoiceSelect.options[invoiceSelect.selectedIndex];

            if (!selected || !selected.value) {
                summaryCard.style.display = 'none';
                amountHint.textContent = '';
                amountInput.removeAttribute('max');
                return;
            }

            const total = Number(selected.dataset.total || 0);
            const paid = Number(selected.dataset.paid || 0);
            const remaining = Number(selected.dataset.remaining ?? (total - paid));

            document.getElementById('summary-code').textContent = selected.dataset.code || '-';
            document.getElementById('summary-customer').textContent = selected.dataset.customer || '-';
            document.getElementById('summary-date').textContent = selected.dataset.date || '-';
            document.getElementById('summary-due-date').textContent = selected.dataset.dueDate || '-';
            document.getElementById('summary-total').textContent = rupiah(total);
            document.getElementById('summary-paid').textContent = rupiah(paid);
            document.getElementById('summary-remaining').textContent = rupiah(remaining);

            summaryCard.style.display = '';

            amountHint.textContent = 'Max: ' + rupiah(remaining);
            amountInput.setAttribute('max', remaining);
        }

        invoiceSelect.addEventListener('change', updateInvoiceSummary);
        updateInvoiceSummary();
    </script>
</body>

</html>