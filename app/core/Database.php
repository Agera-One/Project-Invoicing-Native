<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Medoo\Medoo;

class Database
{
    private Medoo $db;

    public function __construct()
    {
        $this->db = new Medoo([
            'type'     => DB_TYPE,
            'host'     => DB_HOST,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
        ]);
    }

    public function getConnection()
    {
        return $this->db;
    }
}
