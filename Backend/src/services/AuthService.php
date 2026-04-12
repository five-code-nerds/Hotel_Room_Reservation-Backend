<?php

namespace Src\Services;

use Src\Exceptions\EmailNotVerifiedException;
use Src\Exceptions\UserNotFoundException;
use Src\Exceptions\ValidationException;
use Src\Models\User;

class AuthService
{

    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function register($name, $email, $password, $phone)
    {
        $user = $this->userModel->getUserByEmail($email);
        if ($user) {
            throw new ValidationException("User already exists");
        }
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $this->userModel->create($name, $email, $hashed, $phone);
        return [
            'message' => 'User account created',
            'data' => null
        ];
    }

    public function verifyEmail($email, $verification_code)
    {
        $user = $this->userModel->getUserByEmail($email);
        $now = date("Y-m-d H:i:s");
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        if ($user['code_expires'] < $now) {
            throw new EmailNotVerifiedException("OTP expired");
        }
        if ($user['verification_code'] == $verification_code) {
            $this->userModel->verificationUpdate($email);
            return [
                'message' => 'Email verified',
                'data' => null
            ];
        }
    }

    public function getUser($email)
    {
        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        return [
            'message' => 'User account created',
            'data' => [
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" =>  $user['phone']
            ]
        ];
    }
}
