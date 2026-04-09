<?php
    use Src\Controllers\AuthController;
    use Src\Controllers\LoginController;
    use Src\Controllers\VerificationController;
    use Src\Controllers\RoomController;
    use Src\Middlewares\AuthMiddleware;

    $router->post('/register', ['controller' =>[AuthController::class, 'register']]);
    $router->post('/login', ['controller' =>[LoginController::class, 'login']]);
    $router->post('/verify-email', ['controller' =>[VerificationController::class, 'verify']]);
    $router->post('/send-otp', ['controller' =>[VerificationController::class, 'sendOtp']]);
    $router->get('/user', ['controller' => [AuthController::class, 'user']]);

    $router->get('/admin/rooms', [
        'controller' => [RoomController::class, 'getAllRooms'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
    $router->patch('/admin/rooms/status', [
        'controller' => [RoomController::class, 'updateRoom'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
    $router->patch('/admin/rooms/disable-room', [
        'controller' => [RoomController::class, 'diableRoom'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
?>