<?php

class Routes {
    public function run() {
        $router = new App();
        $router->setDefaultController('AuthController');
        $router->setDefaultMethod('showLoginForm');

        $router->get('/login', ['AuthController', 'showLoginForm']);
        $router->post('/login/store', ['AuthController', 'login']);
        $router->get('/register', ['AuthController', 'showRegisterForm']);
        $router->post('/register/store', ['AuthController', 'register']);

        $router->run();
    }
}