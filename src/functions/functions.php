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