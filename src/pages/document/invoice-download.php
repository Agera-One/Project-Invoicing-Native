<?php
require_once '../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

ob_start();
include 'generate-pdf.php';
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('Invoice.pdf', [
    'Attachment' => true
]);
