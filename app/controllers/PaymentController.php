<?php

class PaymentController extends BaseController
{
    private $payment;
    private $invoice;
    private $invoiceDetail;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->payment = $this->model('payment');
        $this->invoice = $this->model('invoice');
        $this->invoiceDetail = $this->model('invoicedetail');
        $this->db = $this->invoice->getConnection();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $where_condition['invoice.company_id'] = $this->companyId;

        $join_structure = [
            '[><]invoice' => ['invoice_id' => 'id'],
            '[>]customer' => ['invoice.customer_id' => 'id']
        ];

        $where_condition = $this->search($search, $where_condition, ['payment.payment_code', 'invoice.invoice_code', 'customer.name', 'payment.date']);
        $pagination = $this->payment->pagination($this->db, $page, 'payment', 'payment.id', $where_condition, $join_structure);

        $payments = $this->payment->getAll($join_structure, $where_condition, $pagination['offset'], $pagination['limit']);

        $datas = [
            'search' => $search,
            'page' => $page,
            'pagination' => $pagination,
            'payments' => $payments,
        ];

        $this->view('payment/index', $datas);
    }

    public function add($get_invoice_id = '')
    {
        $payment_code = $this->payment->generateCode($this->db, "payment", "payment_code", "PAY");

        $invoice_id  = $_POST['invoice_id'] ?? $get_invoice_id;

        $join_structure = [
            '[><]customer' => ['customer_id' => 'id'],
            '[>]invoice_detail' => ['id' => 'invoice_id'],
            '[>]payment' => ['id' => 'invoice_id'],
            '[><]pic' => ['pic_id' => 'id'],
        ];

        $where_condition = ['invoice.company_id' => $this->companyId];

        $invoice_data = $this->invoice->getAll($join_structure, $where_condition);

        $selected_invoice = null;
        foreach ($invoice_data as $invoice) {
            if ((string)$invoice['id'] === (string)$invoice_id) {
                $selected_invoice = $invoice;
                break;
            }
        }

        $datas = [
            'payment_code' => $payment_code,
            'invoice_id' => $invoice_id,
            'invoice_data' => $invoice_data,
            'selected_invoice' => $selected_invoice,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['invoice_id'] = $invoice_id;

            $this->payment->create($_POST);
            $this->redirect(BASEURL . 'payment');
        } else {
            $this->view('payment/add', $datas);
        }
    }

    public function edit($id)
    {
        $payment_data = $this->payment->find($id);

        $join_structure = [
            '[><]customer' => ['customer_id' => 'id'],
            '[>]invoice_detail' => ['id' => 'invoice_id'],
            '[>]payment' => ['id' => 'invoice_id'],
            '[><]pic' => ['pic_id' => 'id'],
        ];

        $where_condition = ['invoice.company_id' => $this->companyId];

        $invoice_data = $this->invoice->getAll($join_structure, $where_condition);

        $selected_invoice = null;
        foreach ($invoice_data as $invoice) {
            if ((string)$invoice['id'] === (string)$payment_data['invoice_id']) {
                $selected_invoice = $invoice;
                break;
            }
        }

        $datas = [
            'payment_data' => $payment_data,
            'invoice_data' => $invoice_data,
            'selected_invoice' => $selected_invoice,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->payment->update($id, $_POST);
            $this->redirect(BASEURL . 'payment');
        } else {
            $this->view('payment/edit', $datas);
        }
    }

    public function delete($id)
    {
        $this->payment->delete($id);
        $this->redirect(BASEURL . 'payment');
    }
}
