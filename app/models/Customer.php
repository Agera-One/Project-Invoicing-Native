<?php

class Customer extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($where_condition = [], $offset = '', $limit = '')
    {
        return $this->getConnection()->select('customer', '*', [
            ...$where_condition,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => [$offset, $limit],
        ]);
    }

    public function find($id)
    {
        return $this->getConnection()->get('customer', '*', ['id' => $id]);
    }

    public function create($data)
    {
        return $this->getConnection()->insert('customer', [
            'customer_code' => $data['customer_code'],
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'address'       => $data['address'],
            'company_id'    => $data['company_id'],
        ]);
    }

    public function update($data, $condition)
    {
        return $this->getConnection()->update('customer', [
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'],
            'address' => $data['address'],
        ], $condition);
    }

    public function delete($id)
    {
        return $this->getConnection()->delete('customer', ['id' => $id]);
    }

    public function isCodeTakenByOther($customerCode, $checkCondition)
    {
        $owner_id = $this->getConnection()->get('customer', 'id', ['customer_code' => $customerCode]);

        if ($owner_id === null) {
            return false;
        }

        $current_id = $this->getConnection()->get('customer', 'id', $checkCondition);

        return $owner_id != $current_id;
    }
}