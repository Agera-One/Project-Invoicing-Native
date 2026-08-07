<?php

class AuthController extends BaseController {
    private $auth;
    private $company;

    public function __construct() {
        $this->auth = $this->model('Auth');
        $this->company = $this->model('Company');
    }

    public function showLoginForm() {
        $this->view('auth/login');
    }

    public function login() {
        $email    = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->auth->find($email);

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
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ];

        $company_data = [
            'company_name'          => $_POST['company_name'],
            'business_entity'       => $_POST['business_entity'],
            'business_sector'       => $_POST['business_sector'],
            'business_website'      => $_POST['business_website'] === '' ? null : $_POST['business_website'],
            'business_description'  => $_POST['business_description'] ?? '',
            'country'               => $_POST['country'],
            'province'              => $_POST['province'],
            'city'                  => $_POST['city'],
            'subdistrict'           => $_POST['subdistrict'],
            'business_address'      => $_POST['business_address'],
            'company_email'         => $_POST['company_email'],
            'company_phone'         => $_POST['company_phone'],
        ];

        $user_email_exists    = $$this->db->has('user', ['email' => $user_data['email']]);
        $company_email_exists = $$this->db->has('company', ['email' => $company_data['company_email']]);
        $company_phone_exists = $$this->db->has('company', ['phone' => $company_data['company_phone']]);

        if ($user_email_exists) {
            echo '<script>alert("User email already exists. Please use a different email.")</script>';
            $this->redirect('/login');
        } elseif ($company_email_exists) {
            echo '<script>alert("Company email already exists. Please use a different email.")</script>';
        } elseif ($company_phone_exists) {
            echo '<script>alert("Company phone already exists. Please use a different phone.")</script>';
        } else {
            $this->company->create($company_data);
            $company_id = $this->company->id();
            $data = $this->auth->create($user_data, $company_id);

            if ($data) {
                echo '<script>alert("Registration successful. Please log in.")</script>';
                echo '<script>window.location.href = "login.php";</script>';
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
