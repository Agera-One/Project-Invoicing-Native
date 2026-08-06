<?php
// require_once "../../../config/database.php";
// require_once "../../../classes/Auth.php";

// $db = (new Database())->getConnection();
// $auth = new Auth($db);

class AuthController {
    private $auth;

    // public function __construct()
    // {
    //     $db = (new Database())->getConnection();
    //     $this->auth = new Auth($db);
    // }

    public function showLoginForm() {
        // $this->view('auth/login');
        echo 'hello';
    }

    public function login() {
        $email    = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->auth->find($email);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                Session::start();
                Session::set('user_id', $user['id']);
                Session::set('company_id', $user['company_id']);

                header("Location: ../dashboard/dashboard.php");
                exit();
            } else {
                echo '<script>alert("Incorrect password. Please try again.")</script>';
            }
        } else {
            echo '<script>alert("Email not found. Please register first.")</script>';
        }
    }
}
