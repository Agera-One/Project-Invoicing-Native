<?php

class Company extends BaseModel {
    public function __construct()
    {
        parent::__construct();
    }

    public function find($column, $company_id) {
        return $this->getConnection()->get('company', $column, ['id' => $company_id]);
    }

    public function create($company_data) {
        $this->getConnection()->insert('company', [
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
    }

    public function updateInfo($id, $datas) {
        $this->getConnection()->update('company', [
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
    }

    public function updateContact($id, $datas) {
        $this->getConnection()->update('company', [
            'email' => $datas['email'],
            'phone' => $datas['phone']
        ], [
            'id' => $id
        ]);
    }

    public function uploadLogo($id, $new_logo_name) {
        $this->getConnection()->update('company', [
            'logo' => $new_logo_name
        ], [
            'id' => $id
        ]);
    }

    public function uploadSignature($id, $new_signature_name) {
        $this->getConnection()->update('company', [
            'signature' => $new_signature_name
        ], [
            'id' => $id
        ]);
    }

    public function id()
    {
        return $this->getConnection()->id();
    }
}