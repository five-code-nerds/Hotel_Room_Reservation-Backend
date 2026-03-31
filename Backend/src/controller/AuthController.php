<?php

namespace Src\Controller;

use Src\Service\AuthService;

class AuthController {

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? "");
        $email = trim($data['email'] ?? "");
        $password = trim($data['password'] ?? "");
        $phone = trim($data['phone'] ?? "");

        $service = new AuthService();

        try {
            $user = $service->register($name, $email, $password, $phone);
            echo json_encode([
                "status" => "success",
                "data" => $user
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = trim($data['email'] ?? "");
        $password = trim($data['password'] ?? "");
        if (!$email || !$password) {
            throw new \Exception("Email and Password are required");
        }
        $service = new AuthService();

        try {
            $user = $service->login($email, $password);
            echo json_encode([
                "status" => "success",
                "data" => $user['user']
            ]);
        } catch (\Exception $e) {
            http_response_code($user['code']);
            echo json_encode([
                "status" => "error",
                "message" => $user['error']
            ]);
        }
    }
}
?>