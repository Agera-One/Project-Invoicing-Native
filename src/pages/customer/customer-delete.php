<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice = $database->count('invoice', [
        'customer_id' => $id
    ]);

    $total_payment = $database->count('payment', [
        'customer_id' => $id
    ]);

    if ($total_invoice > 0 || $total_payment > 0) {
        echo "
        <script>
            alert('The customer cannot be deleted because it is still being used by another table.');
            window.location.href = 'customer.php';
        </script>";

        exit;
    } else {
        $customers = $database->delete('customer', [
            'id' => $id
        ]);
    
        header('Location: customer.php');
        exit();
    }

} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
