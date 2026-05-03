<?php

namespace Src\Controllers;

use Src\Services\AuthService;
use Src\Exceptions\ValidationException;

class AuthController
{

    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }
    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? null);
        $email = trim($data['email'] ?? null);
        $password = trim($data['password'] ?? null);
        $phone = trim($data['phone'] ?? null);

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
        if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            throw new ValidationException("Name must contain only letters and space");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format");        }
        if (!preg_match("/^(09|07)\d{8}$/", $phone)) {
            throw new ValidationException("Phone must start with 09 or 07 and have 10 digits");
        }
        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new ValidationException("Password must be at least 8 characters and only include letters, numbers, underscore");
        }
        $result = $this->authService->register($name, $email, $password, $phone);
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => $result['message'],
            "data" => $result['data']
        ]);
    }

    public function user()
    {
        $user = $_REQUEST['user'];
        if ($user) {
            $userId = $_REQUEST['user']->sub;
            $result = $this->authService->getUserById($userId);
        } else {
            $data = json_decode(file_get_contents("php://input"), true);
            $email = trim($data['email'] ?? null);
            if (!$email) {
                throw new ValidationException("Email is required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email is format");
            }
            $result = $this->authService->getUserByEmail($email);
        }
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => $result['message'],
            "data" => [
                "name" => $result['data']['name'],
                "email" => $result['data']['email'],
                "phone" =>  $result['data']['phone']
            ]
        ]);
    }
}
