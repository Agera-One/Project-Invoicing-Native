<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$extension = ['png', 'jpg', 'jpeg'];

if (isset($_FILES['logo'])) {
    $logo_name = $_FILES['logo']['name'];
    $logo_size = $_FILES['logo']['size'];
    $error = $_FILES['logo']['error'];
    $tmp_name = $_FILES['logo']['tmp_name'];

    if ($error === UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('Select any logo first'); window.history.back();</script>";
        exit;
    }

    $image_extension = explode('.', $logo_name);
    $image_extension = strtolower(end($image_extension));

    if (!in_array($image_extension, $extension)) {
        echo "<script>alert('You must upload an image'); window.history.back();</script>";
        exit;
    }

    if ($logo_size > 2000000) {
        echo "<script>alert('The maximum image size is 2MB'); window.history.back();</script>";
        exit;
    }

    $new_logo_name = 'logo_' . $id . '_' . time() . '.' . $image_extension;

    move_uploaded_file($tmp_name, '../../../storage/' . $new_logo_name);

    $database->update('company', [
        'logo' => $new_logo_name
    ], [
        'id' => $id
    ]);

    header("Location: company.php");
    exit;
} elseif (isset($_FILES['signature'])) {
    $signature_name = $_FILES['signature']['name'];
    $signature_size = $_FILES['signature']['size'];
    $error = $_FILES['signature']['error'];
    $tmp_name = $_FILES['signature']['tmp_name'];

    if ($error === UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('Select a signature first'); window.history.back();</script>";
        exit;
    }

    $image_extension = explode('.', $signature_name);
    $image_extension = strtolower(end($image_extension));

    if (!in_array($image_extension, $extension)) {
        echo "<script>alert('You must upload an image'); window.history.back();</script>";
        exit;
    }

    if ($signature_size > 2000000) {
        echo "<script>alert('The maximum image size is 2MB'); window.history.back();</script>";
        exit;
    }

    $new_signature_name = 'signature_' . $id . '_' . time() . '.' . $image_extension;

    move_uploaded_file($tmp_name, '../../../storage/' . $new_signature_name);

    $database->update('company', [
        'signature' => $new_signature_name
    ], [
        'id' => $id
    ]);

    header("Location: company.php");
    exit;
}
