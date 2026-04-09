<?php
    namespace Src\Core;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class JWTHandler {
        private $secret;
        public function __construct()
        {
            $this->secret = require __DIR__ . '/../config/jwt.php';
        }
        public function encode($payload) {
            return JWT::encode($payload, $this->secret['secret'], 'HS256');
        }
        public function decode($token) {
            $decoded = JWT::decode($token, new Key($this->secret['secret'], 'HS256'));
            $_REQUEST['user'] = $decoded;
            return $decoded;
        }
    }
?>