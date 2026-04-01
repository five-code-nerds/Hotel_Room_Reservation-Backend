<?php

    namespace Src\Controller;

    use Src\Service\LoginService;

    class LoginController {
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = trim($data['email'] ?? "");
        $password = trim($data['password'] ?? "");
        $service = new LoginService();

        try {
            if (!$email || !$password) {
                throw new \Exception("Both Email and Password are required", 400);
            }
            $user = $service->login($email, $password);
            echo json_encode([
                http_response_code(200),
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