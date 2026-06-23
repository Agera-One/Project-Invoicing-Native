<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $sql = "DELETE FROM customer WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header('Location: customer.php');
        exit();
    } else {
        echo 'Error deleting record: ' . mysqli_error($conn);
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}
