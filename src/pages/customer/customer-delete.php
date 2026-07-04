<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $customers = $database->delete('customer', [
        'id' => $id
    ]);

    header('Location: customer.php');
    exit();
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
