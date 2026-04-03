<?php
    use Src\Controller\AuthController;
    use Src\Controller\LoginController;

    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/login', [LoginController::class, 'login']);

?>