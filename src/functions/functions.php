<?php
function generate_code($db, $table, $column, $prefix) {
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

function search($search, $where_condition, $columns)
{
    $keyword = isset($search) ? $search : '';

    if ($keyword !== '') {
        foreach ($columns as $column) {
            $where_condition['OR']["{$column}[~]"] = $keyword;
        }
    }

    return $where_condition;
}

function pagination($db, $page, $table, $column, $where_condition, $join = [])
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