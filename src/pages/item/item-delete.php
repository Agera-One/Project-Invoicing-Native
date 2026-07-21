<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    $total_invoice_detail = $database->count('invoice_detail', [
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
        $items = $database->delete('item', [
            'id' => $id
        ]);

        header('Location: item.php');
        exit();
    }
} else {
    echo "<script>
        alert('Invalid request method. Please submit the form.');
    </script>";
}