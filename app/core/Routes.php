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
        $router->post('/item/store', ['ItemController', 'store']);
        $router->get('/item/edit', ['ItemController', 'edit']);
        $router->post('/item/update', ['ItemController', 'update']);
        $router->get('/item/delete', ['ItemController', 'delete']);

        $router->run();
    }
}