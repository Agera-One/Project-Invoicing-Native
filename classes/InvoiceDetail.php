<?php

use Medoo\Medoo;

class InvoiceDetail
{
    private $db;
    private $company_id;

    public function __construct($db, $company_id)
    {
        $this->db = $db;
        $this->company_id = $company_id;
    }

    public function getAll($invoice_id) {
        return $this->db->select('invoice_detail', [
            '[>]invoice' => ['invoice_id' => 'id'],
            '[>]customer' => ['invoice.customer_id' => 'id'],
            '[>]pic' => ['invoice.pic_id' => 'id'],
            '[>]item' => ['invoice_detail.item_id' => 'id'],
            '[><]company' => ['invoice.company_id' => 'id'],
        ], [
            'invoice.id(invoice_id)',
            'invoice.invoice_code',
            'invoice.date',
            'invoice.due_date',
            'customer.name(customer_name)',
            'pic.name(pic_name)',
            'invoice_detail.id(detail_id)',
            'invoice_detail.unit_price',
            'invoice_detail.quantity',
            'invoice_detail.amount',
            'item.id(item_id)',
            'item.name',
            'company.name(company_name)',
            'company.email(company_email)',
            'company.province(company_province)',
            'company.subdistrict(company_subdistrict)',
            'company.logo(company_logo)' ?? '',
        ], [
            'invoice.id' => $invoice_id
        ]);
    }

    public function find($id) {
        return $this->db->get('invoice_detail', '*', [
            'id' => $id
        ]);
    }

    public function create($data) {
        return $this->db->insert('invoice_detail', [
            'invoice_id' => $data['invoice_id'],
            'item_id' => $data['item_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'amount' => $data['amount']
        ]);
    }

    public function update($id, $data) {
        $this->db->update('invoice_detail', [
            'invoice_id' => $data['invoice_id'],
            'item_id' => $data['item_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'amount' => $data['amount']
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        $this->db->delete('invoice_detail', [
            'id' => $id
        ]);
    }

    public function invoiceItemCount($id)
    {
        return $this->db->count('invoice_detail', [
            'invoice_id' => $id
        ]);
    }

    public function sumInvoiceBill($invoice_id) {
        $total_bill_query = $this->db->select('invoice_detail', 'amount', ['invoice_id' => $invoice_id]);
        return array_sum($total_bill_query) ?? 0;
    }
}
