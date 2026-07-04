<?php
require_once '../../connection.php';

$invoice_id = $_GET['invoice_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $invoices = $database->delete('invoice_detail', [
        'id' => $id
    ]);

    header('Location: detail.php?invoice_id=' . $invoice_id);
    exit();
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
