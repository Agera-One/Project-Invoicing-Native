<?php

class ItemController extends BaseController {
    private $item;
    private $db;

    public function __construct() {
        parent::__construct();
        $this->item = $this->model('item');
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
        $ref_no = $this->item->generateCode($this->db, "item", "ref_no", "REF");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['company_id'] = $this->companyId;
            $this->item->create($_POST);
            $this->redirect(BASEURL . 'item');
        } else {
            $this->view('item/add', ['ref_no' => $ref_no]);
        }
    }

    public function edit($id) {
        $datas = $this->item->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->item->update($id, $_POST);
            $this->redirect(BASEURL . 'item');
        } else {
            $this->view('item/edit', $datas);
        }
    }

    public function delete($id) {
        $total_invoice_detail = $this->db->has('invoice_detail', ['item_id' => $id]);

        if ($total_invoice_detail) {
            echo
            '<script>
                alert("The item cannot be deleted because it is still being used by another table.");
                window.location.href = "' . BASEURL . 'item";
            </script>';
        } else {
            $this->item->delete($id);
            $this->redirect(BASEURL . 'item');
        }
    }
}