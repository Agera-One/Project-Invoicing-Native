<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $invoices = $database->delete('invoice', [
        'id' => $id
    ]);

    header('Location: invoice.php');
    exit(); 
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
