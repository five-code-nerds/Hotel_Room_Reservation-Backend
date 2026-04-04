<?php
    namespace Src\Controllers;
    use Src\Services\EmailService;
    class VerificationController {

        public function sendOtp() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        try {
            if (!$email) {
                throw new \Exception("Email is needed", 400);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Invalid email format", 400);
            }
            $emailService = new EmailService();
            $is_sent = $emailService->sendOtp($email);
            echo json_encode([
                "status" => $is_sent ? "success" : "error",
                "data" => ""
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
        }
        public function verify() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = htmlspecialchars(trim($data['email'])) ?? "";
        $code = htmlspecialchars(trim($data['code'])) ?? "";
        try {
            if (!$code) {
                throw new \Exception("Verification code needed", 400);
            }
            if (!$email) {
                throw new \Exception("Email is needed", 400);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Invalid email format", 400);
            }
            $is_verified = EmailService::verifyEmail($email, $code);
            if ($is_verified) {
                http_response_code(200);
            } else {
                http_response_code(401);
            }
            echo json_encode([
                "status" => $is_verified ? "success" : "error",
                "data" => $is_verified ? "verified" : "not verified"
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