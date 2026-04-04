<?php

namespace Src\Controllers;

use Src\Services\AuthService;
use Src\Exceptions;
use Src\Exceptions\ValidationException;

class AuthController
{

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = htmlspecialchars(trim($data['name'])) ?? "";
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        $password = htmlspecialchars(trim($data['password'])) ?? "";
        $phone = htmlspecialchars(trim($data['phone'])) ?? "";

        if (!$name) {
            throw new ValidationException("Name is required");
        }
        if (!$email) {
            throw new ValidationException("Email is required");
        }
        if (!$phone) {
            throw new ValidationException("Phone number is required");
        }
        if (!$password) {
            throw new ValidationException("Password is required");
        }
        $authService = new AuthService();
        $user = $authService->register($name, $email, $password, $phone);
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "data" => $user['user']
        ]);
    }

    public function user()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        if (!$email) {
            throw new ValidationException("Email is required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email is format");
        }
        $authService = new AuthService();
        $user = $authService->getUser($email);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => [
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" =>  $user['phone'],
            ]
        ]);
    }
}
