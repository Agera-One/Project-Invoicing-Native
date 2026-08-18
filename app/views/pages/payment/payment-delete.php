<?php
require_once "../../config/database.php";
require_once "../../classes/Payment.php";

$company_id = $_SESSION['company_id'];

$db = (new Database())->getConnection();
$payment = new Payment($db, $company_id);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $payment->delete($id);

    header('Location: payment.php');
    exit();
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
