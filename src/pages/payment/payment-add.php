<?php
require_once '../../connection.php';
include '../../components/scripts.php';

use Medoo\Medoo;

$invoices = $database->select('invoice', [
    '[><]customer' => ['customer_id' => 'id'],
    '[><]invoice_detail' => ['id' => 'invoice_id']
], [
    'invoice.id',
    'invoice.invoice_code',
    'invoice.customer_id',
    'customer.name(customer_name)',
    'total_bill' => Medoo::raw('SUM(<invoice_detail.amount>)')
], [
    'GROUP' => [
        'invoice.id',
        'invoice.invoice_code',
        'invoice.customer_id',
        'customer.name'
    ]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_data = $_POST['invoice_data'];

    if (!empty($invoice_data) && $invoice_data !== 'Select invoice') {
        $data_splitting = explode('-', $invoice_data);
        $customer_id = $data_splitting[0];
        $invoice_id  = $data_splitting[1];
        $payment_code = $_POST['payment_code'];
        $date = $_POST['date'];
        $amount = (int)$_POST['amount'];

        if (strlen($payment_code) > 10) {
            echo '<script>alert("The maximum payment code is 10 character.")</script>';
        } else {
            $check_code = $database->count('payment', ['payment_code' => $payment_code]);

            if ($check_code > 0) {
                echo '<script>alert("Code payment already exists. Please use a different code payment.")</script>';
            } else {
                $total_bill_query = $database->select('invoice_detail', 'amount', ['invoice_id' => $invoice_id]);
                $total_bill = array_sum($total_bill_query) ?? 0;

                $total_paid_query = $database->select('payment', 'amount', ['invoice_id' => $invoice_id]);
                $total_already_paid = array_sum($total_paid_query) ?? 0;

                $remaining_bill = $total_bill - $total_already_paid;

                if ($amount > $remaining_bill) {
                    echo '<script>alert("Failed! Payment amount (Rp ' . number_format($amount, 0, ',', '.') . ') exceeds the remaining balance due (Rp ' . number_format($remaining_bill, 0, ',', '.') . ').")</script>';
                } else {
                    $payments = $database->insert('payment', [
                        'customer_id' => $customer_id,
                        'invoice_id' => $invoice_id,
                        'payment_code' => $payment_code,
                        'date' => $date,
                        'amount' => $amount
                    ]);

                    header("Location: payment.php");
                    exit();
                }
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
    <title>Document</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Add Payment</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Invoice</label>
                                    <select name="invoice_data" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled selected>Select invoice</option>
                                        <?php foreach ($invoices as $invoice): ?>
                                            <option value="<?= $invoice['customer_id'] . '-' . $invoice['id']; ?>">
                                                <?= $invoice['invoice_code'] . ' -- ' . $invoice['customer_name'] . ' (Rp' . number_format($invoice['total_bill'], 0, ',', '.') . ')'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Code</label>
                                    <input value="<?= $payment_code ?? ''; ?>" name="payment_code" type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Date</label>
                                    <input value="<?= $date ?? ''; ?>" name="date" type="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount Paid</label>
                                    <input value="<?= $amount ?? ''; ?>" name="amount" type="number" class="form-control" required>
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
</body>

</html>