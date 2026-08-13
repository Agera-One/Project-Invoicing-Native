<?php

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
}
