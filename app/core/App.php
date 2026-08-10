<?php

class App {
    private $controller = 'ErrorController';
    private $method = 'index';
    private $params = [];
    private const DEFAULT_GET = 'GET';
    private const DEFAULT_POST = 'POST';
    private $handlers = [];
    
    public function setDefaultController($controller) {
        $this->controller = $controller;
    }

    public function setDefaultMethod($method) {
        $this->method = $method;
    }

    public function get($uri, $callback) {
        $this->setHandler(self::DEFAULT_GET, $uri, $callback);
    }

    public function post($uri, $callback) {
        $this->setHandler(self::DEFAULT_POST, $uri, $callback);
    }

    private function setHandler(string $method, string $path, $handler) {
        $this->handlers[$method . $path] = [    
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function run() {
        $execute = 0;
        $url = $this->getUrl();
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->handlers as $handler) {
            $path = explode('/', trim($handler['path'], '/'));
            
            $kurl = (isset($url[0]) ? $url[0] : '') . (isset($url[1]) ? $url[1] : '');
            $kpath = (isset($path[0]) ? $path[0] : '') . (isset($path[1]) ? $path[1] : '');

            if ($kurl != "" && $kurl == $kpath && $requestMethod == $handler['method']) {
                if (
                    isset($handler['handler'][0]) &&
                    file_exists(__DIR__ . '/../controllers/' . $handler['handler'][0] . '.php')
                ) {
                    $this->controller = $handler['handler'][0];
                    unset($url[0]);
                }

                require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
                $this->controller = new $this->controller;
                $execute = 1;

                if (isset($handler['handler'][1])) {
                    $this->method = $handler['handler'][1];
                    if (isset($url[1])) {
                        unset($url[1]);
                    }
                }
            }
        }

        if ($execute == 0) {
            require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;
        }

        if (!empty($url)) {
            $this->params = array_values($url);
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function getUrl()
    {
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        $routePart = explode('&', $queryString, 2)[0];

        $url = rtrim($routePart, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
    }
}