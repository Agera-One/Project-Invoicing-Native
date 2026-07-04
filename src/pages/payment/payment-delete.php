<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $payments = $database->delete('payment', [
        'id' => $id
    ]);

    header('Location: payment.php');
    exit();
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
