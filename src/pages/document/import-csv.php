<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file_name'])) {
        if ($_FILES['file_name']['error'] > 0) {
            echo 'Error with code: ' . $_FILES['file_name']['error'] . '<br />';
        } else {
            $target_dir = "../../../storage/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $file_extension = pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
            
            if (strtolower($file_extension) !== 'csv') {
                echo "Invalid file format. Only .csv files are allowed!";
                exit();
            }

            $filename = "import_customer_" . time() . "." . $file_extension;
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES['file_name']['tmp_name'], $target_file)) {
                $handle = fopen($target_file, "r");

                // Get the first row as CSV Header
                $header = fgetcsv($handle, 0, ",");

                if ($header) {
                    // Clean hidden characters / BOM from header if any
                    $header = array_map(function($h) {
                        return trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h));
                    }, $header);
                }

                // Read data row by row
                while (($row = fgetcsv($handle, 0, ",")) !== false) {
                    if (count($header) !== count($row)) {
                        continue;
                    }

                    $data = array_combine($header, $row);

                    // Fetch data based on uppercase CSV headers
                    $name    = isset($data['NAME']) ? trim($data['NAME']) : '';
                    $email   = isset($data['EMAIL']) ? trim($data['EMAIL']) : '';
                    $phone   = isset($data['PHONE']) ? trim($data['PHONE']) : '';
                    $address = isset($data['ADDRESS']) ? trim($data['ADDRESS']) : '';

                    // Skip if name is empty
                    if (empty($name)) {
                        continue;
                    }

                    // UPSERT Logic: Check existence by Email, or by Name if email is empty
                    $checkCondition = !empty($email) ? ["email" => $email] : ["name" => $name];
                    $is_customer_exist = $database->has("customer", $checkCondition);

                    if ($is_customer_exist) {
                        // If EXISTS, update data
                        $database->update("customer", [
                            'name'    => $name,
                            'phone'   => $phone,
                            'address' => $address
                        ], $checkCondition);
                    } else {
                        // If NOT EXISTS, insert new record
                        $database->insert("customer", [
                            'name'    => $name,
                            'email'   => !empty($email) ? $email : null,
                            'phone'   => $phone,
                            'address' => $address
                        ]);
                    }
                }

                fclose($handle);

                if (file_exists($target_file)) {
                    unlink($target_file);
                }

                // Redirect back to customer management page
                header("Location: ../customer/customer.php");
                exit();
            } else {
                echo "Failed to move uploaded file to storage directory.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Customer Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Import Customer via CSV</h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="mb-4">
                                <label for="file_name" class="form-label fw-semibold text-secondary">Choose CSV File</label>
                                <input type="file" class="form-control" id="file_name" name="file_name" accept=".csv" required>
                                <div class="form-text text-muted mt-2">
                                    Column structure must match the export template: <code>NO, NAME, EMAIL, PHONE, ADDRESS</code> (Comma Separated).
                                </div>
                            </div>

                            <hr class="text-muted my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="../customer/customer.php" class="btn btn-outline-secondary px-4">
                                    Back
                                </a>
                                <button type="submit" class="btn btn-success px-4">
                                    Upload & Import
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>