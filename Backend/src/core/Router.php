<?php

namespace Src\Core;

class Router
{
    private $routes = [];

    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$uri])) {
            [$class, $action] = $this->routes[$method][$uri];

            $controller = new $class();
            $controller->$action();
        } else {
            http_response_code(404);
            echo "Route not found";
        }
    }
}
