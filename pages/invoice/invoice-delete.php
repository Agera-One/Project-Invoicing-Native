<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/Invoice.php";

$company_id = $_SESSION['company_id'];

$db = (new Database())->getConnection();
$invoice = new Invoice($db, $company_id);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice_detail = $db->count('invoice_detail', [
        'invoice_id' => $id
    ]);

    $total_payment = $db->count('payment', [
        'invoice_id' => $id
    ]);

    if ($total_invoice_detail > 0 || $total_payment > 0) {
        echo "
        <script>
            alert('The invoice cannot be deleted because it is still being used by another table.');
            window.location.href = 'invoice.php';
        </script>";
        
        exit;
    } else {
        $invoice->delete($id);

        header('Location: invoice.php');
        exit(); 
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
