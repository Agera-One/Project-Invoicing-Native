<?php

class Pic
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($where_condition = [], $offset = '', $limit = '')
    {
        return $this->db->select('pic', '*', [
            ...$where_condition,
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => [$offset, $limit]
        ]);
    }

    public function find($id)
    {
        return $this->db->get('pic', '*', [
            'id' => $id
        ]);
    }

    public function create($datas)
    {
        return $this->db->insert('pic', [
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
        return $this->db->update('pic', [
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
        return $this->db->delete('pic', [
            'id' => $id
        ]);
    }
}
