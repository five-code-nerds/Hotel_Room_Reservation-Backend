<?php

namespace Src\Services;

use Src\Exceptions\EmailNotVerifiedException;
use Src\Exceptions\InvalidCredentialException;
use Src\Exceptions\UserNotFoundException;
use Src\Models\User;
use Src\Core\JWTHandler;

class LoginService
{
    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function login($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        if (!password_verify($password, $user['password'])) {
            throw new InvalidCredentialException("Invalid Credentials");
        }
        if ($user['is_verified'] != true) {
            throw new EmailNotVerifiedException("Email not verified");
        }
        $payload = [
            'sub' => $user['id'],
            'role' => $user['role'],
            'iat' => time(),
            'exp'=> time() + 3600
        ];
        $jwtHandeler = new JWTHandler();
        $token = $jwtHandeler->encode($payload);
        return [
            'user' => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "role" => $user['role']
            ],
            'token' => $token
        ];
    }
}
