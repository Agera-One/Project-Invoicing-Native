<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($query_options) {
        return $this->db->select('user', '*', $query_options);
    }

    public function find($id) {
        return $this->db->get('user', '*', [
            'id' => $id
        ]);
    }

    public function create($data) {
        return $this->db->insert('user', [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data["password"], PASSWORD_DEFAULT),
            'company_id' => $data['company_id']
        ]);
    }

    public function update($id, $data) {
        return $this->db->update('user', [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data["password"], PASSWORD_DEFAULT)
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete('user', [
            'id' => $id
        ]);
    }
}
