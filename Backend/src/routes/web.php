<?php
    use Src\Controller\AuthController;
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/login', [AuthController::class, 'login']);

?>