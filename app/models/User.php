<?php

class User extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($query_options) {
        return $this->getConnection()->select('user', '*', $query_options);
    }

    public function find($where) {
        return $this->getConnection()->get('user', '*', $where);
    }

    public function create($data) {
        return $this->getConnection()->insert('user', [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data["password"], PASSWORD_DEFAULT),
            'company_id' => $data['company_id']
        ]);
    }

    public function update($id, $data) {
        return $this->getConnection()->update('user', [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data["password"], PASSWORD_DEFAULT)
        ], [
            'id' => $id
        ]);
    }

    public function delete($id) {
        return $this->getConnection()->delete('user', [
            'id' => $id
        ]);
    }
}
