<?php

class PicController extends BaseController
{
    private $pic;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->pic = $this->model('pic');
        $this->db = $this->pic->getConnection();
    }

    public function index()
    {
        unset($_SESSION['old']);
        $where_condition['company_id'] = $this->companyId;

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $where_condition = $this->search($search, $where_condition, ['name', 'email', 'phone', 'position']);
        $pagination = $this->pic->pagination($this->db, $page, 'pic', 'id', $where_condition);

        $pics = $this->pic->getAll($where_condition, $pagination['offset'], $pagination['limit']);

        $datas = [
            'search' => $search,
            'page' => $page,
            'pagination' => $pagination,
            'pics' => $pics,
        ];

        $this->view('pic/index', $datas);
    }

    public function add()
    {
        $is_active = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['company_id'] = $this->companyId;

            $email_exists = $this->db->has('pic', ['email' => $_POST['email']]);
            $phone_exists = $this->db->has('pic', ['phone' => $_POST['phone']]);

            if ($email_exists || $phone_exists) {
                $_SESSION['error'] = $email_exists
                    ? 'Email already exists'
                    : 'Phone number already exists';

                $_SESSION['old'] = $_POST;

                $this->redirect(BASEURL . 'pic/add');
                return;
            } else {
                unset($_SESSION['old']);
                $this->pic->create($_POST);
                $this->redirect(BASEURL . 'pic');
            }
        } else {
            $this->view('pic/add', ['is_active' => $is_active]);
        }
    }

    public function edit($id)
    {
        $data = $this->pic->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email_exists = $this->db->has('pic', [
                'AND' => [
                    'email' => $_POST['email'],
                    'id[!]' => $id
                ]
            ]);

            $phone_exists = $this->db->has('pic', [
                'AND' => [
                    'phone' => $_POST['phone'],
                    'id[!]' => $id
                ]
            ]);

            if ($email_exists) {
                echo '<script>alert("Email already exists")</script>';
                $this->view('pic/edit', $data);
            } elseif ($phone_exists) {
                echo '<script>alert("phone already exists")</script>';
                $this->view('pic/edit', $data);
            } else {
                $this->pic->update($id, $_POST);
                $this->redirect(BASEURL . 'pic');
            }
        } else {
            $this->view('pic/edit', $data);
        }
    }

    public function delete($id)
    {
        $invoice = $this->db->has('invoice', ['pic_id' => $id]);

        if ($invoice) {
            echo
            '<script>
                alert("The pic cannot be deleted because it is still being used by another table.");
                window.location.href = "' . BASEURL . 'pic";
            </script>';
        } else {
            $this->pic->delete($id);
            $this->redirect(BASEURL . 'pic');
        }
    }
}
