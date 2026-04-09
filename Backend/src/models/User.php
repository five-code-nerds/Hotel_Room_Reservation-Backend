<?php

namespace Src\Models;
use Src\Core\Database;
use PDO;
class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($name, $email, $password, $phone)
    {

        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, phone, verification_code, is_verified , code_expires) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([$name, $email, $password, $phone, null, 0 , null]);

        return [
            "user" => [
                "id" => $this->db->lastInsertId(),
                "name" => $name,
                "email" => $email,
                "phone" => $phone
            ]
        ];
    }
    public function getUserByEmail($email)
    {

        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function verificationUpdate($email) {

        $stmt = $this->db->prepare(
            "UPDATE users SET is_verified = 1, verification_code = null, code_expires = null WHERE email = ?"
        );

        return $stmt->execute([$email]);
    }

    public function otpResendUpdate($email, $code, $expire_time)
    {

        $stmt = $this->db->prepare(
            "UPDATE users SET is_verified = 0, verification_code = ?, code_expires = ? WHERE email = ?"
        );

        return $stmt->execute([$code, $expire_time, $email]);
    }
}
?>