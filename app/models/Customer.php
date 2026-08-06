<?php

class Customer {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($where_condition = [], $offset = '', $limit = '') {
        return $this->db->select('customer', '*', [
            ...$where_condition,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }

    public function find($id) {
        return $this->db->get('customer', '*', [
            'id' => $id
        ]);
    }

    public function create($data) {
        return $this->db->insert('customer', [
            'customer_code' => $data['customer_code'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'company_id' => $data['company_id']
        ]);
    }

    public function update($id, $data) {
        return $this->db->update('customer', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete('customer', [
            'id' => $id
        ]);
    }
}