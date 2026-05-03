<?php

namespace Src\Core;

use PDO;

class Database
{
    public static function connect()
    {
        $config = require __DIR__ . '/../config/database.php';
        $pdo = new PDO(
            $config['dsn'],
            $config['user'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
