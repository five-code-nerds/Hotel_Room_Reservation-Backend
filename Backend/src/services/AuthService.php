<?php

namespace Src\Services;

use Src\Models\User;
class AuthService {

    public static function register($name, $email, $password, $phone) {

        if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            throw new \Exception("Name can only contain letters and spaces", 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format", 400);
        }
        if (!preg_match("/^(09|07)\d{8}$/", $phone)) {
            throw new \Exception("Phone must start with 09 or 07 and have 10 digits", 400);
        }

        if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $password)) {
            throw new \Exception("Password must be at least 8 characters and only include letters, numbers, underscore", 400);
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        try {
            return User::create($name, $email, $hashed, $phone);
        } catch(\PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function verifyEmail($email, $verification_code)
    {
        try {
            $user = User::getByEmail($email);
            $now = date("Y-m-d H:i:s");
            if (!$user) {
                throw new \Exception("User not found", 400);
            }
            if ($user['code_expires'] < $now) {
                throw new \Exception("OTP expired", 400);
            }
            if ($user['verification_code'] == $verification_code) {
                return User::verificationUpdate($email);
            }
            return null;
        } catch (\PDOException $e) {
            throw new \Exception("Internal server error", 500);
        }
    }
    
    public static function getUser($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format", 400);
        }
        try {
            $user = User::getByEmail($email);
            if (!$user) {
                throw new \Exception("User is not found", 400);
            }
            return $user;
        } catch (\PDOException $e) {
            throw new \Exception("Internal server error", 500);
        }
    }
}
?>