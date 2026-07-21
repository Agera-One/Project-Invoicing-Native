<?php
session_start();
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    if ($id == $_SESSION['user_id']) {
        echo "
        <script>
            alert('You cannot delete the account yourself.');
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