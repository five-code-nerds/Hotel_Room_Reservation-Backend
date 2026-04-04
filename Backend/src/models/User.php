<?php

namespace Src\Models;

use Src\Core\Database;
use PDO;
class User
{

    public static function create($name, $email, $password, $phone)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "INSERT INTO users (name, email, password, phone, verification_code, is_verified , code_expires) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([$name, $email, $password, $phone, null, 0 , null]);

        return [
            "success" => true, 
            "user" => [
                "id" => $db->lastInsertId(),
                "name" => $name,
                "email" => $email,
                "phone" => $phone
            ]
        ];
    }
    public static function getByEmail($email)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function verificationUpdate($email) {
        $db = Database::connect();

        $stmt = $db->prepare(
            "UPDATE users SET is_verified = 1, verification_code = null, code_expires = null WHERE email = ?"
        );

        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function otpResendUpdate($email, $code, $expire_time)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "UPDATE users SET is_verified = false, verification_code = ?, code_expires = ? WHERE email = ?"
        );

        $stmt->execute([$code, $expire_time, $email]);
    }
}
?>