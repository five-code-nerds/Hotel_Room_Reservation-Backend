<?php

    namespace Src\Controllers;
    use Src\Services\LoginService;

    class LoginController {
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        $password = htmlspecialchars(trim($data['password'])) ?? "";
        try {
            if (!$email || !$password) {
                throw new \Exception("Both Email and Password are required", 400);
            }
            $user = LoginService::login($email, $password);
            http_response_code(200);
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
    }

?>