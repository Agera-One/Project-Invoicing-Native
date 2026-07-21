<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice_detail = $database->count('invoice_detail', [
        'invoice_id' => $id
    ]);

    $total_payment = $database->count('payment', [
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
        $invoices = $database->delete('invoice', [
            'id' => $id
        ]);

        header('Location: invoice.php');
        exit(); 
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
