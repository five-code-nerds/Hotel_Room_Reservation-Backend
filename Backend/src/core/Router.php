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
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $route = $this->routes[$method][$uri];

        if (isset($route)) {
            [$controllerClass, $controllerAction] = $route['controller'];

            if (isset($route['middleware'])) {
               foreach ($route['middleware'] as $middleware) {
                    [$middlewareClass, $middlewareAction] = $middleware;
                    $action = new $middlewareClass();
                    $action->$middlewareAction();
               }
            }

            $controller = new $controllerClass();
            $controller->$controllerAction();
        } else {
            http_response_code(404);
            echo "Route not found";
        }
    }
}
