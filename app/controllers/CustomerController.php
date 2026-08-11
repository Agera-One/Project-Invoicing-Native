<?php

class CustomerController extends BaseController
{
    private $customer;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->customer = $this->model('customer');
        $this->db = $this->customer->getConnection();
    }

    public function index()
    {
        unset($_SESSION['old']);
        $where_condition['company_id'] = $this->companyId;

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $where_condition = $this->search($search, $where_condition, ['customer_code', 'name', 'email', 'phone', 'address']);
        $pagination = $this->customer->pagination($this->db, $page, 'customer', 'id', $where_condition);

        $customers = $this->customer->getAll($where_condition, $pagination['offset'], $pagination['limit']);

        $datas = [
            'search' => $search,
            'page' => $page,
            'pagination' => $pagination,
            'customers' => $customers,
        ];

        $this->view('customer/index', $datas);
    }

    public function add()
    {
        $customer_code = $this->customer->generateCode($this->db, "customer", "customer_code", "CUST");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['company_id'] = $this->companyId;

            $email_exists = $this->db->has('customer', ['email' => $_POST['email']]);
            $phone_exists = $this->db->has('customer', ['phone' => $_POST['phone']]);

            if ($email_exists || $phone_exists) {
                $_SESSION['error'] = $email_exists
                    ? 'Email already exists'
                    : 'Phone number already exists';

                $_SESSION['old'] = $_POST;

                $this->redirect(BASEURL . 'customer/add');
                return;
            } else {
                unset($_SESSION['old']);
                $this->customer->create($_POST);
                $this->redirect(BASEURL . 'customer');
            }
        } else {
            $this->view('customer/add', ['customer_code' => $customer_code]);
        }
    }

    public function edit($id)
    {
        $data = $this->customer->find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email_exists = $this->db->has('customer', [
                'AND' => [
                    'email' => $_POST['email'],
                    'id[!]' => $id
                ]
            ]);

            $phone_exists = $this->db->has('customer', [
                'AND' => [
                    'phone' => $_POST['phone'],
                    'id[!]' => $id
                ]
            ]);

            if ($email_exists) {
                echo '<script>alert("Email already exists")</script>';
                $this->view('customer/edit', $data);
            } elseif ($phone_exists) {
                echo '<script>alert("phone already exists")</script>';
                $this->view('customer/edit', $data);
            } else {
                $this->customer->update($id, $_POST);
                $this->redirect(BASEURL . 'customer');
            }
        } else {
            $this->view('customer/edit', $data);
        }
    }

    public function delete($id)
    {
        $invoice = $this->db->has('invoice', ['customer_id' => $id]);

        if ($invoice) {
            echo
            '<script>
                alert("The customer cannot be deleted because it is still being used by another table.");
                window.location.href = "' . BASEURL . 'customer";
            </script>';
        } else {
            $this->customer->delete($id);
            $this->redirect(BASEURL . 'customer');
        }
    }
}
