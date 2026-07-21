<?php
session_start();
require_once '../../connection.php';
include '../../functions/functions.php';

function is_customer_code_taken_by_other($database, $customer_code, $checkCondition)
{
    $owner_id = $database->get('customer', 'id', ['customer_code' => $customer_code]);

    if ($owner_id === null) {
        return false;
    }

    $current_id = $database->get('customer', 'id', $checkCondition);

    return $owner_id != $current_id;
}

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

                $header = fgetcsv($handle, 0, ",");

                if ($header) {
                    $header = array_map(function ($h) {
                        return trim(strtoupper(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
                    }, $header);
                }

                $import_errors   = [];
                $skipped_rows    = [];
                $imported_count  = 0;
                $updated_count   = 0;
                $row_number      = 1;
                $used_codes_this_batch = [];

                if (!$header || !in_array('NAME', $header, true)) {
                    $import_errors[] = "Invalid CSV header format. The required \"NAME\" column was not found. Make sure the column order follows the template: NO, NAME, EMAIL, PHONE, ADDRESS.";
                }

                while (empty($import_errors) && ($row = fgetcsv($handle, 0, ",")) !== false) {
                    $row_number++;
                    if ($row === [null] || (count($row) === 1 && trim((string) $row[0]) === '')) {
                        continue;
                    }

                    $original_row = $row;

                    $header_count = count($header);
                    $row_count    = count($row);

                    if ($row_count < $header_count) {
                        $row = array_pad($row, $header_count, '');
                    } elseif ($row_count > $header_count) {
                        $row = array_slice($row, 0, $header_count);
                    }

                    $data = array_combine($header, $row);

                    $customer_code    = isset($data['CUSTOMER CODE']) ? trim($data['CUSTOMER CODE']) : '';
                    $name             = isset($data['NAME']) ? trim($data['NAME']) : '';
                    $email            = isset($data['EMAIL']) ? trim($data['EMAIL']) : '';
                    $phone            = isset($data['PHONE']) ? trim($data['PHONE']) : '';
                    $address          = isset($data['ADDRESS']) ? trim($data['ADDRESS']) : '';

                    if (empty($customer_code) && empty($name) && empty($email) && empty($phone) && empty($address)) {
                        $skipped_rows[] = [
                            'row'    => $row_number,
                            'reason' => 'Empty row, no data found in any column.',
                        ];
                        continue;
                    }

                    if (empty($name)) {
                        $skipped_rows[] = [
                            'row'    => $row_number,
                            'reason' => 'NAME column is empty. Row skipped because name is required.',
                        ];
                        continue;
                    }

                    $missing_fields = [];
                    if (empty($email)) {
                        $missing_fields[] = 'EMAIL';
                    }
                    if (empty($phone)) {
                        $missing_fields[] = 'PHONE';
                    }
                    if (empty($address)) {
                        $missing_fields[] = 'ADDRESS';
                    }

                    if (!empty($missing_fields)) {
                        $skipped_rows[] = [
                            'row'    => $row_number,
                            'reason' => implode(', ', $missing_fields) . ' column' . (count($missing_fields) > 1 ? 's are' : ' is') . ' empty. Row skipped because it is required.',
                        ];
                        continue;
                    }

                    $checkCondition = !empty($email) ? ["email" => $email] : ["name" => $name];

                    if (empty($customer_code)) {
                        $customer_code = generate_code($database, "customer", "customer_code", "INV");
                        $used_codes_this_batch[] = $customer_code;
                    } else {
                        if (!preg_match('/^INV-\d{4}-\d{4}$/', $customer_code)) {
                            $skipped_rows[] = [
                                'row'    => $row_number,
                                'reason' => "Invalid CUSTOMER CODE format (must be INV-MMDD-XXXX): \"{$customer_code}\".",
                            ];
                            continue;
                        }

                        if (in_array($customer_code, $used_codes_this_batch, true)) {
                            $skipped_rows[] = [
                                'row'    => $row_number,
                                'reason' => "CUSTOMER CODE \"{$customer_code}\" duplicates another row in the same file.",
                            ];
                            continue;
                        }

                        if (is_customer_code_taken_by_other($database, $customer_code, $checkCondition)) {
                            $skipped_rows[] = [
                                'row'    => $row_number,
                                'reason' => "CUSTOMER CODE \"{$customer_code}\" is already used by another customer.",
                            ];
                            continue;
                        }

                        $used_codes_this_batch[] = $customer_code;
                    }

                    $is_customer_exist = $database->has("customer", $checkCondition);

                    if ($is_customer_exist) {
                        $database->update("customer", [
                            'customer_code'    => $customer_code,
                            'name'             => $name,
                            'phone'            => $phone,
                            'address'          => $address
                        ], $checkCondition);
                        $updated_count++;
                    } else {
                        $database->insert("customer", [
                            'customer_code'    => $customer_code,
                            'name'             => $name,
                            'email'            => !empty($email) ? $email : null,
                            'phone'            => $phone,
                            'address'          => $address
                        ]);
                        $imported_count++;
                    }
                }

                fclose($handle);

                if (file_exists($target_file)) {
                    unlink($target_file);
                }

                if (empty($import_errors) && empty($skipped_rows)) {
                    header("Location: ../customer/customer.php?import=success&imported=" . $imported_count . "&updated=" . $updated_count);
                    exit();
                }
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
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Import CSV</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item text-decoration-none"><a href="../customer/customer.php">Customers Management</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Import CSV</li>
                            </ol>
                        </ol>
                    </div>
                </div>

                <?php if (!empty($import_errors)): ?>
                    <div class="alert alert-danger shadow-sm border-0">
                        <h6 class="fw-bold mb-2"><i class="bi bi-x-circle me-1"></i> Import Failed</h6>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($import_errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif (isset($imported_count)): ?>
                    <div class="alert alert-success shadow-sm border-0">
                        <i class="bi bi-check-circle me-1"></i>
                        Import complete: <strong><?= $imported_count ?></strong> new record(s) added,
                        <strong><?= $updated_count ?></strong> record(s) updated.
                    </div>

                    <?php if (!empty($skipped_rows)): ?>
                        <div class="alert alert-warning shadow-sm border-0">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <?= count($skipped_rows) ?> row(s) skipped
                            </h6>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($skipped_rows as $skip): ?>
                                    <li>Row <?= (int) $skip['row'] ?>: <?= htmlspecialchars($skip['reason']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

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
                                    Column structure must match the export template: <code>CUSTOMER CODE, NAME, EMAIL, PHONE, ADDRESS</code> (Comma Separated).
                                    <br>
                                    <code>CUSTOMER CODE</code> is optional — leave it blank and one will be generated automatically in the format <code>INV-MMDD-XXXX</code>. If filled in, it must follow that same format and must be unique.
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
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>