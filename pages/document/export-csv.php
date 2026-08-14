<?php
require_once '../../connection.php';

$customers = $database->select('customer', [
    'id',
    'customer_code',
    'name',
    'email',
    'phone',
    'address'
], [
    'ORDER' => [
        'id' => 'ASC'
    ]
]);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Customer_Report_' . date('Ymd_His') . '.csv"');
header('Cache-Control: max-age=0');

$output = fopen('php://output', 'w');

$headers = ['NO', 'NAME', 'EMAIL', 'PHONE', 'ADDRESS'];
fputcsv($output, $headers, ',');

$no = 1;
foreach ($customers as $customer) {
    $row_data = [
        $no++,
        $customer['customer_code'],
        $customer['name'],
        $customer['email'],
        $customer['phone'],
        $customer['address']
    ];

    fputcsv($output, $row_data, ',');
}

fclose($output);
exit;
