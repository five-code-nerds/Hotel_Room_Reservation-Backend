<?php

namespace Src\Controllers;

use Src\Services\AuthService;
use Src\Exceptions\ValidationException;

class AuthController
{

    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }
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
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format");        }
        if (!preg_match("/^(09|07)\d{8}$/", $phone)) {
            throw new ValidationException("Phone must strat with 09 or 07 and have 10 digits");
        }
        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new ValidationException("Password must be at least 8 characters and only include letters, numbers, underscore");
        }
        $user = $this->authService->register($name, $email, $password, $phone);
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
        $user = $this->authService->getUser($email);
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
