<?php
use Medoo\Medoo;

class Invoice extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public function getAll($join, $where_condition, $offset = '', $limit = '') {
        return $this->getConnection()->select('invoice', $join, [
            'invoice.id',
            'invoice.customer_id',
            'invoice.pic_id',
            'invoice.invoice_code',
            'invoice.date',
            'invoice.due_date',
            'pic.name(pic_name)',
            'customer.name(customer_name)',
            'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
            'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)'),
            'total_amount_paid' => Medoo::raw('(SELECT COALESCE(SUM(payment.amount), 0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
        ], [
            ...$where_condition,
            'GROUP' => 'invoice.id',
            'ORDER' => ['invoice.id' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }

    public function getAllCompact() {
        return $this->getConnection()->select('invoice', [
            '[><]customer' => ['customer_id' => 'id'],
        ], [
            'invoice.id',
            'invoice.invoice_code',
            'invoice.date',
            'invoice.due_date',
            'customer.name(customer_name)',
            'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
            'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
        ], [
            'GROUP' => 'invoice.id',
            'ORDER' => ['invoice.id' => 'DESC'],
            'invoice.company_id' => $this->companyId,
            'LIMIT' => 6
        ]);
    }

    public function find($id) {
        return $this->getConnection()->get('invoice', '*', [
            'id' => $id
        ]);
    }

    public function create($data) {
        $this->getConnection()->insert('invoice', [
            'pic_id' => $data['pic_id'],
            'customer_id' => $data['customer_id'],
            'invoice_code' => $data['invoice_code'],
            'date' => $data['date'],
            'due_date' => $data['due_date'],
            'company_id' => $data['company_id'],
        ]);
    }

    public function update($id, $data) {
        $this->getConnection()->update('invoice', [
            'customer_id' => $data['customer_id'],
            'pic_id' => $data['pic_id'],
            'date' => $data['date'],
            'due_date' => $data['due_date']
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->getConnection()->delete('invoice', [
            'id' => $id
        ]);
    }

    public function sumInvoiceValue() {
        return $this->getConnection()->sum('invoice', [
            '[><]invoice_detail' => ['id' => 'invoice_id']
        ], 'invoice_detail.amount', [
            'company_id' => $this->companyId
        ]) ?: 0;
    }

    public function sumUnpaidOverdue($today) {
        $total_unpaid = 0;
        $total_overdue = 0;

        $invoices = $this->getConnection()->select('invoice', [
            'id',
            'due_date',
            'total_bill' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM invoice_detail WHERE invoice_detail.invoice_id = <invoice.id>)'),
            'total_payment' => Medoo::raw('(SELECT COALESCE(SUM(amount),0) FROM payment WHERE payment.invoice_id = <invoice.id>)')
        ], [
            'invoice.company_id' => $this->companyId,
        ]);

        foreach ($invoices as $invoice) {
            $remaining = $invoice['total_bill'] - $invoice['total_payment'];

            if ($remaining > 0) {
                if ($invoice['due_date'] >= $today) {
                    $total_unpaid += $remaining;
                } else {
                    $total_overdue += $remaining;
                }
            }
        }

        return compact('total_unpaid', 'total_overdue');
    }
}