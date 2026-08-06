<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/InvoiceDetail.php";

$db = (new Database())->getConnection();
$invoice_detail = new InvoiceDetail($db, $_SESSION['company_id']);
$invoice_id = $_GET['invoice_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $invoice_detail->delete($id);

    header('Location: detail.php?invoice_id=' . $invoice_id);
    exit();
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
