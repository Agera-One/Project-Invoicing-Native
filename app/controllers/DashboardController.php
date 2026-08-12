<?php

class DashboardController extends BaseController
{
    private $invoice;
    private $payment;
    private $item;
    private $invoiceDetail;

    public function __construct()
    {
        parent::__construct();
        $this->invoice = $this->model('invoice');
        $this->payment = $this->model('payment');
        $this->item = $this->model('item');
        $this->invoiceDetail = $this->model('invoicedetail');
    }

    public function index()
    {
        $number = 1;
        $today = date('Y-m-d');
        $invoice_value = $this->invoice->sumInvoiceValue();
        $total_revenue = $this->payment->sumRevenue();
        $invoices = $this->invoice->getAllCompact();
        $top_item = $this->item->getTopItem();
        $sum_unpaid_overdue = $this->invoice->sumUnpaidOverdue($today);

        $datas = [
            'number' => $number,
            'today' => $today,
            'invoice_value' => $invoice_value,
            'total_revenue' => $total_revenue,
            'invoices' => $invoices,
            'top_item' => $top_item,
            'total_unpaid'  => $sum_unpaid_overdue['total_unpaid']  ?? 0,
            'total_overdue' => $sum_unpaid_overdue['total_overdue'] ?? 0,
            'invoice_detail' => $this->invoiceDetail,
        ];

        $this->view('dashboard/index', $datas);
    }
}
