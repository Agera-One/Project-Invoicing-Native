<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice = $database->count('invoice', [
        'user_id' => $id
    ]);

    if ($total_invoice > 0) {
        echo "
        <script>
            alert('The user cannot be deleted because it is still being used by another table.');
            window.location.href = 'user.php';
        </script>";

        exit;
    } else {
        $users = $database->delete('user', [
            'id' => $id
        ]);

        header('Location: user.php');
        exit();
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}