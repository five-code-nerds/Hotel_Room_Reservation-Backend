<?php

namespace Src\Model;

use Src\Core\Database;
use PDO;

class User
{

    public function create($name, $email, $password, $phone)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([$name, $email, $password, $phone]);

        return [
            "id" => $db->lastInsertId(),
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        ];
    }
    public function getByEmail($email)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>