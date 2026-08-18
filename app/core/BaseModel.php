<?php

class BaseModel extends Database {
    protected $companyId;
    protected $userId;

    public function __construct()
    {
        parent::__construct();
        $this->companyId = Session::get('company_id');
        $this->userId    = Session::get('user_id');
    }

    public function generateCode($db, $table, $column, $prefix)
    {
        $date = date("Y");

        $last = $db->get($table, [$column], [
            $column . "[~]" => "{$prefix}-{$date}-%",
            "ORDER" => [
                "id" => "DESC"
            ]
        ]);

        if ($last) {
            $number = (int) substr($last[$column], -4) + 1;
        } else {
            $number = 1;
        }

        return sprintf("%s-%s-%04d", $prefix, $date, $number);
    }

    public function pagination($db, $page, $table, $column, $where_condition, $join = [])
    {
        $limit = 10;

        $active_page = isset($page) ? (int)$page : 1;
        $offset = ($active_page - 1) * $limit;

        if (!empty($join)) {
            $count_where = $where_condition;
            $count_where['GROUP'] = $column;
            $matching = $db->select($table, $join, [$column], $count_where);
            $rows = count($matching);
        } else {
            $rows = $db->count($table, $column, $where_condition);
        }

        $total_page = ceil($rows / $limit);

        return compact('limit', 'active_page', 'offset', 'where_condition', 'total_page', 'rows');
    }
}