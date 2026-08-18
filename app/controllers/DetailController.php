<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class DetailController extends BaseController
{
    private $invoiceDetail;
    private $item;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceDetail = $this->model('invoicedetail');
        $this->item = $this->model('item');
        $this->db = $this->invoiceDetail->getConnection();
    }

    public function index($invoice_id)
    {
        $invoice_details = $this->invoiceDetail->getall($invoice_id);

        $invoice = $invoice_details[0];
        $total_bill = 0;

        foreach ($invoice_details as $invoice_detail) {
            $total_bill += $invoice_detail['amount'];
        }

        $datas = [
            'invoice_id' => $invoice_id,
            'invoice_details' => $invoice_details,
            'invoice' => $invoice,
            'total_bill' => $total_bill,
        ];

        $this->view('invoice-detail/index', $datas);
    }

    public function add($invoice_id)
    {
        $item_id = $_POST['item_id'] ?? '';
        $item_data = $this->item->getAll(['company_id' => $this->companyId]);

        $datas = [
            'item_id' => $item_id,
            'item_data' => $item_data,
            'invoice_id' => $invoice_id,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['unit_price'])) {
                $units_price = $this->db->get('item', 'price', [
                    'id' => $_POST['item_id']
                ]);

                $_POST['unit_price'] = $units_price;
                $_POST['amount'] = $_POST['quantity'] * $_POST['unit_price'];
            }

            $_POST['amount'] = $_POST['quantity'] * $_POST['unit_price'];

            $this->invoiceDetail->create($_POST);
            $this->redirect(BASEURL . 'invoice/detail/' . $invoice_id);
        } else {
            $this->view('invoice-detail/add', $datas);
        }
    }

    public function edit($id, $invoice_id)
    {
        $detail_data = $this->invoiceDetail->find($id);
        $item_data = $this->item->getAll(['company_id' => $this->companyId]);

        $datas = [
            'detail_data' => $detail_data,
            'item_data' => $item_data,
            'invoice_id' => $invoice_id,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['unit_price'])) {
                $units_price = $this->db->get('item', 'price', [
                    'id' => $_POST['item_id']
                ]);

                $_POST['unit_price'] = $units_price;
                $_POST['amount'] = $_POST['quantity'] * $_POST['unit_price'];
            }

            $_POST['amount'] = $_POST['quantity'] * $_POST['unit_price'];

            $this->invoiceDetail->update($id, $_POST);
            $this->redirect(BASEURL . 'invoice/detail/' . $invoice_id);
        } else {
            $this->view('invoice-detail/edit', $datas);
        }
    }

    public function delete($id, $invoice_id)
    {
        $this->invoiceDetail->delete($id);
        $this->redirect(BASEURL . 'invoice/detail/' . $invoice_id);
    }

    private function getInvoiceData($invoice_id)
    {
        $invoice_details = $this->invoiceDetail->getall($invoice_id);

        $invoice = $invoice_details[0];
        $total_bill = 0;

        foreach ($invoice_details as $invoice_detail) {
            $total_bill += $invoice_detail['amount'];
        }

        $logo_src = $this->imageToBase64($invoice['company_logo'] ?? null, 'logo');
        $signature_src = $this->imageToBase64($invoice['company_signature'] ?? null, 'signature');

        return [
            'number' => 1,
            'invoice_id' => $invoice_id,
            'invoice_details' => $invoice_details,
            'invoice' => $invoice,
            'total_bill' => $total_bill,
            'logo_src' => $logo_src,
            'signature_src' => $signature_src,
        ];
    }

    private function imageToBase64($relative_path, $subfolder)
    {
        if (empty($relative_path)) {
            return '';
        }

        $full_path = __DIR__ . '/../../public/uploads/company/' . $subfolder . '/' . $relative_path;

        if (!file_exists($full_path)) {
            return '';
        }

        $ext = pathinfo($full_path, PATHINFO_EXTENSION);
        $data = base64_encode(file_get_contents($full_path));

        return "data:image/{$ext};base64,{$data}";
    }

    public function generatePdf($invoice_id)
    {
        $datas = $this->getInvoiceData($invoice_id);

        $this->view('invoice-detail/generate-pdf', $datas);
    }

    public function print($invoice_id)
    {
        extract($this->getInvoiceData($invoice_id));

        ob_start();
        include_once __DIR__ . '/../views/pages/invoice-detail/generate-pdf.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("Invoice.pdf", [
            'Attachment' => false
        ]);
    }

    public function download($invoice_id)
    {
        extract($this->getInvoiceData($invoice_id));

        ob_start();
        include_once __DIR__ . '/../views/pages/invoice-detail/generate-pdf.php';
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
    }
}
