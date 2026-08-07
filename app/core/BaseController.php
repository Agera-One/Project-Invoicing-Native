<?php

class BaseController
{
    protected $userId;
    protected $companyId;
    protected $currentUser;
    protected $currentCompanyName;

    public function __construct()
    {
        $this->userId    = Session::get('user_id');
        $this->companyId = Session::get('company_id');

        $user    = $this->model('User');
        $company = $this->model('Company');

        $this->currentUser         = $user->find($this->userId);
        $this->currentCompanyName = $company->find('name', $this->companyId);
    }

    public function view($view, $data = [])
    {
        $data['user_id']              = $this->userId;
        $data['company_id']           = $this->companyId;
        $data['current_user']         = $this->currentUser;
        $data['current_company_name'] = $this->currentCompanyName;
        
        if (count($data)) {
            extract($data);
        }

        require_once '../app/views/pages/' . $view . '.php';
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

        public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    public function search($search, $where_condition, $columns)
    {
        $keyword = isset($search) ? $search : '';

        if ($keyword !== '') {
            foreach ($columns as $column) {
                $where_condition['OR']["{$column}[~]"] = $keyword;
            }
        }

        return $where_condition;
    }
}