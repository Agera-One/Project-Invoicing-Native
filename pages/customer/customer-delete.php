<?php
require_once "../../config/database.php";
require_once "../../classes/Customer.php";

$db = (new Database())->getConnection();
$customer = new Customer($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice = $db->count('invoice', [
        'customer_id' => $id
    ]);

    $total_payment = $db->count('payment', [
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
        $customer->delete($id);
    
        header('Location: customer.php');
        exit();
    }

} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
