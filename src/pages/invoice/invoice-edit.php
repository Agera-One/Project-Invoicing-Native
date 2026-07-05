<?php
require_once '../../connection.php';

$id = $_GET['id'];
$customer_id = $_GET['customer_id'];

$invoice = $database->get('invoice', [
    '[><]customer' => ['customer_id' => 'id']
], [
    'invoice.id',
    'invoice.customer_id',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
], [
    'invoice.id' => $id
]);

$customers = $database->select('customer', ['id', 'name']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $invoice_code = $_POST['invoice_code'];
    $date = $_POST['date'];
    $due_date = $_POST['due_date'];

    $check_invoice_code = count($database->select('invoice', 'invoice_code', [
        'AND' => [
            'invoice_code' => $invoice_code,
            'id[!]' => $id
        ]
    ]));

    if (strlen($invoice_code) > 10) {
        echo '<script>alert("The invoice code is limited to a maximum of 10 characters")</script>';
    } elseif ($due_date < $date) {
        echo '<script>alert("The due date must not be earlier than the invoice date")</script>';
    } elseif ($check_invoice_code > 0) {
        echo '<script>alert("Invoice code already exists. Please use a different invoice code.")</script>';
    } else {
        $invoices = $database->update('invoice', [
            'customer_id' => $customer_id,
            'invoice_code' => $invoice_code,
            'date' => $date,
            'due_date' => $due_date
        ], [
            'id' => $id
        ]);

        header("Location: invoice.php");
        exit();
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
</head>

<body>
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Edit Invoice</div>
        </div>
        <form action="" method="POST">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Code Invoice</label>
                    <input value="<?= $invoice['invoice_code']; ?>" name="invoice_code" type="text" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <select name="customer_id" class="form-select" aria-label="Default select example">
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id']; ?>" <?= ($customer_id == $customer['id']) ? 'selected' : ''; ?>><?= $customer['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input value="<?= $invoice['date']; ?>" name="date" type="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input value="<?= $invoice['due_date']; ?>" name="due_date" type="date" class="form-control" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="invoice.php" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>

    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
</body>

</html>