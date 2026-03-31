<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Src\Core\Router;
    use Src\Controller\AuthController;
    $router = new Router();
    require __DIR__ . '/../src/routes/web.php';
    $router->resolve();
?>