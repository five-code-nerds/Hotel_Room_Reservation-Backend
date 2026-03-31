<?php

namespace Src\Service;

use Src\Model\User;

class AuthService {

    public function register($name, $email, $password, $phone) {

        if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            throw new \Exception("Name can only contain letters and spaces");
        }
        if (!$email) {
            throw new \Exception("Email is required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }
        if (!$phone) {
            throw new \Exception("Phone number is required");
        }
        if (!preg_match("/^(09|07)\d{8}$/", $phone)) {
            throw new \Exception("Phone must start with 09 or 07 and have 10 digits");
        }
        if (!$password) {
            throw new \Exception("Password is required");
        }
        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new \Exception("Password must be at least 8 characters and only include letters, numbers, underscore");
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $userModel = new User();
        return $userModel->create($name, $email, $hashed, $phone);
    }
    
}
?>