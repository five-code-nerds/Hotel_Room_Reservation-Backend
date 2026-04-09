<?php

namespace Src\Controllers;

use Src\Exceptions\ValidationException;
use Src\Services\LoginService;

class LoginController
{
    private $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        $password = htmlspecialchars(trim($data['password'])) ?? "";
        if (!$email) {
            throw new ValidationException("Email is required");
        }
        if (!$password) {
            throw new ValidationException("Password is required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format");
        }
        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new ValidationException("Password must be at least 8 characters and only include letters, numbers, underscore");
        }
        $user = $this->loginService->login($email, $password);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $user['user'],
            'access_token' => $user['token']
        ]);
    }
}
