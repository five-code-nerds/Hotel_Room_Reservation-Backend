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

    public function delete($path, $handler)
    {
        $this->routes['DELETE'][$path] = $handler;
    }
    public function put($path, $handler)
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function patch($path, $handler)
    {
        $this->routes['PATCH'][$path] = $handler;
    }
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, PUT, OPTIONS");
        if ($method === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
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
