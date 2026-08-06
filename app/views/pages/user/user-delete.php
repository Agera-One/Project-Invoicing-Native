<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/User.php";

$db = (new Database())->getConnection();
$user = new User($db);

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
        $user->delete($id);

        header('Location: user.php');
        exit();
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}