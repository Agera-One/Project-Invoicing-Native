<?php

class Company {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function find($column, $company_id) {
        return $this->db->get('company', $column, ['id' => $company_id]);
    }

    public function update($id, $datas, $section) {
        if ($section === 'info') {
            return $this->db->update('company', [
                'name'             => $datas['name'],
                'business_entity'  => $datas['business_entity'],
                'sector'           => $datas['sector'],
                'website'          => $datas['website'],
                'description'      => $datas['description'],
                'country'          => $datas['country'],
                'province'         => $datas['province'],
                'city'             => $datas['city'],
                'subdistrict'      => $datas['subdistrict'],
                'address'          => $datas['address']
            ], [
                'id' => $id
            ]);
            
        } elseif ($section === 'contact') {
            return $this->db->update('company', [
                'email' => $datas['email'],
                'phone' => $datas['phone']
            ], [
                'id' => $id
            ]);
        }


    }
}