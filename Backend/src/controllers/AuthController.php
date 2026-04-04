<?php

namespace Src\Controllers;
use Src\Services\AuthService;

class AuthController {

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = htmlspecialchars(trim($data['name'])) ?? "";
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        $password = htmlspecialchars(trim($data['password'])) ?? "";
        $phone = htmlspecialchars(trim($data['phone'])) ?? "";
        
        try {
            if (!$name) {
                throw new \Exception("Name is required", 400);
            }
            if (!$email) {
                throw new \Exception("Email is required", 400);
            }
            if (!$phone) {
                throw new \Exception("Phone number is required", 400);
            }
            if (!$password) {
                throw new \Exception("Password is required", 400);
            }
            $user = AuthService::register($name, $email, $password, $phone);
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "data" => $user['user']
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function user() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        try {
            if (!$email) {
                throw new \Exception("Email is required", 400);
            }
            $user = AuthService::getUser($email);
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "phone" =>  $user['phone'],
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
?>