<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Medoo\Medoo;

$database = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'invoice_new',
    'username' => 'root',
    'password' => '',
]);