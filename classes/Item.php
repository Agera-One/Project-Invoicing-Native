<?php
use Medoo\Medoo;

class Item {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($where_condition = [], $offset = '', $limit = '')
    {
        return $this->db->select('item', '*', [
            ...$where_condition,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }

    public function find($id) {
        return $this->db->get('item', '*', [
            'id' => $id
        ]);
    }

    public function create($data) {
        return $this->db->insert('item', [
            'ref_no' => $data['ref_no'],
            'name' => $data['name'],
            'price' => $data['price'],
            'company_id' => $data['company_id']
        ]);
    }

    public function update($id, $data) {
        return $this->db->update('item', [
            'name' => $data['name'],
            'price' => $data['price']
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete('item', [
            'id' => $id
        ]);
    }

    public function getTopItem($company_id) {
        return $this->db->select('item', [
            '[><]invoice_detail' => [
                'id' => 'item_id'
            ]
        ], [
            'item.name(item_name)',
            'invoice_detail.unit_price',
            'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
            'total_revenue' => Medoo::raw('SUM(<invoice_detail.unit_price> * <invoice_detail.quantity>)')
        ], [
            'GROUP' => 'item.id',
            'ORDER' => [
                'total_unit_sold' => 'DESC'
            ],
            'item.company_id' => $company_id,
            'LIMIT' => 5
        ]);
    }

    public function validatorPeriod($period, $period_label) {
        $where_condition = [];

        switch ($period) {
            case 'weekly':
                $start = date('Y-m-d', strtotime('monday this week'));
                $end   = date('Y-m-d', strtotime('sunday this week'));
                $where_condition['invoice.date[>=]'] = $start;
                $where_condition['invoice.date[<=]'] = $end;
                $period_label = 'This Week';
                break;

            case 'monthly':
                $start = date('Y-m-01');
                $end   = date('Y-m-t');
                $where_condition['invoice.date[>=]'] = $start;
                $where_condition['invoice.date[<=]'] = $end;
                $period_label = 'This Month';
                break;

            case 'yearly':
                $start = date('Y-01-01');
                $end   = date('Y-12-31');
                $where_condition['invoice.date[>=]'] = $start;
                $where_condition['invoice.date[<=]'] = $end;
                $period_label = 'This Year';
                break;

            case 'all':
            default:
                $period_label = 'All Time';
                break;
        }

        return [
            'where_condition' => $where_condition,
            'period_label' => $period_label,
        ];
    }

    public function getBestSeller($where_condition, $company_id) {
        return $this->db->select('item', [
            '[><]invoice_detail' => [
                'id' => 'item_id'
            ],
            '[><]invoice' => [
                'invoice_detail.invoice_id' => 'id'
            ]
        ], [
            'item.name(item_name)',
            'item.price',
            'total_unit_sold' => Medoo::raw('SUM(<invoice_detail.quantity>)'),
            'total_sales' => Medoo::raw('SUM(<invoice_detail.amount>)')
        ], [
            ...$where_condition,
            'GROUP' => 'item.id',
            'ORDER' => [
                'total_unit_sold' => 'DESC'
            ],
            'item.company_id' => $company_id,
            'LIMIT' => 10
        ]);
    }
}