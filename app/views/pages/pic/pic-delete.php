<?php
require_once "../../config/database.php";
require_once "../../classes/Pic.php";

$db = (new Database())->getConnection();
$pic = new Pic($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice = $db->count('invoice', [
        'pic_id' => $id
    ]);

    if ($total_invoice > 0) {
        echo "
        <script>
            alert('The pic cannot be deleted because it is still being used by another table.');
            window.location.href = 'pic.php';
        </script>";

        exit;
    } else {
        $pic->delete($id);

        header('Location: pic.php');
        exit();
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}