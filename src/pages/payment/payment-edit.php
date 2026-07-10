<?php
require_once '../../connection.php';
include '../../components/scripts.php';

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
    'customer.name(customer_name)',
    'total_due' => Medoo::raw('SUM(<invoice_detail.amount>)')
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_data = $_POST['invoice_data'];

    if (!empty($invoice_data)) {
        $data_splitting = explode('-', $invoice_data);
        $customer_id = $data_splitting[0];
        $invoice_id  = $data_splitting[1];
        $payment_code = $_POST['payment_code'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];

        $check_code = count($database->select('payment', 'payment_code', [
            'AND' => [
                'payment_code' => $payment_code,
                'id[!]' => $id
            ]
        ]));

        if (strlen($payment_code) > 10) {
            echo '<script>alert("The maximum payment code is 10 character.")</script>';
        } elseif ($check_code > 0) {
            echo '<script>alert("Code payment already exists. Please use a different code payment.")</script>';
        } else {
            $payments = $database->update('payment', [
                'customer_id' => $customer_id,
                'invoice_id' => $invoice_id,
                'payment_code' => $payment_code,
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
                        <div class="card-title">Edit Payment</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Invoice</label>
                                    <select name="invoice_data" class="form-select" aria-label="Default select example" required>
                                        <?php foreach ($invoices as $invoice): ?>
                                            <option value="<?= $invoice['customer_id'] . '-' . $invoice['id']; ?>" <?= ($invoice_id == $invoice['id']) ? 'selected' : ''; ?>>
                                                <?= $invoice['invoice_code'] . ' -- ' . $invoice['customer_name'] . ' (Rp' . number_format($invoice['total_due'], 0, ',', '.') . ')'; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Code</label>
                                    <input value="<?= $payment['payment_code'] ?? ''; ?>" name="payment_code" type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Date</label>
                                    <input value="<?= $payment['date'] ?? ''; ?>" name="date" type="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount Paid</label>
                                    <input value="<?= $payment['amount'] ?? ''; ?>" name="amount" type="number" class="form-control" required>
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