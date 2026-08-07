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

    public function update($id, $datas, $section) {
        if ($section === 'info') {
            return $this->getConnection()->update('company', [
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
            return $this->getConnection()->update('company', [
                'email' => $datas['email'],
                'phone' => $datas['phone']
            ], [
                'id' => $id
            ]);
        }
    }
}