<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Medoo\Medoo;

class Database {
    private Medoo $db;

    public function __construct()
    {
        $this->db = new Medoo([
            'type' => 'mysql',
            'host' => 'localhost',
            'database' => 'invoicing_new',
            'username' => 'root',
            'password' => '',
        ]);
    }

    public function getConnection()
    {
        return $this->db;
    }
}