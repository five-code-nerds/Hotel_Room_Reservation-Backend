<?php

namespace Src\Services;

use Src\Exceptions\EmailNotVerifiedException;
use Src\Exceptions\UserNotFoundException;
use Src\Models\User;

class AuthService
{

    public static function register($name, $email, $password, $phone)
    {

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $userModel = new User();
        return $userModel->create($name, $email, $hashed, $phone);
    }

    public static function verifyEmail($email, $verification_code)
    {
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);
        $now = date("Y-m-d H:i:s");
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        if ($user['code_expires'] < $now) {
            throw new EmailNotVerifiedException("OTP expired");
        }
        if ($user['verification_code'] == $verification_code) {
            return $userModel->verificationUpdate($email);
        }
    }

    public static function getUser($email)
    {
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        return $user;
    }
}
