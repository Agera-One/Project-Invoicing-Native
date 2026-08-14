<?php
require_once "../../config/database.php";
require_once "../../classes/Item.php";

$db = (new Database())->getConnection();
$item = new Item($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice_detail = $db->count('invoice_detail', [
        'item_id' => $id
    ]);

    if ($total_invoice_detail > 0) {
        echo "
        <script>
            alert('The item cannot be deleted because it is still being used by another table.');
            window.location.href = 'item.php';
        </script>";

        exit;
    } else {
        $item->delete($id);

        header('Location: item.php');
        exit();
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}