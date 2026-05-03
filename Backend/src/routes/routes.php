<?php

use Src\Controllers\AuthController;
use Src\Controllers\BookingController;
use Src\Controllers\LoginController;
use Src\Controllers\PaymentController;
use Src\Controllers\ReservationController;
use Src\Controllers\VerificationController;
use Src\Controllers\RoomController;
use Src\Middlewares\AdminMiddleware;
use Src\Middlewares\AuthMiddleware;

$router->post('/register', ['controller' => [AuthController::class, 'register']]);
$router->post('/login', ['controller' => [LoginController::class, 'login']]);
$router->post('/verify-email', ['controller' => [VerificationController::class, 'verify']]);
$router->post('/send-otp', ['controller' => [VerificationController::class, 'sendOtp']]);
$router->get('/rooms', ['controller' => [RoomController::class, 'getAvailableRooms']]);
$router->post('/payment/webhook', ['controller' => [PaymentController::class, 'webhook']]);

$router->get('/user', [
    'controller' => [AuthController::class, 'user'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle']
    ]
]);

$router->patch('/cancel', [
    'controller' => [BookingController::class, 'cancel'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle']
    ]
]);

$router->post('/reservations', [
    'controller' => [BookingController::class, 'book'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle']
    ]
]);
$router->post('/admin/rooms', [
    'controller' => [RoomController::class, 'createRoom'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
$router->post('/admin/room-types', [
    'controller' => [RoomController::class, 'createRoomType'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
$router->get('/reservations', [
    'controller' => [ReservationController::class, 'getReservation'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle']
    ]
]);

$router->get('/admin/rooms', [
    'controller' => [RoomController::class, 'getAllRooms'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
$router->get('/admin/reservations', [
    'controller' => [ReservationController::class, 'getAllReservations'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
$router->patch('/admin/rooms/price', [
    'controller' => [RoomController::class, 'updateRoomPrice'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
$router->patch('/admin/rooms/status', [
    'controller' => [RoomController::class, 'disableRoom'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
$router->patch('/admin/reservations/cancel', [
    'controller' => [ReservationController::class, 'cancel'],
    'middleware' => [
        [AuthMiddleware::class, 'authHandle'],
        [AdminMiddleware::class, 'isAdmin']
    ]
]);
