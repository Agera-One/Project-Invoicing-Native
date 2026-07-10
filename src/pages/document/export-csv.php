<?php
require_once '../../connection.php';

// Fetch all data from customer table
$customers = $database->select('customer', [
    'id',
    'name',
    'email',
    'phone',
    'address'
], [
    'ORDER' => [
        'id' => 'ASC'
    ]
]);

// Set HTTP headers to force CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Customer_Report_' . date('Ymd_His') . '.csv"');
header('Cache-Control: max-age=0');

// Open PHP output stream
$output = fopen('php://output', 'w');

// 1. Write CSV Header
$headers = ['NO', 'NAME', 'EMAIL', 'PHONE', 'ADDRESS'];
fputcsv($output, $headers, ',');

// 2. Write Customer Data row by row
$no = 1;
foreach ($customers as $customer) {
    $row_data = [
        $no++,
        $customer['name'],
        $customer['email'],
        $customer['phone'],
        $customer['address']
    ];

    fputcsv($output, $row_data, ',');
}

fclose($output);
exit;
