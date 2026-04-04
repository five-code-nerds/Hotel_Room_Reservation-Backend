<?php

namespace Src\Services;

use Src\Exceptions\EmailNotVerifiedException;
use Src\Exceptions\InvalidCredentialException;
use Src\Exceptions\UserNotFoundException;
use Src\Models\User;

class LoginService
{
    public static function login($email, $password)
    {
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        if (!password_verify($password, $user['password'])) {
            throw new InvalidCredentialException("Invalid Credentials");
        }
        if ($user['is_verified'] != true) {
            throw new EmailNotVerifiedException("Email not verified");
        }
        return [
            'user' => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
            ]
        ];
    }
}
