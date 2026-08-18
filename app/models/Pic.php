<?php

class Pic extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($where_condition = [], $offset = '', $limit = '')
    {
        return $this->getConnection()->select('pic', '*', [
            ...$where_condition,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }

    public function find($id)
    {
        return $this->getConnection()->get('pic', '*', [
            'id' => $id
        ]);
    }

    public function create($datas)
    {
        return $this->getConnection()->insert('pic', [
            'name' => $datas['name'],
            'phone' => $datas['phone'],
            'email' => $datas['email'],
            'position' => $datas['position'],
            'is_active' => $datas['is_active'],
            'company_id' => $datas['company_id']
        ]);
    }

    public function update($id, $datas)
    {
        return $this->getConnection()->update('pic', [
            'name' => $datas['name'],
            'phone' => $datas['phone'],
            'email' => $datas['email'],
            'position' => $datas['position'],
            'is_active' => $datas['is_active']
        ], [
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        return $this->getConnection()->delete('pic', [
            'id' => $id
        ]);
    }
}
