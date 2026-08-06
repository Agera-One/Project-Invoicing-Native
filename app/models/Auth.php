<?php

class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function find($email) {
        return $this->db->get('user', '*', [
            'email' => $email
        ]);
    }

    public function create($user_data, $company_data) {
        $this->db->insert('company', [
            'name'            => $company_data['company_name'],
            'email'           => $company_data['company_email'],
            'phone'           => $company_data['company_phone'],
            'business_entity' => $company_data['business_entity'],
            'sector'          => $company_data['business_sector'],
            'website'         => $company_data['business_website'],
            'description'     => $company_data['business_description'],
            'country'         => $company_data['country'],
            'province'        => $company_data['province'],
            'city'            => $company_data['city'],
            'subdistrict'     => $company_data['subdistrict'],
            'address'         => $company_data['business_address'],
        ]);

        $company_id = $this->db->id();

        return $this->db->insert('user', [
            'name'       => $user_data['name'],
            'email'      => $user_data['email'],
            'password'   => $user_data['password'],
            'company_id' => $company_id,
        ]);
    }
}