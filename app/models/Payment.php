<?php
use Medoo\Medoo;

class Payment extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($join, $where_condition, $offset, $limit) {
        return $this->getConnection()->select('payment', $join, [
            'payment.id',
            'payment.payment_code',
            'payment.date',
            'payment.amount',
            'customer.id(customer_id)',
            'customer.name(customer_name)',
            'invoice.id(invoice_id)',
            'invoice.invoice_code(invoice_code)'
        ], [
            ...$where_condition,
            'ORDER' => ['payment.date' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }

    public function find($id) {
        return $this->getConnection()->get('payment', '*', [
            'id' => $id
        ]);
    }

    public function create($data) {
        $this->getConnection()->insert('payment', [
            'invoice_id' => $data['invoice_id'],
            'payment_code' => $data['payment_code'],
            'date' => $data['date'],
            'amount' => $data['amount'],
        ]);
    }

    public function update($id, $data) {
        $this->getConnection()->update('payment', [
            'invoice_id' => $data['invoice_id'],
            'date' => $data['date'],
            'amount' => $data['amount']
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->getConnection()->delete('payment', [
            'id' => $id
        ]);
    }

    public function sumRevenue()
    {
        return $this->getConnection()->sum('payment', [
            '[><]invoice' => ['invoice_id' => 'id']
        ], 'payment.amount', [
            'invoice.company_id' => $this->companyId
        ]) ?: 0;
    }

    public function sumAmountPaid($invoice_id)
    {
        $total_paid_query = $this->getConnection()->select('payment', 'amount', ['invoice_id' => $invoice_id]);
        return array_sum($total_paid_query) ?? 0;
    }

    public function validatorPeriod($period) {
        if ($period === 'daily') {
            $periodKeyExpr   = "DATE(<payment.date>)";
            $periodLabelExpr = "DATE_FORMAT(<payment.date>, '%W, %d %M %Y')";
            $limit           = 7;
        } elseif ($period === 'weekly') {
            $periodKeyExpr   = "YEARWEEK(<payment.date>, 1)";
            $periodLabelExpr = "CONCAT('Week ', WEEK(MIN(<payment.date>), 1), ' (', DATE_FORMAT(MIN(<payment.date>), '%M'), ')')";
            $limit           = 5;
        } else {
            $periodKeyExpr   = "DATE_FORMAT(<payment.date>, '%Y-%m')";
            $periodLabelExpr = "DATE_FORMAT(<payment.date>, '%Y-%m')";
            $limit           = 6;
        }

        return [
            'periodKeyExpr' => $periodKeyExpr,
            'periodLabelExpr' => $periodLabelExpr,
            'limit' => $limit,
        ];
    }

    public function sumRevenuePeriod($periodKeyExpr, $periodLabelExpr, $company_id, $limit) {
        return $this->getConnection()->select('payment', [
            '[><]invoice' => ['invoice_id' => 'id']
        ], [
            'period_key' => Medoo::raw($periodKeyExpr),
            'period' => Medoo::raw($periodLabelExpr),
            'total_invoice' => Medoo::raw('COUNT(DISTINCT <payment.invoice_id>)'),
            'total_payment' => Medoo::raw('COUNT(<payment.id>)'),
            'revenue' => Medoo::raw('SUM(<payment.amount>)')
        ], [
            'GROUP' => 'period_key',
            'ORDER' => ['period_key' => 'DESC'],
            'invoice.company_id' => $company_id,
            'LIMIT' => $limit
        ]);
    }
}
