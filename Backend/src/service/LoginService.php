<?php

    namespace Src\Service;
    use Src\Model\User;

    class LoginService {
    public function login($email, $password)
    {


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }
        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new \Exception("Password must be at least 8 characters and only include letters, numbers, underscore");
        }
        $userModel = new User();

        $user = $userModel->getByEmail($email);
        if (!$user) {
            return [
                "error" => "User not found!",
                "code" => 404
            ];
        }
        if (!password_verify($password, $user['password'])) {
            return [
                "error" => "Invalid Credentials",
                "code" => 401
            ];
        }
        return [
            "success" => true,
            'user' => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
            ]
        ];
    }
    }

?>