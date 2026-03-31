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
            if (!$name) {
                throw new \Exception("Name is required");
            }
            if (!$email) {
                throw new \Exception("Email is required");
            }
            if (!$phone) {
                throw new \Exception("Phone number is required");
            }
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
}
?>