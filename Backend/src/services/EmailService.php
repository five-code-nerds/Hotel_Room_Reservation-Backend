<?php

namespace Src\Services;

use Src\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use Src\Exceptions\UserNotFoundException;

class EmailService
{
    public static function sendVerificationCode($email, $user, $code)
    {
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = "smtp.gmail.com";
        $mailer->SMTPAuth = true;
        $mailer->Username = $_ENV['EMAIL'];
        $mailer->Password =  $_ENV['APP'];
        $mailer->SMTPSecure = "tls";
        $mailer->Port = 587;
        $mailer->setFrom($_ENV['EMAIL'], "Verify your account");
        $mailer->addAddress($email);
        $mailer->Subject = "Email Verification";
        $mailer->AltBody = "Hi $user,\n\n Your verification code is:\n\t\t$code\nYour code is expire in 5 minutes.\nIf this was not you, Ignore this email. \n\nThank you, \nHorizon Hotel Team";
        $mailer->isHTML(true);
        $mailer->Body = "
                <p>
                Hi <strong>$user</strong>,<br>
                Your verification code is:<br>
                <h2 style='color: #3d93cd; padding-left: 2em;'>$code</h2><br>
                Your code expire in 5 minutes.<br>
                Please don't share your code with anyone.<br>
                If this was not you, Ignore this email.<br>
                Thank you,<br> <strong>Hotel horizon Team</strong>
                </p>
            ";
        $mailer->send();
    }

    public function sendOtp($email)
    {
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        $verification_code = random_int(100000, 999999);
        $expire_time = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $userModel->otpResendUpdate($email, $verification_code, $expire_time);
        $this->sendVerificationCode($email, $user['name'], $verification_code);
    }
}
