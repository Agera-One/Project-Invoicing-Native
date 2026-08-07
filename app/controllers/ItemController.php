<?php

class ItemController extends BaseController {
    private $item;
    private $db;

    public function __construct() {
        parent::__construct();
        $this->item = $this->model('Item');
        $this->db = $this->item->getConnection();
    }

    public function index() {
        $where_condition['company_id'] = $this->companyId;

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $where_condition = $this->search($search, $where_condition, ['ref_no', 'name']);
        $pagination = $this->item->pagination($this->db, $page, 'item', 'id', $where_condition);

        $items = $this->item->getAll($where_condition, $pagination['offset'], $pagination['limit']);

        $datas = [
            'search' => $search,
            'page' => $page,
            'pagination' => $pagination,
            'items' => $items,
        ];

        $this->view('item/index', $datas);
    }

    public function add() {
        $this->view('item/add');
    }
}
