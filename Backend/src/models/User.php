<?php

namespace Src\Models;

use PDO;
use PDOException;
use Src\Core\Database;
use Src\Exceptions\DatabaseException;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($name, $email, $password, $phone)
    {
        try {
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
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function getUserByEmail($email)
    {
        try {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function getUserById($userId)
    {
        try {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = ?"
        );

        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function verificationUpdate($email) {
        try {
        $stmt = $this->db->prepare(
            "UPDATE users SET is_verified = 1, verification_code = null, code_expires = null WHERE email = ?"
        );

        return $stmt->execute([$email]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function otpResendUpdate($email, $code, $expire_time)
    {

        try {

            $stmt = $this->db->prepare(
            "UPDATE users SET is_verified = 0, verification_code = ?, code_expires = ? WHERE email = ?"
            );

            return $stmt->execute([$code, $expire_time, $email]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
?>