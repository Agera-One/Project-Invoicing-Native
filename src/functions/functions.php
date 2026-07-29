<?php
function generate_code($database, $table, $column, $prefix) {
    $date = date("Y");

    $last = $database->get($table, [$column], [
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

function pagination($database, $page, $table, $column, $where_condition, $join = [])
{
    $limit = 10;

    $active_page = isset($page) ? (int)$page : 1;
    $offset = ($active_page - 1) * $limit;

    if (!empty($join)) {
        $rows = $database->count($table, $join, $column, $where_condition);
    } else {
        $rows = $database->count($table, $column, $where_condition);
    }
    
    $total_page = ceil($rows / $limit);

    return [
        'limit'        => $limit,
        'active_page'  => $active_page,
        'offset'       => $offset,
        'where'        => $where_condition,
        'total_page'   => $total_page,
        'rows'         => $rows,
    ];
}