<?php

class Auth extends BaseModel {
    public function __construct() {
        parent::__construct();
    }

    public function find($email) {
        return $this->getConnection()->get('user', '*', [
            'email' => $email
        ]);
    }

    public function create($user_data, $company_id) {
        return $this->getConnection()->insert('user', [
            'name'       => $user_data['name'],
            'email'      => $user_data['email'],
            'password'   => $user_data['password'],
            'company_id' => $company_id,
        ]);
    }
}