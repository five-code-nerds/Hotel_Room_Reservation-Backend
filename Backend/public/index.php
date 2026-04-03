<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Src\Core\Router;
    $router = new Router();
    require __DIR__ . '/../src/routes/routes.php';
    $router->resolve();
?>