<?php

class CompanyController extends BaseController {
    private $db;
    private $company;
    private $extension = ['png', 'jpg', 'jpeg'];
    private $upload_dir = __DIR__ . '/../../public/uploads/company/';

    public function __construct()
    {
        parent::__construct();
        $this->company = $this->model('company');
        $this->db = $this->company->getConnection();
    }

    public function index() {
        $datas = $this->company->find('*', $this->companyId);
        $this->view('company/index', $datas);
    }

    public function editInfo() {
        $datas = $this->company->find([
            'name',
            'business_entity',
            'sector',
            'website',
            'description',
            'country',
            'province',
            'city',
            'subdistrict',
            'address'
        ], $this->companyId);
        
        $datas['section'] = 'info';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->company->updateInfo($this->companyId, $_POST);
            $this->redirect(BASEURL . 'company');
        } else {
            
            $this->view('/company/edit', $datas);
        }
    }

    public function editContact() {
        $datas = $this->company->find(['email', 'phone'], $this->companyId);
        $datas['section'] = 'contact';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email_exists = $this->db->has('company', [
                'AND' => [
                    'email' => $_POST['email'],
                    'id[!]' => $this->companyId
                ]
            ]);

            $phone_exists = $this->db->has('company', [
                'AND' => [
                    'phone' => $_POST['phone'],
                    'id[!]' => $this->companyId
                ]
            ]);

            if ($email_exists) {
                echo '<script>alert("Email already exists")</script>';
                $this->view('/company/edit/contact', $datas);
            } elseif ($phone_exists) {
                echo '<script>alert("phone already exists")</script>';
                $this->view('/company/edit/contact', $datas);
            } else {
                $this->company->updateContact($this->companyId, $_POST);
                $this->redirect(BASEURL . 'company');
            }
        } else {
            
            $this->view('/company/edit', $datas);
        }
    }

    public function uploadLogo() {
        $logo_name = $_FILES['logo']['name'];
        $logo_size = $_FILES['logo']['size'];
        $error = $_FILES['logo']['error'];
        $tmp_name = $_FILES['logo']['tmp_name'];

        if ($error === UPLOAD_ERR_NO_FILE) {
            echo "<script>alert('Select any logo first'); window.history.back();</script>";
            exit;
        }

        $image_extension = explode('.', $logo_name);
        $image_extension = strtolower(end($image_extension));

        if (!in_array($image_extension, $this->extension)) {
            echo "<script>alert('You must upload an image'); window.history.back();</script>";
            exit;
        }

        if ($logo_size > 2000000) {
            echo "<script>alert('The maximum image size is 2MB'); window.history.back();</script>";
            exit;
        }

        $new_logo_name = 'logo_' . $this->companyId . '_' . time() . '.' . $image_extension;

        move_uploaded_file($tmp_name, $this->upload_dir . '/logo/' . $new_logo_name);

        $this->company->uploadLogo($this->companyId, $new_logo_name);

        $this->redirect(BASEURL . 'company');
    }

    public function uploadSignature() {
        $signature_name = $_FILES['signature']['name'];
        $signature_size = $_FILES['signature']['size'];
        $error = $_FILES['signature']['error'];
        $tmp_name = $_FILES['signature']['tmp_name'];

        if ($error === UPLOAD_ERR_NO_FILE) {
            echo "<script>alert('Select a signature first'); window.history.back();</script>";
            exit;
        }

        $image_extension = explode('.', $signature_name);
        $image_extension = strtolower(end($image_extension));

        if (!in_array($image_extension, $this->extension)) {
            echo "<script>alert('You must upload an image'); window.history.back();</script>";
            exit;
        }

        if ($signature_size > 2000000) {
            echo "<script>alert('The maximum image size is 2MB'); window.history.back();</script>";
            exit;
        }

        $new_signature_name = 'signature_' . $this->companyId . '_' . time() . '.' . $image_extension;

        move_uploaded_file($tmp_name, $this->upload_dir . '/signature/' . $new_signature_name);

        $this->company->uploadSignature($this->companyId, $new_signature_name);

        $this->redirect(BASEURL . 'company');
    }
}