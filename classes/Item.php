<?php

class Item {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($query_options) {
        return $this->db->select('item', '*', $query_options);
    }

    public function find($id) {
        return $this->db->get('item', '*', [
            'id' => $id
        ]);
    }

    public function create($datas) {
        return $this->db->insert('item', [
            'ref_no' => $datas['ref_no'],
            'name' => $datas['name'],
            'price' => $datas['price'],
            'company_id' => $datas['company_id']
        ]);
    }

    public function update($id, $datas) {
        return $this->db->update('item', [
            'name' => $datas['name'],
            'price' => $datas['price']
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete('item', [
            'id' => $id
        ]);
    }
}