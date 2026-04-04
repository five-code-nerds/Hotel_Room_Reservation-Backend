<?php

    namespace Src\Services;
    use Src\Models\User;

    class LoginService {
    public static function login($email, $password)
    {


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format", 400);
        }
        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new \Exception("Password must be at least 8 characters and only include letters, numbers, underscore", 400);
        }
        try {
            $user = User::getByEmail($email);
        } catch (\PDOException $e) {
            throw new \Exception("Internal server error", 500);
        }
        if (!$user) {
            throw new \Exception("User not found!", 404);
        }
        if (!password_verify($password, $user['password'])) {
            throw new \Exception("Invalid Credentials", 401);
        }
        if ($user['is_verified'] != true) {
            throw new \Exception("Please verify your email", 400);
        }
        return [
            'success' => true,
            'user' => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
            ]
        ];
    }
    }

?>