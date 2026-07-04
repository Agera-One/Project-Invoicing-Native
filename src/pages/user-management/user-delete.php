<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $items = $database->delete('user', [
        'id' => $id
    ]);
    header('Location: user.php');
    exit();
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}