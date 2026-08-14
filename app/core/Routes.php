<?php

class Routes {
    public function run() {
        $router = new App();

        if (isset($_SESSION['user_id']) && isset($_SESSION['company_id'])) {
            $router->setDefaultController('ErrorController');
            $router->setDefaultMethod('index');
        } else {
            $router->setDefaultController('AuthController');
            $router->setDefaultMethod('showLoginForm');
        }
        
        $router->get('/login', ['AuthController', 'showLoginForm']);
        $router->post('/login/store', ['AuthController', 'login']);
        $router->get('/register', ['AuthController', 'showRegisterForm']);
        $router->post('/register/store', ['AuthController', 'register']);
        $router->get('/logout', ['AuthController', 'logout']);

        $router->get('/dashboard', ['DashboardController', 'index']);

        $router->get('/item', ['ItemController', 'index']);
        $router->get('/item/add', ['ItemController', 'add']);
        $router->post('/item/add', ['ItemController', 'add']);
        $router->get('/item/edit', ['ItemController', 'edit']);
        $router->post('/item/edit', ['ItemController', 'edit']);
        $router->get('/item/delete', ['ItemController', 'delete']);
        
        $router->get('/customer', ['CustomerController', 'index']);
        $router->get('/customer/add', ['CustomerController', 'add']);
        $router->post('/customer/add', ['CustomerController', 'add']);
        $router->get('/customer/edit', ['CustomerController', 'edit']);
        $router->post('/customer/edit', ['CustomerController', 'edit']);
        $router->get('/customer/delete', ['CustomerController', 'delete']);
        
        $router->get('/pic', ['PicController', 'index']);
        $router->get('/pic/add', ['PicController', 'add']);
        $router->post('/pic/add', ['PicController', 'add']);
        $router->get('/pic/edit', ['PicController', 'edit']);
        $router->post('/pic/edit', ['PicController', 'edit']);
        $router->get('/pic/delete', ['PicController', 'delete']);
        
        $router->get('/invoice', ['InvoiceController', 'index']);
        $router->get('/invoice/add', ['InvoiceController', 'add']);
        $router->post('/invoice/add', ['InvoiceController', 'add']);
        $router->get('/invoice/edit', ['InvoiceController', 'edit']);
        $router->post('/invoice/edit', ['InvoiceController', 'edit']);
        $router->get('/invoice/delete', ['InvoiceController', 'delete']);

        $router->get('/invoice/detail', ['DetailController', 'index']);
        $router->get('/detail/add', ['DetailController', 'add']);
        $router->post('/detail/add', ['DetailController', 'add']);
        $router->get('/detail/edit', ['DetailController', 'edit']);
        $router->post('/detail/edit', ['DetailController', 'edit']);
        $router->get('/detail/delete', ['DetailController', 'delete']);

        $router->get('/payment', ['PaymentController', 'index']);
        $router->get('/payment/add', ['PaymentController', 'add']);
        $router->post('/payment/add', ['PaymentController', 'add']);
        $router->get('/payment/edit', ['PaymentController', 'edit']);
        $router->post('/payment/edit', ['PaymentController', 'edit']);
        $router->get('/payment/delete', ['PaymentController', 'delete']);

        $router->get('/outstanding', ['OutstandingController', 'index']);
        $router->get('/overdue', ['OverdueController', 'index']);

        $router->get('/revenue', ['RevenueController', 'index']);
        $router->get('/best-seller', ['BestSellerController', 'index']);
        
        $router->get('/company', ['CompanyController', 'index']);
        $router->get('/company/info', ['CompanyController', 'editInfo']);
        $router->post('/company/info', ['CompanyController', 'editInfo']);
        $router->get('/company/contact', ['CompanyController', 'editContact']);
        $router->post('/company/contact', ['CompanyController', 'editContact']);
        $router->post('/company/logo', ['CompanyController', 'uploadLogo']);
        $router->post('/company/signature', ['CompanyController', 'uploadSignature']);

        $router->get('/user', ['UserController', 'index']);
        $router->get('/user/add', ['UserController', 'add']);
        $router->post('/user/add', ['UserController', 'add']);
        $router->get('/user/edit', ['UserController', 'edit']);
        $router->post('/user/edit', ['UserController', 'edit']);
        $router->get('/user/delete', ['UserController', 'delete']);

        $router->run();
    }
}