<?php

class Routes {
    public function register(App $app) {

        if (isset($_SESSION['user_id']) && isset($_SESSION['company_id'])) {
            $app->setDefaultController('ErrorController');
            $app->setDefaultMethod('index');
        } else {
            $app->setDefaultController('AuthController');
            $app->setDefaultMethod('showLoginForm');
        }

        $app->get('/login', ['AuthController', 'showLoginForm']);
        $app->post('/login/store', ['AuthController', 'login']);
        $app->get('/register', ['AuthController', 'showRegisterForm']);
        $app->post('/register/store', ['AuthController', 'register']);
        $app->get('/logout', ['AuthController', 'logout']);

        $app->get('/dashboard', ['DashboardController', 'index']);

        $app->get('/item', ['ItemController', 'index']);
        $app->get('/item/add', ['ItemController', 'add']);
        $app->post('/item/add', ['ItemController', 'add']);
        $app->get('/item/edit', ['ItemController', 'edit']);
        $app->post('/item/edit', ['ItemController', 'edit']);
        $app->get('/item/delete', ['ItemController', 'delete']);

        $app->get('/customer', ['CustomerController', 'index']);
        $app->get('/customer/add', ['CustomerController', 'add']);
        $app->post('/customer/add', ['CustomerController', 'add']);
        $app->get('/customer/edit', ['CustomerController', 'edit']);
        $app->post('/customer/edit', ['CustomerController', 'edit']);
        $app->get('/customer/delete', ['CustomerController', 'delete']);

        $app->get('/pic', ['PicController', 'index']);
        $app->get('/pic/add', ['PicController', 'add']);
        $app->post('/pic/add', ['PicController', 'add']);
        $app->get('/pic/edit', ['PicController', 'edit']);
        $app->post('/pic/edit', ['PicController', 'edit']);
        $app->get('/pic/delete', ['PicController', 'delete']);

        $app->get('/invoice', ['InvoiceController', 'index']);
        $app->get('/invoice/add', ['InvoiceController', 'add']);
        $app->post('/invoice/add', ['InvoiceController', 'add']);
        $app->get('/invoice/edit', ['InvoiceController', 'edit']);
        $app->post('/invoice/edit', ['InvoiceController', 'edit']);
        $app->get('/invoice/delete', ['InvoiceController', 'delete']);

        $app->get('/invoice/detail', ['DetailController', 'index']);
        $app->get('/detail/add', ['DetailController', 'add']);
        $app->post('/detail/add', ['DetailController', 'add']);
        $app->get('/detail/edit', ['DetailController', 'edit']);
        $app->post('/detail/edit', ['DetailController', 'edit']);
        $app->get('/detail/delete', ['DetailController', 'delete']);
        $app->get('/detail/print', ['DetailController', 'print']);
        $app->get('/detail/download', ['DetailController', 'download']);

        $app->get('/payment', ['PaymentController', 'index']);
        $app->get('/payment/add', ['PaymentController', 'add']);
        $app->post('/payment/add', ['PaymentController', 'add']);
        $app->get('/payment/edit', ['PaymentController', 'edit']);
        $app->post('/payment/edit', ['PaymentController', 'edit']);
        $app->get('/payment/delete', ['PaymentController', 'delete']);

        $app->get('/outstanding', ['OutstandingController', 'index']);
        $app->get('/overdue', ['OverdueController', 'index']);

        $app->get('/revenue', ['RevenueController', 'index']);
        $app->get('/best-seller', ['BestSellerController', 'index']);

        $app->get('/company', ['CompanyController', 'index']);
        $app->get('/company/info', ['CompanyController', 'editInfo']);
        $app->post('/company/info', ['CompanyController', 'editInfo']);
        $app->get('/company/contact', ['CompanyController', 'editContact']);
        $app->post('/company/contact', ['CompanyController', 'editContact']);
        $app->post('/company/logo', ['CompanyController', 'uploadLogo']);
        $app->post('/company/signature', ['CompanyController', 'uploadSignature']);

        $app->get('/user', ['UserController', 'index']);
        $app->get('/user/add', ['UserController', 'add']);
        $app->post('/user/add', ['UserController', 'add']);
        $app->get('/user/edit', ['UserController', 'edit']);
        $app->post('/user/edit', ['UserController', 'edit']);
        $app->get('/user/delete', ['UserController', 'delete']);
    }
}