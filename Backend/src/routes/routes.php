<?php
    use Src\Controllers\AuthController;
    use Src\Controllers\LoginController;
    use Src\Controllers\VerificationController;

    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/login', [LoginController::class, 'login']);
    $router->post('/verify-email', [VerificationController::class, 'verify']);
    $router->post('/send-otp', [VerificationController::class, 'sendOtp']);

    $router->get('/user', [AuthController::class, 'user']);


?>