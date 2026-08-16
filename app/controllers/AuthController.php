<?php

class AuthController extends BaseController {
    private $user;
    private $company;
    private $db;

    public function __construct() {
        $this->user = $this->model('user');
        $this->company = $this->model('company');
        $this->db = $this->user->getConnection();
    }

    public function showLoginForm() {
        $this->view('auth/login');
    }

    public function login() {
        $email    = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->user->find(['email' => $email]);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                Session::set('user_id', $user['id']);
                Session::set('company_id', $user['company_id']);

                $this->redirect(BASEURL . 'dashboard');
            } else {
                echo
                '<script>
                    alert("Incorrect password. Please try again.");
                    window.location.href = "' . BASEURL . 'login";
                </script>';
            }
        } else {
            echo
            '<script>
                alert("Email not found. Please register first.");
                window.location.href = "' . BASEURL . 'login";
            </script>';
        }
    }

    public function showRegisterForm() {
        $this->view('auth/register');
    }

    public function register() {
        $user_data = [
            'name'     => $_POST['username'],
            'email'    => $_POST['email'],
            'password' => $_POST['password'],
        ];

        $company_data = [
            'company_name'          => $_POST['company_name'],
            'business_entity'       => $_POST['business_entity'],
            'business_sector'       => $_POST['business_sector'],
            'business_website'      => $_POST['business_website'],
            'business_description'  => $_POST['business_description'],
            'country'               => $_POST['country'],
            'province'              => $_POST['province'],
            'city'                  => $_POST['city'],
            'subdistrict'           => $_POST['subdistrict'],
            'business_address'      => $_POST['business_address'],
            'company_email'         => $_POST['company_email'],
            'company_phone'         => $_POST['company_phone'],
        ];

        $user_email_exists    = $this->db->has('user', ['email' => $user_data['email']]);
        $company_email_exists = $this->db->has('company', ['email' => $company_data['company_email']]);
        $company_phone_exists = $this->db->has('company', ['phone' => $company_data['company_phone']]);

        if ($user_email_exists) {
            echo 
            '<script>
                alert("User email already exists");
                window.location.href = "' . BASEURL . 'login";
            </script>';
        } elseif ($company_email_exists) {
            echo 
            '<script>
                alert("Company email already exists");
                window.location.href = "' . BASEURL . 'login";
            </script>';
        } elseif ($company_phone_exists) {
            echo 
            '<script>
                alert("Company phone already exists");
                window.location.href = "' . BASEURL . 'login";
            </script>';
        } else {
            $this->company->create($company_data);
            $user_data['company_id'] = $this->company->id();
            $data = $this->user->create($user_data);

            if ($data) {
                echo
                '<script>
                    alert("Registration successful. Please log in.");
                    window.location.href = "' . BASEURL . 'login";
                </script>';
            } else {
                echo '<script>alert("Error occurred during registration.")</script>';
            }
        }
    }

    public function logout() {
        Session::destroy();
        $this->redirect(BASEURL . 'login');
    }
}
