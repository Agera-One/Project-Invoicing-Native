<?php

use Medoo\Medoo;

class OverdueController extends BaseController
{
    private $invoice;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->invoice = $this->model('invoice');
        $this->db = $this->invoice->getConnection();
    }

    public function index()
    {
        $today = date('Y-m-d');
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $join_structure = [
            '[><]customer' => ['customer_id' => 'id'],
            '[><]invoice_detail' => ['id' => 'invoice_id'],
            '[><]pic' => ['pic_id' => 'id'],
        ];

        $where_condition = [
            'invoice.company_id' => $this->companyId,
            'invoice.due_date[<]' => $today,
            'HAVING' => Medoo::raw('SUM(<invoice_detail.amount>) > (SELECT COALESCE(SUM(payment.amount), 0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
        ];

        $where_condition = $this->search($search, $where_condition, ['invoice.invoice_code', 'customer.name', 'invoice.date', 'invoice.due_date']);
        $pagination = $this->invoice->pagination($this->db, $page, 'invoice', 'invoice.id', $where_condition, $join_structure);

        $invoices = $this->invoice->getAll($join_structure, $where_condition, $pagination['offset'], $pagination['limit']);

        $datas = [
            'today' => $today,
            'search' => $search,
            'page' => $page,
            'pagination' => $pagination,
            'invoices' => $invoices,
        ];

        $this->view('overdue/index', $datas);
    }
}
