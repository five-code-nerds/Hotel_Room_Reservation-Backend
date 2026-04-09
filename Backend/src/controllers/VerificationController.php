<?php

namespace Src\Controllers;

use Src\Exceptions\ValidationException;
use Src\Services\EmailService;
use Src\Services\AuthService;

class VerificationController
{
    private $emailService;
    private $authService;
    public function __construct()
    {
        $this->emailService = new EmailService();
        $this->authService = new AuthService();
    }
    public function sendOtp()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        if (!$email) {
            throw new ValidationException("Email is required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format");
        }
        $this->emailService->sendOtp($email);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => "OTP sent"
        ]);
    }
    public function verify()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        $code = htmlspecialchars(trim($data['code'])) ?? "";
        if (!$code) {
            throw new ValidationException("Verification code is required");
        }
        if (!$email) {
            throw new ValidationException("Email is required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format");
        }
        $this->authService->verifyEmail($email, $code);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => "Email verified"
        ]);
    }
}
