<?php
require_once '../../connection.php';
require '../../../vendor/autoload.php';

use Medoo\Medoo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Mengambil data dari database dengan Medoo
$invoices = $database->select('invoice', [
    '[><]customer' => ['customer_id' => 'id'],
], [
    'invoice.id',
    'invoice.invoice_code',
    'invoice.date',
    'invoice.due_date',
    'customer.name(customer_name)',
    'item_count' => Medoo::raw('(SELECT COUNT(*) FROM invoice_detail WHERE invoice_detail.invoice_id=invoice.id)'),
    'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id=invoice.id)'),
    'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id=invoice.id)')
], [
    'ORDER' => [
        'invoice.date' => 'DESC',
        'invoice.id' => 'DESC'
    ]
]);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Invoice Report');

// Mengaktifkan gridline agar terlihat rapi
$sheet->setShowGridLines(true);

// 1. Header Tabel Sesuai Gambar (7 Kolom)
$headers = ['NO', 'INVOICE CODE', 'CUSTOMER NAME', 'DATE', 'DUE DATE', 'TOTAL BILL', 'STATUS'];
$row = 1; // Memulai langsung dari baris pertama agar sederhana

foreach (range('A', 'G') as $i => $col) {
    $sheet->setCellValue($col . $row, $headers[$i]);
}

// Styling Header (Teks Bold & Warna Background Abu-abu Gelap Modern)
$sheet->getStyle('A1:G1')->getFont()->setBold(true)->getColor()->setARGB('FFFFFF');
$sheet->getStyle('A1:G1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2F3542');
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->freezePane('A2');
$sheet->setAutoFilter('A1:G1');

// 2. Mengisi Data Invoice ke Baris Excel
$r = 2;
$no = 1;

foreach ($invoices as $inv) {
    // Menentukan status pembayaran
    if ($inv['item_count'] == 0) {
        $status = 'No Item';
    } elseif ($inv['total_payment'] == 0) {
        $status = 'Unpaid';
    } elseif ($inv['total_payment'] < $inv['total_bill']) {
        $status = 'Partially Paid';
    } else {
        $status = 'Paid';
    }

    $sheet->fromArray([
        $no++,
        $inv['invoice_code'],
        $inv['customer_name'],
        \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($inv['date'])),
        \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($inv['due_date'])),
        $inv['total_bill'],
        $status
    ], NULL, 'A' . $r);

    // Format Tanggal (YYYY-MM-DD sesuai tampilan gambar Anda)
    $sheet->getStyle("D$r:E$r")->getNumberFormat()->setFormatCode('yyyy-mm-dd');

    // Format Mata Uang (Rp Rupiah tanpa desimal)
    $sheet->getStyle("F$r")->getNumberFormat()->setFormatCode('"Rp"#,##0');

    $r++;
}

// 3. Styling dan Perapian Layout Akhir
$lastRow = $r - 1;

// Memberikan border tipis pada seluruh cell data
$sheet->getStyle('A1:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Auto-size lebar kolom berdasarkan kontennya
foreach (range('A', 'G') as $c) {
    $sheet->getColumnDimension($c)->setAutoSize(true);
}

// Format Alignments (Rata Tengah untuk No, Tanggal, dan Status)
foreach (['A', 'D', 'E', 'G'] as $c) {
    $sheet->getStyle($c . '2:' . $c . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Format Alignment (Rata Kanan untuk Total Bill)
$sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

// 4. Proses Download File Excel (.xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Invoice_Report_' . date('Ymd_His') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
