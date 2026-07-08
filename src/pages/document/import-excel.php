<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file_name'])) {
        if ($_FILES['file_name']['error'] > 0) {
            echo 'Error dengan kode :' . $_FILES['file_name']['error'] . '<br />';
        } else {
            $target_dir = "../../../storage/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $file_extension = pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
            $filename = "import_" . time() . "." . $file_extension;
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES['file_name']['tmp_name'], $target_file)) {
                $handle = fopen($target_file, "r");

                // Lewati baris pertama (Header CSV)
                $header = fgetcsv($handle);

                // Mulai membaca data baris demi baris (diubah ke 0 agar panjang karakter tidak dibatasi)
                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    // Ambil data dan bersihkan spasi/karakter aneh di ujung teks
                    $invoice_code  = trim($data['INVOICE CODE']);
                    $customer_name = trim($data['CUSTOMER NAME']);
                    $raw_date      = trim($data['DATE']);
                    $raw_due_date  = trim($data['DUE DATE']);

                    // Skip jika kode invoice kosong
                    if (empty($invoice_code)) {
                        continue;
                    }

                    // Format ulang tanggal agar bersih dan sesuai standar YYYY-MM-DD MySQL
                    $date     = (!empty($raw_date)) ? date('Y-m-d', strtotime($raw_date)) : null;
                    $due_date = (!empty($raw_due_date)) ? date('Y-m-d', strtotime($raw_due_date)) : null;

                    // 1. Ambil ID customer berdasarkan nama
                    $customer_id = $database->get('customer', 'id', [
                        'name' => $customer_name
                    ]);

                    // 2. Jika nama customer tidak ditemukan, buat otomatis
                    if (!$customer_id && !empty($customer_name)) {
                        $database->insert('customer', [
                            'name' => $customer_name
                        ]);
                        $customer_id = $database->id();
                    }

                    // 3. Pengaman jika ID tetap kosong
                    if (!$customer_id) {
                        continue;
                    }

                    // 4. LOGIKA UPSERT (Cek keberadaan Invoice Code)
                    $is_invoice_exist = $database->has("invoice", [
                        "invoice_code" => $invoice_code
                    ]);

                    if ($is_invoice_exist) {
                        // Jika SUDAH ADA, lakukan UPDATE
                        $database->update("invoice", [
                            'customer_id'  => $customer_id,
                            'date'         => $date,
                            'due_date'     => $due_date
                        ], [
                            'invoice_code' => $invoice_code
                        ]);
                    } else {
                        // Jika BELUM ADA, lakukan INSERT data baru
                        $database->insert("invoice", [
                            'customer_id'  => $customer_id,
                            'invoice_code' => $invoice_code,
                            'date'         => $date,
                            'due_date'     => $due_date
                        ]);
                    }
                }

                fclose($handle);

                if (file_exists($target_file)) {
                    unlink($target_file);
                }

                header("Location: ../invoice/invoice.php");
                exit();
            } else {
                echo "Gagal memindahkan file ke folder storage.";
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
    <title>Import Data Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Import Invoice via CSV</h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="mb-4">
                                <label for="file_name" class="form-label fw-semibold text-secondary">Pilih File CSV</label>
                                <input type="file" class="form-control" id="file_name" name="file_name" accept=".csv" required>
                                <div class="form-text text-muted mt-2">
                                    Format kolom harus berurutan: <code>Nama Customer, Kode Invoice, Tanggal, Jatuh Tempo</code>.
                                </div>
                            </div>

                            <hr class="text-muted my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="../invoice/invoice.php" class="btn btn-outline-secondary px-4">
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
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