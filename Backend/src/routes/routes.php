<?php
    use Src\Controllers\AuthController;
    use Src\Controllers\LoginController;
    use Src\Controllers\ReservationController;
    use Src\Controllers\VerificationController;
    use Src\Controllers\RoomController;
    use Src\Middlewares\AuthMiddleware;

    $router->post('/register', ['controller' =>[AuthController::class, 'register']]);
    $router->post('/login', ['controller' =>[LoginController::class, 'login']]);
    $router->post('/verify-email', ['controller' =>[VerificationController::class, 'verify']]);
    $router->post('/send-otp', ['controller' =>[VerificationController::class, 'sendOtp']]);
    $router->post('/reservaions', ['controller' => [ReservationController::class, 'book']]);
    $router->get('/user', ['controller' => [AuthController::class, 'user']]);
    $router->get('/rooms', ['controller' => [RoomController::class, 'getAvailableRooms']]);
    $router->get('/reservations', ['controller' => [ReservationController::class, 'getReservation']]);
    $router->patch('/cancel', ['controller' => [ReservationController::class, 'cancel']]);

    $router->get('/admin/rooms', [
        'controller' => [RoomController::class, 'getAllRooms'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
    $router->patch('/admin/rooms/{room_number}/status', [
        'controller' => [RoomController::class, 'updateRoomPrice'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
    $router->patch('/admin/rooms/{room_number}/disable', [
        'controller' => [RoomController::class, 'disableRoom'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
    $router->patch('/admin/rooms/{room_number}/cancel', [
        'controller' => [ReservationController::class, 'cancel'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
    $router->patch('/admin/reservations', [
        'controller' => [ReservationController::class, 'getAllReservations'],
        'middleware' => [
            [AuthMiddleware::class, 'isAdmin']
        ]
    ]);
?>